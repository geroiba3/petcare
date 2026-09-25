<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UsuarioController extends Controller
{
    private function authenticatedUser(): Authenticatable
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        return $user;
    }

    public function index()
    {
        $user = $this->authenticatedUser();
        return view('usuario.index', ['user' => $user]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit()
    {
        $user = $this->authenticatedUser();
        $horario = null;
        if ($user->role === 'vet') {
            $db = Firebase::database();
            $horario = $db->getReference('usuarios/' . $user->getAuthIdentifier() . '/horario')->getValue();
        }

        return view('usuario.edit', ['user' => $user, 'horario' => $horario]);
    }

    public function update(Request $request)
    {
        $user = $this->authenticatedUser();
        $db = Firebase::database();
        $id = $user->getAuthIdentifier();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Chequeo manual de email duplicado (antes: unique:users,email,...)
        $todos = $db->getReference('usuarios')->getValue() ?? [];
        foreach ($todos as $otroId => $otroUsuario) {
            if ((string) $otroId !== (string) $id && is_array($otroUsuario) && ($otroUsuario['email'] ?? null) === $data['email']) {
                return back()->withErrors(['email' => 'Ese email ya está en uso.']);
            }
        }

        $actualizacion = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoBase64 = 'data:' . $foto->getMimeType() . ';base64,' . base64_encode(file_get_contents($foto->getRealPath()));
            $actualizacion['foto'] = $fotoBase64;
        }

        $db->getReference('usuarios/' . $id)->update($actualizacion);

        return redirect('/perfil')->with('success', '¡Perfil actualizado!');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authenticatedUser();

        if (!Hash::check($request->password_actual, $user->getAuthPassword())) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $db = Firebase::database();
        $db->getReference('usuarios/' . $user->getAuthIdentifier())->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/perfil')->with('success', '¡Contraseña actualizada!');
    }

    public function guardarHorario(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!method_exists($user, 'isVet') || !$user->isVet()) {
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
        $db->getReference('usuarios/' . $user->getAuthIdentifier() . '/horario')->set($horario);

        return redirect('/perfil/editar')->with('success', '¡Horario actualizado!');
    }

    public function destroy(string $id)
    {
        //
    }
}