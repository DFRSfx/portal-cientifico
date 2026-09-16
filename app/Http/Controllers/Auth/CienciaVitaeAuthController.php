<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CienciaVitaeAuthController extends Controller
{
    /**
     * Redirect the user to the Ciência Vitae OAuth authorization server
     */
    public function redirect(Request $request)
    {
        $state = Str::random(40);
        session()->put('ciencia_vitae_oauth_state', $state);

        $redirectTo = $request->query('redirect_to', $request->input('redirect_to'));
        if ($redirectTo && !str_contains($redirectTo, '/login') && !str_contains($redirectTo, '/register')) {
            session()->put('ciencia_vitae_redirect_to', $redirectTo);
        } else {
            session()->put('ciencia_vitae_redirect_to', url()->previous());
        }

        $clientId = config('services.ciencia_vitae.client_id');
        $clientSecret = config('services.ciencia_vitae.client_secret');
        $oauthUrl = config('services.ciencia_vitae.oauth_url', 'https://autenticacao.cienciavitae.pt/oauth');
        $redirectUri = config('services.ciencia_vitae.redirect', route('auth.ciencia-vitae.callback'));
        $devMode = config('services.ciencia_vitae.dev_mode', true);

        // If real OAuth credentials are configured and not in forced dev mode, redirect to FCT OAuth
        if (!empty($clientId) && !empty($clientSecret) && !$devMode) {
            $authUrl = rtrim($oauthUrl, '/') . '/authorize?' . http_build_query([
                'client_id' => $clientId,
                'response_type' => 'code',
                'redirect_uri' => $redirectUri,
                'state' => $state,
                'scope' => 'read_profile',
            ]);

            return redirect()->away($authUrl);
        }

        // Development / Sandbox mode: Interactive SSO Selection Screen
        $sampleResearchers = User::whereNotNull('ciencia_vitae')
            ->where('ciencia_vitae', '!=', '')
            ->take(6)
            ->get();

        return view('auth.ciencia-vitae-sso-dev', [
            'state' => $state,
            'sampleResearchers' => $sampleResearchers,
            'isLinking' => Auth::check(),
        ]);
    }

    /**
     * Handle the OAuth callback from Ciência Vitae
     */
    public function callback(Request $request): RedirectResponse
    {
        if ($request->isMethod('get') && $request->filled('code')) {
            $savedState = session()->pull('ciencia_vitae_oauth_state');
            $inputState = $request->input('state');

            // State validation (CSRF protection for OAuth GET flow)
            if (empty($savedState) || $savedState !== $inputState) {
                return redirect()->route('login')->withErrors([
                    'email' => __('Falha na validação de segurança da autenticação Ciência Vitae (State inválido). Tente novamente.'),
                ]);
            }
        }

        $clientId = config('services.ciencia_vitae.client_id');
        $clientSecret = config('services.ciencia_vitae.client_secret');
        $oauthUrl = config('services.ciencia_vitae.oauth_url', 'https://autenticacao.cienciavitae.pt/oauth');
        $redirectUri = config('services.ciencia_vitae.redirect', route('auth.ciencia-vitae.callback'));

        $cienciaId = null;
        $name = null;
        $email = null;
        $affiliation = null;

        // If real OAuth code received
        if ($request->filled('code') && !empty($clientId) && !empty($clientSecret)) {
            $tokenResponse = Http::asForm()->post(rtrim($oauthUrl, '/') . '/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'code' => $request->input('code'),
            ]);

            if (!$tokenResponse->successful()) {
                return redirect()->route('login')->withErrors([
                    'email' => __('Erro ao obter autorização do servidor Ciência Vitae: ') . $tokenResponse->body(),
                ]);
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;
            $cienciaId = $tokenData['ciencia_id'] ?? $tokenData['sub'] ?? null;

            // Fetch researcher identifying profile info with the token
            if ($accessToken && $cienciaId) {
                $profileResponse = Http::withToken($accessToken)
                    ->get(config('services.ciencia_vitae.api_url', 'https://api.cienciavitae.pt/v1.1') . "/curriculum/{$cienciaId}/person-info");

                if ($profileResponse->successful()) {
                    $pData = $profileResponse->json();
                    $name = data_get($pData, 'person-info.names.full-name') ?? data_get($pData, 'person-info.names.citation-name');
                }
            }
        } else {
            // Development / Simulated Callback Mode
            $cienciaId = trim((string)$request->input('ciencia_id'));
            $name = trim((string)$request->input('name'));
            $email = trim((string)$request->input('email'));
            $affiliation = trim((string)$request->input('affiliation'));
        }

        // Validate identifier presence
        $rawIdentifier = trim((string)$cienciaId);
        if (empty($rawIdentifier)) {
            return redirect()->route('login')->withErrors([
                'email' => __('Nenhum Ciência ID ou nome foi fornecido na autenticação.'),
            ]);
        }

        // If not matching standard Ciência ID format (XXXX-XXXX-XXXX), attempt to resolve by Name or Email
        if (!preg_match('/^[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}$/', $rawIdentifier)) {
            $matchedUser = User::where('name', 'like', "%{$rawIdentifier}%")
                ->orWhere('email', $rawIdentifier)
                ->first();

            if ($matchedUser && !empty($matchedUser->ciencia_vitae)) {
                $cienciaId = $matchedUser->ciencia_vitae;
                $name = $name ?: $matchedUser->name;
                $email = $email ?: $matchedUser->email;
            } else {
                $matchedAuthor = \App\Models\Author::where('name', 'like', "%{$rawIdentifier}%")
                    ->whereNotNull('ciencia_vitae')
                    ->where('ciencia_vitae', '!=', '')
                    ->first();
                if ($matchedAuthor) {
                    $cienciaId = $matchedAuthor->ciencia_vitae;
                    $name = $name ?: $matchedAuthor->name;
                    $email = $email ?: $matchedAuthor->email;
                } else {
                    $cienciaId = $rawIdentifier;
                }
            }
        } else {
            $cienciaId = strtoupper($rawIdentifier);
        }

        // Try to fetch public information from Ciência Vitae API via existing controller if name/email missing
        if (empty($name) || empty($email)) {
            try {
                $cvController = app(\App\Http\Controllers\CienciaVitaeController::class);
                $apiResponse = $cvController->cienciaVitaeRequest("curriculum/" . urlencode($cienciaId) . "/identifying-info");
                if (is_array($apiResponse)) {
                    $name = $name ?: data_get($apiResponse, 'identifying-info.names.full-name');
                    $affiliation = $affiliation ?: data_get($apiResponse, 'identifying-info.affiliation.institution');
                }
            } catch (\Throwable $e) {
                \Log::warning("Could not fetch extra identifying-info for {$cienciaId}: " . $e->getMessage());
            }
        }

        // SCENARIO 1: User is already authenticated in the Portal and is LINKING their account
        if (Auth::check()) {
            $currentUser = Auth::user();

            // Verify if another user already has this Ciência ID
            $existingWithCienciaId = User::where('ciencia_vitae', $cienciaId)
                ->where('id', '!=', $currentUser->id)
                ->first();

            if ($existingWithCienciaId) {
                return redirect()->route('profile.edit')->withErrors([
                    'ciencia_vitae' => __("Este Ciência ID (:id) já está associado a outro utilizador (:email).", [
                        'id' => $cienciaId,
                        'email' => $existingWithCienciaId->email,
                    ]),
                ]);
            }

            $currentUser->ciencia_vitae = $cienciaId;
            $currentUser->save();

            // Ensure Author model exists
            if (!$currentUser->authorInformation) {
                $currentUser->authorInformation()->create([
                    'profile_is_public' => 1,
                    'profile_image_is_public' => 1,
                ]);
            }

            return redirect()->route('profile.edit')->with('status', __("O seu Ciência ID (:id) foi associado à sua conta com sucesso!", ['id' => $cienciaId]));
        }

        // SCENARIO 2: Match by existing Ciência ID in database
        $user = User::where('ciencia_vitae', $cienciaId)->first();

        // SCENARIO 3: If not matched by Ciência ID, check if user exists with matching email
        if (!$user && !empty($email)) {
            $userByEmail = User::where('email', $email)->first();
            if ($userByEmail) {
                // Associate this Ciência ID to the existing account
                $userByEmail->ciencia_vitae = $cienciaId;
                $userByEmail->is_active = 1;
                $userByEmail->save();

                $user = $userByEmail;
            }
        }

        // SCENARIO 4: No existing user found -> Create and save new account automatically
        if (!$user) {
            $generatedEmail = !empty($email) ? $email : (strtolower(str_replace('-', '', $cienciaId)) . '@islagaia.pt');
            $primaryEntity = !empty($affiliation) ? $affiliation : 'ISLA - Instituto Politécnico de Gestão e Tecnologia';

            $userData = [
                'name' => $name ?: ('Investigador ' . $cienciaId),
                'email' => $generatedEmail,
                'ciencia_vitae' => $cienciaId,
                'password' => Hash::make(Str::random(32)), // Secure random hash for SSO accounts
                'set_password_token' => Str::random(24),
                'type' => 'researcher',
                'is_active' => 1,
                'is_admin' => 0,
                'is_isla' => 1,
                'email_verified_at' => now(),
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'entidade')) {
                $userData['entidade'] = $primaryEntity;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'entities')) {
                $userData['entities'] = [$primaryEntity];
            }

            $user = User::create($userData);

            // Create linked Author record
            $user->authorInformation()->create([
                'profile_is_public' => 1,
                'profile_image_is_public' => 1,
            ]);
        }

        // Ensure user is active and has verified email status
        if (!$user->is_active) {
            $user->is_active = 1;
            $user->save();
        }

        // Ensure authorInformation exists
        if (!$user->authorInformation && $user->type !== 'administrative') {
            $user->authorInformation()->create([
                'profile_is_public' => 1,
                'profile_image_is_public' => 1,
            ]);
        }

        // Log the user in and activate the session
        Auth::login($user, remember: true);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        // Automatic metrics sync for the author on login
        if ($user && $user->authorInformation) {
            try {
                app(\App\Services\MetricScraperService::class)->syncAuthorMetrics($user->authorInformation);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Automatic metrics sync on CV login failed: ' . $e->getMessage());
            }
        }

        // Redirect back to intended page
        $redirectTo = $request->input('redirect_to') ?: ($request->hasSession() ? session()->pull('ciencia_vitae_redirect_to') : null);
        if ($redirectTo && !str_contains($redirectTo, '/login') && !str_contains($redirectTo, '/register')) {
            return redirect()->to($redirectTo)->with('status', __("Sessão iniciada com sucesso através do Ciência Vitae (:name).", ['name' => $user->name]));
        }

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('status', __("Sessão iniciada com sucesso através do Ciência Vitae (:name).", ['name' => $user->name]));
    }

    /**
     * Unlink Ciência Vitae ID from the authenticated user
     */
    public function unlink(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $user->ciencia_vitae = null;
        $user->save();

        return redirect()->route('profile.edit')->with('status', __('O seu Ciência ID foi desassociado da sua conta.'));
    }
}
