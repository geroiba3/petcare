<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class TurnoController extends Controller
{
    public function index()
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $turnos = $db->getReference('turnos/' . $userId)->getValue();

        return view('turnos.index', ['turnos' => $turnos ?? []]);
    }

    public function create()
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $mascotas = $db->getReference('mascotas/' . $userId)->getValue();

        return view('turnos.create', ['mascotas' => $mascotas ?? []]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => 'required|string',
            'mascota_nombre' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
        'motivo' => 'required|string|max:255',
    ]);

    $db = Firebase::database();
    $userId = auth()->id();

    $turno = [
        'mascota_id' => $data['mascota_id'],
        'mascota_nombre' => $data['mascota_nombre'],
        'fecha' => $data['fecha'],
        'hora' => $data['hora'],
        'motivo' => $data['motivo'],
        'estado' => 'pendiente',
        'usuario_id' => $userId,
        'usuario_nombre' => auth()->user()->name,
        'created_at' => now()->toDateTimeString(),
    ];

    // Guardar en el nodo del dueño
    $ref = $db->getReference('turnos/' . $userId)->push($turno);
    $turnoId = $ref->getKey();

    // Guardar en el nodo global para el veterinario
    $turno['turno_id_local'] = $turnoId;
    $db->getReference('turnos_pendientes/' . $turnoId)->set($turno);

    return redirect('/turnos')->with('success', '¡Turno reservado!');
}
    public function destroy($id)
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $db->getReference('turnos/' . $userId . '/' . $id)->remove();

        return redirect('/turnos')->with('success', 'Turno cancelado.');
    }
}