<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // Crear usuario en SQLite
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'user',
        ]);

        // Guardar en Firebase Realtime Database
        $db = Firebase::database();
        $db->getReference('usuarios/' . $user->id)->set([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
            'created_at' => now()->toDateTimeString(),
        ]);

        return $user;
    }
}