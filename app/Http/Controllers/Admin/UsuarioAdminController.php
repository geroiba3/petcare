<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UsuarioAdminController extends Controller
{
    /**
     * Trae un usuario de Firebase con su id incluido, o null si no existe.
     */
    private function buscarUsuario($db, string $id): ?array
    {
        $usuario = $db->getReference('usuarios/' . $id)->getValue();
        if (!$usuario) {
            return null;
        }
        $usuario['id'] = $id;
        return $usuario;
    }

    /**
     * Trae todos los usuarios de Firebase, opcionalmente filtrados por rol,
     * como objetos (para seguir usando $usuario->campo en las vistas, igual que con Eloquent).
     */
private function todosLosUsuarios($db, ?string $rol = null): \Illuminate\Support\Collection
{
    $todos = $db->getReference('usuarios')->getValue() ?? [];

    $usuarios = [];
    foreach ($todos as $id => $usuario) {
        if (!is_array($usuario)) {
            continue;
        }
        if ($rol !== null && ($usuario['role'] ?? null) !== $rol) {
            continue;
        }
        $usuario['id'] = $id;
        $usuario['name'] = $usuario['name'] ?? '(sin nombre)';
        $usuario['email'] = $usuario['email'] ?? '';
        $usuario['role'] = $usuario['role'] ?? 'user';
        $usuario['activo'] = $usuario['activo'] ?? true;
        $usuarios[] = $usuario;
    }

    usort($usuarios, function ($a, $b) {
        return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
    });

    return collect($usuarios)->map(function ($usuario) {
        $usuario['created_at'] = Carbon::parse($usuario['created_at'] ?? now());
        return (object) $usuario;
    });
}

    public function index()
    {
        $db = Firebase::database();
        $usuarios = $this->todosLosUsuarios($db);

        return view('admin.usuarios.index', ['usuarios' => $usuarios]);
    }

    public function veterinarios()
    {
        $db = Firebase::database();
        $usuarios = $this->todosLosUsuarios($db, 'vet');
        $veterinarias = $db->getReference('veterinarias')->getValue() ?? [];

        return view('admin.veterinarios.index', ['usuarios' => $usuarios, 'veterinarias' => $veterinarias]);
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

    public function edit(string $id)
    {
        $db = Firebase::database();
        $usuario = $this->buscarUsuario($db, $id);

        abort_if(!$usuario, 404);

        $usuario['created_at'] = Carbon::parse($usuario['created_at'] ?? now());

        return view('admin.usuarios.edit', ['usuario' => (object) $usuario]);
    }

    public function update(Request $request, string $id)
    {
        $db = Firebase::database();
        $usuario = $this->buscarUsuario($db, $id);

        abort_if(!$usuario, 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Antes esto lo hacía 'unique:users,email,...' de Eloquent — ahora lo chequeamos a mano
        $todos = $db->getReference('usuarios')->getValue() ?? [];
        foreach ($todos as $otroId => $otroUsuario) {
            if ((string) $otroId !== (string) $id && ($otroUsuario['email'] ?? null) === $data['email']) {
                return back()->withInput()->with('error', 'Ese email ya está en uso por otro usuario.');
            }
        }

        $db->getReference('usuarios/' . $id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario "' . $data['name'] . '" actualizado.');
    }

    public function cambiarRol(Request $request, string $id)
    {
        $data = $request->validate([
            'role' => 'required|in:user,vet,admin',
        ]);

        // El admin no puede cambiarse el rol a sí mismo (comparación en string: los ids de Firebase ya no son int)
        if ((string) $id === (string) Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No podés cambiar tu propio rol.');
        }

        $db = Firebase::database();
        $usuario = $this->buscarUsuario($db, $id);

        abort_if(!$usuario, 404);

        $db->getReference('usuarios/' . $id)->update([
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Rol de ' . $usuario['name'] . ' actualizado a "' . $data['role'] . '".');
    }

    public function asignarVeterinaria(Request $request, string $id)
    {
        $data = $request->validate([
            'veterinaria_id' => 'nullable|string',
        ]);

        $db = Firebase::database();
        $usuario = $this->buscarUsuario($db, $id);

        abort_if(!$usuario, 404);

        if (($usuario['role'] ?? null) !== 'vet') {
            return redirect()->route('admin.veterinarios.index')
                ->with('error', 'Solo se puede asignar una veterinaria a un usuario con rol "vet".');
        }

        $db->getReference('usuarios/' . $id)->update([
            'veterinaria_id' => $data['veterinaria_id'] ?: null,
        ]);

        return redirect()->route('admin.veterinarios.index')
            ->with('success', 'Veterinaria asignada a ' . $usuario['name'] . '.');
    }

    public function toggleActivo(string $id)
    {
        // El admin no puede desactivarse a sí mismo
        if ((string) $id === (string) Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No podés desactivar tu propia cuenta.');
        }

        $db = Firebase::database();
        $usuario = $this->buscarUsuario($db, $id);

        abort_if(!$usuario, 404);

        $nuevoEstado = !($usuario['activo'] ?? true);
        $db->getReference('usuarios/' . $id)->update(['activo' => $nuevoEstado]);

        // Cascada: aplicar el mismo estado a todas sus mascotas
        $mascotas = $db->getReference('mascotas/' . $id)->getValue() ?? [];
        foreach ($mascotas as $mascotaId => $mascota) {
            $db->getReference('mascotas/' . $id . '/' . $mascotaId)->update([
                'activo' => $nuevoEstado,
            ]);
        }

        $estado = $nuevoEstado ? 'activado' : 'desactivado';
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario "' . $usuario['name'] . '" ' . $estado . ' junto con sus mascotas.');
    }
}