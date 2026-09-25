<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\Authenticatable;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(Authenticatable $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');

       $db = Firebase::database();
$db->getReference('usuarios/' . $user->getAuthIdentifier())->update([
    'password' => Hash::make($input['password']),
]);
    }
}
