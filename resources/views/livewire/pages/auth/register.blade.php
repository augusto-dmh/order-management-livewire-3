<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;
    public int $passwordStrength = 0;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }

    /**
     * Generate a strong password.
     */
    public function generatePassword(): void
    {
        // Generate a random 12-character password with letters, numbers and symbols
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';

        for ($i = 0; $i < 12; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        $this->password = $password;
        $this->password_confirmation = $password;
        $this->calculatePasswordStrength();
    }

    /**
     * Toggle password visibility.
     */
    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    /**
     * Calculate password strength on input changes.
     */
    public function updatedPassword(): void
    {
        $this->calculatePasswordStrength();
    }

    /**
     * Calculate the password strength score.
     */
    private function calculatePasswordStrength(): void
    {
        $strength = 0;
        $password = $this->password;

        if (strlen($password) >= 8) $strength += 25;
        if (preg_match('/[A-Z]/', $password)) $strength += 25;
        if (preg_match('/[0-9]/', $password)) $strength += 25;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $strength += 25;

        $this->passwordStrength = $strength;
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <div class="flex flex-col gap-1">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input wire:model="name" id="name" class="block w-full" type="text" name="name" autofocus autocomplete="name" />
            </div>

            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <div class="flex flex-col gap-1">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block w-full" type="email" name="email" autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <!-- Input & Generation -->
            <div class="flex flex-col gap-1">
                <x-input-label for="password" :value="__('Password')" />

                <div class="flex justify-center items-center gap-1">
                    <div class="relative flex-1">
                        <x-text-input wire:model.live="password" id="password" class="block w-full pr-10"
                                        :type="$showPassword ? 'text' : 'password'"
                                        name="password"
                                        autocomplete="new-password" />

                        <button type="button" wire:click="togglePasswordVisibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($showPassword)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7A9.97 9.97 0 014.02 8.971m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                @endif
                            </svg>
                        </button>
                    </div>

                    <div class="flex-2">
                        <button type="button" wire:click="generatePassword" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Generate') }}
                        </button>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Strength Bar -->
            @if(strlen($password) > 0)
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full bg-cyan-500" style="width: {{ $passwordStrength }}%"></div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">
                        @if($passwordStrength < 25)
                            {{ __('Weak password') }}
                        @elseif($passwordStrength < 75)
                            {{ __('Medium password') }}
                        @else
                            {{ __('Strong password') }}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 flex flex-col gap-1">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full"
                                :type="$showPassword ? 'text' : 'password'"
                                name="password_confirmation" autocomplete="new-password" />
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
