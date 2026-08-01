<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UsuarioAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->get();

        return view('admin.usuarios.index', ['usuarios' => $usuarios]);
    }
   
     public function veterinarios()
    {
        $usuarios = User::where('role', 'vet')->orderBy('created_at', 'desc')->get();
        $db = Firebase::database();
        $veterinarias = $db->getReference('veterinarias')->getValue() ?? []; 


        return view('admin.veterinarios.index', ['usuarios' => $usuarios, 'veterinarias' => $veterinarias]);
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

      public function edit(string $id)
    {
        $usuario = User::findOrFail($id);

        return view('admin.usuarios.edit', ['usuario' => $usuario]);
    }

    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
        ]);

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        $usuario->save();

        // Sincronizar con Firebase
        $db = Firebase::database();
        $db->getReference('usuarios/' . $usuario->id)->update([
            'name' => $usuario->name,
            'email' => $usuario->email,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario "' . $usuario->name . '" actualizado.');
    }
   
     public function cambiarRol(Request $request, $id)
    {
        $data = $request->validate([
            'role' => 'required|in:user,vet,admin',
        ]);

        // El admin no puede cambiarse el rol a sí mismo
        if ((int) $id === Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No podés cambiar tu propio rol.');
        }

        $usuario = User::findOrFail($id);
        $usuario->role = $data['role'];
        $usuario->save();

        // Sincronizar con Firebase
        $db = Firebase::database();
        $db->getReference('usuarios/' . $usuario->id)->update([
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Rol de ' . $usuario->name . ' actualizado a "' . $data['role'] . '".');
    }

    public function asignarVeterinaria(Request $request, $id)
    {
        $data = $request->validate([
            'veterinaria_id' => 'nullable|string',
        ]);
                $usuario = User::findOrFail($id);
        if ($usuario->role !== 'vet') {
            return redirect()->route('admin.veterinarios.index')
                ->with('error', 'Solo se puede asignar una veterinaria a un usuario con rol "vet".');
        }
        $usuario->veterinaria_id = $data['veterinaria_id'] ?: null;
        $usuario->save();

        $db = Firebase::database();
        $db->getReference('usuarios/' . $usuario->id)->update([
            'veterinaria_id' => $usuario->veterinaria_id,
        ]); 

        return redirect()->route('admin.veterinarios.index')
            ->with('success', 'Veterinaria asignada a ' . $usuario->name . '.');
    }   


    /**
     * Remove the specified resource from storage.
     */
   public function toggleActivo($id)
    {
        // El admin no puede desactivarse a sí mismo
        if ((int) $id === Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No podés desactivar tu propia cuenta.');
        }

        $usuario = User::findOrFail($id);
        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $db = Firebase::database();

        // Sincronizar estado del usuario en Firebase
        $db->getReference('usuarios/' . $usuario->id)->update([
            'activo' => $usuario->activo,
        ]);

        // Cascada: aplicar el mismo estado a todas sus mascotas
        $mascotas = $db->getReference('mascotas/' . $usuario->id)->getValue() ?? [];
        foreach ($mascotas as $mascotaId => $mascota) {
            $db->getReference('mascotas/' . $usuario->id . '/' . $mascotaId)->update([
                'activo' => $usuario->activo,
            ]);
        }

        $estado = $usuario->activo ? 'activado' : 'desactivado';
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario "' . $usuario->name . '" ' . $estado . ' junto con sus mascotas.');
    }
}

