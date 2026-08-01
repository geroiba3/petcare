<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class VeterinarioController extends Controller
{
    public function index()
    {
       // Solo veterinarios pueden acceder
    if (!auth()->user()->isVet()) {
        return redirect('/dashboard')->with('error', 'No tenés permisos para acceder a esta sección.');
    }

     $db = Firebase::database();
    $vetId = auth()->id();
    $turnos = $db->getReference('turnos_pendientes')->getValue() ?? [];

    // Filtrar solo los pendientes y del vet logueado
    $turnos = array_filter($turnos, fn($t) =>
        ($t['estado'] ?? '') === 'pendiente' && ($t['vet_id'] ?? null) == $vetId
    );

    // Ordenar por fecha
    uasort($turnos, fn($a, $b) => strcmp($a['fecha'], $b['fecha']));

    return view('veterinario.index', ['turnos' => $turnos]);
    }

    public function completar($turnoId)
    {
        if (!auth()->user()->isVet()) {
        return redirect('/dashboard')->with('error', 'No tenés permisos para acceder a esta sección.');
}
        $db = Firebase::database();
        $turno = $db->getReference('turnos_pendientes/' . $turnoId)->getValue();

        if (!$turno) {
            return redirect('/veterinario')->with('error', 'Turno no encontrado.');
        }

        return view('veterinario.completar', [
            'turno' => $turno,
            'turnoId' => $turnoId,
        ]);
    }

    public function guardarConsulta(Request $request, $turnoId)
    {
        if (!auth()->user()->isVet()) {
        return redirect('/dashboard')->with('error', 'No tenés permisos para acceder a esta sección.');
}
        $data = $request->validate([
            'tipo' => 'required|string',
            'diagnostico' => 'required|string|max:500',
            'proximo_vencimiento' => 'nullable|date|after:today',
        ]);

        $db = Firebase::database();
        $turno = $db->getReference('turnos_pendientes/' . $turnoId)->getValue();
        $userId = $turno['usuario_id'];
        $mascotaId = $turno['mascota_id'];

        // Guardar en historial de la mascota
        $db->getReference('historial/' . $userId . '/' . $mascotaId)->push([
            'tipo' => $data['tipo'],
            'nombre' => $turno['motivo'],
            'fecha' => now()->toDateString(),
            'proximo_vencimiento' => $data['proximo_vencimiento'] ?? null,
            'descripcion' => $data['diagnostico'],
            'created_at' => now()->toDateTimeString(),
        ]);

        // Marcar turno como completado
        $db->getReference('turnos_pendientes/' . $turnoId)->update(['estado' => 'completado']);
        $db->getReference('turnos/' . $userId . '/' . $turno['turno_id_local'])->update(['estado' => 'completado']);

        return redirect('/veterinario')->with('success', 'Consulta guardada en el historial.');
    }
}