<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $user = auth()->user();
    return view('usuario.index', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit()
    {
        $user = auth()->user();
        return view('usuario.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|max:2048',
        ]);

        // Actualizar en SQLite
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        // Actualizar en Firebase
        $db = Firebase::database();
        $actualizacion = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoBase64 = 'data:' . $foto->getMimeType() . ';base64,' . base64_encode(file_get_contents($foto->getRealPath()));
            $actualizacion['foto'] = $fotoBase64;
        }

        $db->getReference('usuarios/' . $user->id)->update($actualizacion);

        return redirect('/perfil')->with('success', '¡Perfil actualizado!');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/perfil')->with('success', '¡Contraseña actualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
