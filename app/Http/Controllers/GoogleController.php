<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirige al usuario a Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google devuelve al usuario acá
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Buscar si ya existe un usuario con ese email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Crear usuario nuevo
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'user',
            ]);

            // Guardar en Firebase
            $db = Firebase::database();
            $db->getReference('usuarios/' . $user->id)->set([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'user',
                'created_at' => now()->toDateTimeString(),
            ]);
            
        }
            if (!$user->activo) {
            return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contactá al administrador.');
            }
  
        Auth::login($user);

        return redirect('/dashboard');
    }
}