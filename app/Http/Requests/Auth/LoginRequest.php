<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }


    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $columnToCheck = $this->columnToCheck();

        $user = User::where($columnToCheck, $this->username)->first();
        if ($user && Hash::check($this->password, $user->password)) {
            if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                throw ValidationException::withMessages([
                    'email' => __('Confirme o email para ativar a conta.'),
                ]);
            }

            if (!$user->is_active) {
                throw ValidationException::withMessages([
                    'email' => __('A sua conta ainda esta pendente de aprovacao.'),
                ]);
            }
        }

        if(! Auth::attempt([$columnToCheck => $this->username, 'password' => $this->password, "is_active" => 1], $this->boolean('remember'))) 
        {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) 
        {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }


    private function columnToCheck()
    {
        if (preg_match('/^.+@.+$/i', $this->username)) 
        {
            $column = "email";
        }
        else
        {
            $column = "ciencia_vitae";
        }

        return $column;
    }

    
}
