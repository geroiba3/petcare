<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UsuarioController extends Controller
{
    private function authenticatedUser(): \Illuminate\Foundation\Auth\User
    {
        $user = Auth::user();

        if (!$user instanceof \Illuminate\Foundation\Auth\User) {
            abort(401);
        }

        return $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->authenticatedUser();
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
        $user = $this->authenticatedUser();
        $horario = null;
        if ($user->role === 'vet') {
            $db = Firebase::database();
            $horario =  $db->getReference('usuarios/' . $user->id . '/horario')->getValue();
        }

        return view('usuario.edit', ['user' => $user, 'horario' => $horario]);
    }

    public function update(Request $request)
    {
        $user = $this->authenticatedUser();

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

        $user = $this->authenticatedUser();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/perfil')->with('success', '¡Contraseña actualizada!');
    }

    public function guardarHorario(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user->isVet()) {
            abort(403);
        }

        $data = $request->validate([
            'dias' => 'required|array|min:1',
            'dias.*' => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'duracion_min' => 'required|integer|in:15,20,30,45,60',
        ]);

        $horario = [
            'dias' => $data['dias'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'duracion_min' => $data['duracion_min'],
        ];

        $db = Firebase::database();
        $db->getReference('usuarios/' . $user->id . '/horario')->set($horario);

        return redirect('/perfil/editar')->with('success', '¡Horario actualizado!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
