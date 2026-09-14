<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MascotaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $db = Firebase::database();
        $todas = $db->getReference('mascotas')->getValue() ?? [];

        // Traemos los nombres de los dueños desde SQLite en una sola consulta
        $duenios = User::whereIn('id', array_keys($todas))->pluck('name', 'id');

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
    public function show($userId, $mascotaId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function toggleActivo($userId, $mascotaId)
    {
        $db = Firebase::database();
        $ref = $db->getReference('mascotas/' . $userId . '/' . $mascotaId);
        $mascota = $ref->getValue();

        if (!$mascota) {
            return redirect()->route('admin.mascotas.index')
                ->with('error', 'Mascota no encontrada.');
        }

        // Si nunca tuvo el campo, la tratamos como activa y la pasamos a inactiva
        $nuevoEstado = !($mascota['activo'] ?? true);
        $ref->update(['activo' => $nuevoEstado]);

        $estado = $nuevoEstado ? 'activada' : 'desactivada';
        return redirect()->route('admin.mascotas.index')
            ->with('success', 'Mascota "' . $mascota['nombre'] . '" ' . $estado . '.');
    }
}
