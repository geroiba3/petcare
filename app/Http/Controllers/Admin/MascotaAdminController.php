<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MascotaAdminController extends Controller
{
    public function index(Request $request)
    {
        $db = Firebase::database();
        $todas = $db->getReference('mascotas')->getValue() ?? [];

        // Antes: User::whereIn('id', array_keys($todas))->pluck('name', 'id')
        $todosUsuarios = $db->getReference('usuarios')->getValue() ?? [];
        $duenios = [];
        foreach (array_keys($todas) as $userId) {
            $duenios[$userId] = $todosUsuarios[$userId]['name'] ?? 'Usuario eliminado';
        }

        $filtro = $request->query('estado', 'todas'); // todas | activas | inactivas

        $mascotas = [];
        foreach ($todas as $userId => $mascotasUsuario) {
            foreach ($mascotasUsuario as $mascotaId => $mascota) {
                $activa = $mascota['activo'] ?? true;

                if ($filtro === 'activas' && !$activa) {
                    continue;
                }
                if ($filtro === 'inactivas' && $activa) {
                    continue;
                }

                $mascota['id'] = $mascotaId;
                $mascota['user_id'] = $userId;
                $mascota['duenio'] = $duenios[$userId] ?? 'Usuario eliminado';
                $mascotas[] = $mascota;
            }
        }

        return view('admin.mascotas.index', [
            'mascotas' => $mascotas,
            'filtro' => $filtro,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($userId, $mascotaId)
    {
        //
    }

    public function edit($userId, $mascotaId)
    {
        $db = Firebase::database();
        $mascota = $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->getValue();

        if (!$mascota) {
            return redirect()->route('admin.mascotas.index')
                ->with('error', 'Mascota no encontrada.');
        }

        return view('admin.mascotas.edit', [
            'mascota' => $mascota,
            'userId' => $userId,
            'mascotaId' => $mascotaId
        ]);
    }

    public function update(Request $request, $userId, $mascotaId)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'sexo' => 'nullable|string',
            'raza' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
        ]);

        $db = Firebase::database();
        $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->update([
            'nombre' => $data['nombre'],
            'especie' => $data['especie'],
            'sexo' => $data['sexo'] ?? '',
            'raza' => $data['raza'] ?? '',
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? '',
        ]);

        return redirect()->route('admin.mascotas.index')
            ->with('success', 'Mascota "' . $data['nombre'] . '" actualizada.');
    }

    public function toggleActivo($userId, $mascotaId)
    {
        $db = Firebase::database();
        $ref = $db->getReference('mascotas/' . $userId . '/' . $mascotaId);
        $mascota = $ref->getValue();

        if (!$mascota) {
            return redirect()->route('admin.mascotas.index')
                ->with('error', 'Mascota no encontrada.');
        }

        $nuevoEstado = !($mascota['activo'] ?? true);
        $ref->update(['activo' => $nuevoEstado]);

        $estado = $nuevoEstado ? 'activada' : 'desactivada';
        return redirect()->route('admin.mascotas.index')
            ->with('success', 'Mascota "' . $mascota['nombre'] . '" ' . $estado . '.');
    }
}