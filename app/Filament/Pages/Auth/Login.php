<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;

class Login extends \Filament\Auth\Pages\Login
{
    public function form(Schema $schema): Schema
    {
        return parent::form($schema)->components([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
            Hidden::make('turnstile_token'),
            View::make('filament.turnstile'),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        if (! $this->verifyTurnstile($this->data['turnstile_token'] ?? '')) {
            Notification::make()
                ->title('Security check failed. Please try again.')
                ->danger()
                ->send();

            return null;
        }

        return parent::authenticate();
    }

    protected function verifyTurnstile(string $token): bool
    {
        if (blank(config('services.turnstile.secret_key'))) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret_key'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        return $response->successful() && $response->json('success') === true;
    }
}
