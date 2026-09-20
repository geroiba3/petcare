<?php

namespace App\Auth;

use App\Models\FirebaseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        $db = Firebase::database();
        $data = $db->getReference('usuarios/' . $identifier)->getValue();

        if (!$data) {
            return null;
        }

        return new FirebaseUser($identifier, $data);

        \Log::info('retrieveById llamado con: ' . $identifier . ' (tipo: ' . gettype($identifier) . ')');

        $db = Firebase::database();
        $data = $db->getReference('usuarios/' . $identifier)->getValue();

        if (!$data) {
            \Log::info('retrieveById: NO se encontró usuario con ese id');
            return null;
        }
        return new FirebaseUser($identifier, $data);
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $user = $this->retrieveById($identifier);

        if (!$user || !$user->getRememberToken()) {
            return null;
        }

        return hash_equals($user->getRememberToken(), $token) ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $db = Firebase::database();
        $db->getReference('usuarios/' . $user->getAuthIdentifier())->update([
            'remember_token' => $token,
        ]);
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials['email'])) {
            return null;
        }

        $db = Firebase::database();
        $resultados = $db->getReference('usuarios')
            ->orderByChild('email')
            ->equalTo($credentials['email'])
            ->getValue();

        if (!$resultados) {
            return null;
        }

        // orderByChild devuelve un array con la clave del usuario adentro
        $id = array_key_first($resultados);
        $data = $resultados[$id];

        return new FirebaseUser($id, $data);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
    }
}