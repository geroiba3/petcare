<?php

namespace App\Http\Controllers;

use App\Models\FirebaseUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $db = Firebase::database();

        // Buscar si ya existe un usuario con ese email
                // Buscar si ya existe un usuario con ese email
        \Log::info('Buscando usuario con email: ' . $googleUser->getEmail());

        $resultados = $db->getReference('usuarios')
            ->orderByChild('email')
            ->equalTo($googleUser->getEmail())
            ->getValue();

        \Log::info('Resultado de la búsqueda: ' . json_encode($resultados));

        if ($resultados) {
            $id = array_key_first($resultados);
            $user = new FirebaseUser($id, $resultados[$id]);
        } else {
            // Crear usuario nuevo con un ID incremental simple (siguiendo el patrón de IDs cortos ya usado)
            $ref = $db->getReference('usuarios')->push([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'user',
                'activo' => true,
                'created_at' => now()->toDateTimeString(),
            ]);
            $id = $ref->getKey();
            $user = new FirebaseUser($id, $db->getReference('usuarios/' . $id)->getValue());
        }

        if (!$user->activo) {
            return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contactá al administrador.');
        }
      \Log::info('Google login - user id: ' . $user->getAuthIdentifier() . ' email: ' . $user->email . ' activo: ' . var_export($user->activo, true));

        Auth::login($user);

        \Log::info('Después de Auth::login - check: ' . var_export(Auth::check(), true) . ' id: ' . var_export(Auth::id(), true));

        return redirect('/dashboard');
    }
}