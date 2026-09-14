<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;


class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        if (!($user->activo ?? true)) {
    return response()->json(['message' => 'Usuario inactivo'], 403);
}

        // Generate a Firebase custom token
      $auth = Firebase::auth();
        $customToken = $auth->createCustomToken($user->id);

        return response()->json(['token' => $customToken->toString()]);
    }
}