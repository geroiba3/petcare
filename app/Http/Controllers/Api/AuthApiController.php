<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FirebaseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $provider = Auth::createUserProvider('users');
        $user = $provider->retrieveByCredentials(['email' => $request->email]);

        if (!$user || !$provider->validateCredentials($user, ['password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!($user->activo ?? true)) {
            return response()->json(['message' => 'Usuario inactivo'], 403);
        }

        $auth = Firebase::auth();
        $customToken = $auth->createCustomToken($user->getAuthIdentifier());

        return response()->json(['token' => $customToken->toString()]);
    }
}