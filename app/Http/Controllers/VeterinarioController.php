<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class VeterinarioController extends Controller
{
    private array $diasSemana = [
        'monday' => 'lunes',
        'tuesday' => 'martes',
        'wednesday' => 'miercoles',
        'thursday' => 'jueves',
        'friday' => 'viernes',
        'saturday' => 'sabado',
        'sunday' => 'domingo',
    ];

    public function index(Request $request)
    {
        if (!auth()->user()->isVet()) {
            return redirect('/dashboard')->with('error', 'No tenés permisos para acceder a esta sección.');
        }

        $db = Firebase::database();
        $vetId = auth()->id();

        // Turnos pendientes del vet logueado
        $turnos = $db->getReference('turnos_pendientes')->getValue() ?? [];
        $turnos = array_filter($turnos, fn($t) =>
            ($t['estado'] ?? '') === 'pendiente' && (string) ($t['vet_id'] ?? '') === (string) $vetId
        );
        uasort($turnos, fn($a, $b) => strcmp($a['fecha'], $b['fecha']));

        // Horario configurado por el vet (puede no existir)
        $horario = $db->getReference('usuarios/' . $vetId . '/horario')->getValue();

        // Semana a mostrar (navegable con ?semana=-1 / ?semana=1)
        $offset = (int) $request->query('semana', 0);
        $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($offset);

        // Días de la grilla: si hay horario, solo los días que el vet atiende;
        // si no, lunes a sábado por defecto.
        $diasGrid = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = $inicioSemana->copy()->addDays($i);
            $slug = $this->diasSemana[strtolower($fecha->englishDayOfWeek)];

            if ($horario) {
                if (!in_array($slug, $horario['dias'] ?? [])) {
                    continue;
                }
            } elseif ($slug === 'domingo') {
                continue;
            }

            $diasGrid[] = [
                'fecha' => $fecha->toDateString(),
                'label' => ucfirst($fecha->locale('es')->isoFormat('ddd')),
                'dia' => $fecha->format('d'),
            ];
        }

        // Franjas horarias, según duración configurada
        $slots = [];
        if ($horario && !empty($horario['hora_inicio']) && !empty($horario['hora_fin']) && !empty($horario['duracion_min'])) {
            $cursor = Carbon::parse($horario['hora_inicio']);
            $fin = Carbon::parse($horario['hora_fin']);
            $duracion = (int) $horario['duracion_min'];
            while ($cursor->lt($fin)) {
                $slots[] = $cursor->format('H:i');
                $cursor->addMinutes($duracion);
            }
        }

        // Turnos indexados por fecha + hora, para ubicarlos en la celda correcta
        $turnosPorFechaHora = [];
        foreach ($turnos as $turnoId => $t) {
            $turnosPorFechaHora[$t['fecha']][$t['hora']] = array_merge($t, ['turno_id' => $turnoId]);
        }

        return view('veterinario.index', [
            'turnos' => $turnos,
            'horario' => $horario,
            'diasGrid' => $diasGrid,
            'slots' => $slots,
            'turnosPorFechaHora' => $turnosPorFechaHora,
            'inicioSemana' => $inicioSemana,
            'offset' => $offset,
        ]);
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

    if ((string) ($turno['vet_id'] ?? '') !== (string) auth()->id()) {
        abort(403, 'Este turno no te pertenece.');
    }

    $mascotaFoto = $db->getReference('mascotas/' . $turno['usuario_id'] . '/' . $turno['mascota_id'] . '/foto')->getValue();

    return view('veterinario.completar', [
        'turno' => $turno,
        'turnoId' => $turnoId,
        'mascotaFoto' => $mascotaFoto,
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

        if (!$turno) {
            return redirect('/veterinario')->with('error', 'Turno no encontrado.');
        }

        if ((string) ($turno['vet_id'] ?? '') !== (string) auth()->id()) {
            abort(403, 'Este turno no te pertenece.');
        }

        $userId = $turno['usuario_id'];
        $mascotaId = $turno['mascota_id'];

        $turnoDueno = $db->getReference('turnos/' . $userId . '/' . $turnoId)->getValue();

        if (!$turnoDueno) {
            $db->getReference('turnos_pendientes/' . $turnoId)->remove();
            return redirect('/veterinario')->with('error', 'Este turno fue cancelado por el dueño y ya no está disponible.');
        }

        $db->getReference('historial/' . $userId . '/' . $mascotaId)->push([
            'tipo' => $data['tipo'],
            'nombre' => $turno['motivo'],
            'fecha' => now()->toDateString(),
            'proximo_vencimiento' => $data['proximo_vencimiento'] ?? null,
            'descripcion' => $data['diagnostico'],
            'created_at' => now()->toDateTimeString(),
        ]);

        $db->getReference('turnos_pendientes/' . $turnoId)->update(['estado' => 'completado']);
        $db->getReference('turnos/' . $userId . '/' . $turnoId)->update(['estado' => 'completado']);

        return redirect('/veterinario')->with('success', 'Consulta guardada en el historial.');
    }
}