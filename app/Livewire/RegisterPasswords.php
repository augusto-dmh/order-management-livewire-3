<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use ZxcvbnPhp\Zxcvbn;

class RegisterPasswords extends Component
{
    public string $password = '';
    public string $passwordConfirmation = '';
    public int $strengthScore = 0;
    public array $strengthLevels = [
        1 => 'Weak',
        2 => 'Fair',
        3 => 'Good',
        4 => 'Strong',
    ];

    public function generatePassword(): void
    {
        $password = Str::password(12);

        $this->setPasswordFields($password);
    }

    protected function setPasswordFields($value): void
    {
        $this->password = $value;
        $this->passwordConfirmation = $value;
        $this->updatedPassword($value);
    }

    public function updatedPassword($value)
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }

    public function render()
    {
        return view('livewire.register-passwords');
    }
}
