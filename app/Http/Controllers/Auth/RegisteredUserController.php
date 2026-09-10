<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Notifications\RegistrationPendingApprovalNotification;
use App\Models\Entity;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $entitiesList = Entity::orderByRaw("case when name = 'OTHER' then 1 else 0 end")
            ->orderBy('name')
            ->get();

        return view('auth.register', compact('entitiesList'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
         \Log::info('Registration attempt:', $request->all()); // Log dos dados recebidos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'ciencia_vitae' => ['nullable', 'string', 'regex:/^([A-Z0-9]{4}-){2}[A-Z0-9]{4}$/i', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'entidade' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'entities' => ['required', 'array'],
            'entities.*' => ['string'],
            'custom_entity' => ['nullable', 'string', 'max:255'],
        ]);

        $type = $request->input('type') ?: ($request->boolean('researcher') ? 'researcher' : 'user');

        $entities = $request->input('entities', []);
        $customEntity = strtoupper(trim((string) $request->input('custom_entity')));

        if (in_array('OTHER', $entities, true) && $customEntity === '') {
            throw ValidationException::withMessages([
                'custom_entity' => __('Indique a outra entidade.'),
            ]);
        }

        $entities = array_values(array_unique(array_filter(array_map('trim', $entities))));
        $entities = array_values(array_filter($entities, fn ($entity) => $entity !== 'OTHER'));

        if ($customEntity !== '') {
            $exists = Entity::query()
                ->whereRaw('upper(name) = ?', [$customEntity])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'custom_entity' => __('Essa entidade já existe. Selecione-a na lista.'),
                ]);
            }
        }

        if ($customEntity !== '') {
            $entities[] = $customEntity;
        }

        $entities = array_values(array_unique($entities));
        $primaryEntity = $request->input('entidade') ?? ($entities[0] ?? null);

        if ($customEntity !== '') {
            Entity::create(['name' => $customEntity]);
        }
         
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ciencia_vitae' => $request->input('ciencia_vitae'),
            'password' => Hash::make($request->password),
            'entidade' => $primaryEntity,
            'type' => $type,
            'entities' => $entities,
            'is_active' => 0,
            'is_admin' => 0,
            'set_password_token' => Str::random(30),
        ]);

        \Log::info('User created:', ['id' => $user->id, 'email' => $user->email]);

        event(new Registered($user));

        $user->notify(new RegistrationPendingApprovalNotification());

        Auth::login($user);

        return redirect()->route('home')
            ->with('open_verify_email', true)
            ->with('status', __('Registo efetuado. Confirme o email e aguarde aprovacao do administrador.'));
    }
}
