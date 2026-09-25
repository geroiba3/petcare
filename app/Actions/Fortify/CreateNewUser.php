<?php

namespace App\Actions\Fortify;

use App\Models\FirebaseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): Authenticatable
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        $db = Firebase::database();

        // Chequeo manual de email duplicado (antes lo hacía Rule::unique(User::class) contra SQLite)
        $todos = $db->getReference('usuarios')->getValue() ?? [];
        foreach ($todos as $otroUsuario) {
            if (is_array($otroUsuario) && ($otroUsuario['email'] ?? null) === $input['email']) {
                throw ValidationException::withMessages([
                    'email' => ['Ese email ya está en uso.'],
                ]);
            }
        }

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'user',
            'activo' => true,
            'created_at' => now()->toDateTimeString(),
        ];

        // Usuario nuevo: se crea con push-key de Firebase (misma decisión de diseño que GoogleController)
        $ref = $db->getReference('usuarios')->push($data);
        $id = $ref->getKey();

        return new FirebaseUser($id, $data);
    }
}