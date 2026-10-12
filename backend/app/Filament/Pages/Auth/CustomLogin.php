<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    use WithRateLimiting;

    protected static string $view = 'filament.pages.auth.custom-login';

    public static string $layout = 'filament.pages.auth.login-layout';

    public ?array $data = [
        'email' => 'admin@shopdress.id',
        'password' => '',
        'remember' => true,
    ];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill([
            'email' => 'admin@shopdress.id',
            'remember' => true,
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $this->validate([
            'data.email' => ['required', 'email'],
            'data.password' => ['required'],
        ]);

        $credentials = [
            'email' => $this->data['email'] ?? '',
            'password' => $this->data['password'] ?? '',
        ];

        $remember = (bool) ($this->data['remember'] ?? false);

        if (! Filament::auth()->attempt($credentials, $remember)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
