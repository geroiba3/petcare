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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userId, $mascotaId)
    {
        //
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
