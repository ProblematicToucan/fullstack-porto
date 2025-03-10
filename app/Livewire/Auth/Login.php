<?php

namespace App\Livewire\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{
    public \App\Livewire\Forms\LoginForm $form;
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function authenticate(): ?LoginResponse
    {
        $this->validate();

        $this->form->authenticate();

        $user = Filament::auth()->user();


        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            throw ValidationException::withMessages([
                'form.password' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
