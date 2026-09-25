<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(Authenticatable $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        $db = Firebase::database();
        $id = $user->getAuthIdentifier();

        // Chequeo manual de email duplicado (antes lo hacía Rule::unique('users')->ignore($user->id))
        $todos = $db->getReference('usuarios')->getValue() ?? [];
        foreach ($todos as $otroId => $otroUsuario) {
            if ((string) $otroId !== (string) $id && is_array($otroUsuario) && ($otroUsuario['email'] ?? null) === $input['email']) {
                throw ValidationException::withMessages([
                    'email' => ['Ese email ya está en uso.'],
                ])->errorBag('updateProfileInformation');
            }
        }

        $db->getReference('usuarios/' . $id)->update([
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }
}