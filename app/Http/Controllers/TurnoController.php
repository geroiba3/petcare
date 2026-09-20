<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

    public function create(Request $request)
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $mascotas = $db->getReference('mascotas/' . $userId)->getValue();
        $veterinarias = $db->getReference('veterinarias')->getValue();

        $veterinariaSeleccionada = $request->query('veterinaria_id');
        $vetSeleccionado = $request->query('vet_id');
        $fechaSeleccionada = $request->query('fecha');

        // Antes: User::where('role', 'vet')->where('veterinaria_id', ...)->get()
        $vets = collect();
        if ($veterinariaSeleccionada) {
            $todosUsuarios = $db->getReference('usuarios')->getValue() ?? [];
            foreach ($todosUsuarios as $uid => $u) {
                $mismoRol = ($u['role'] ?? null) === 'vet';
                $mismaVeterinaria = (string) ($u['veterinaria_id'] ?? '') === (string) $veterinariaSeleccionada;
                if ($mismoRol && $mismaVeterinaria) {
                    $u['id'] = $uid;
                    $vets->push((object) $u);
                }
            }
        }

        // Horarios disponibles del vet elegido en la fecha elegida
        $horariosDisponibles = [];
        if ($vetSeleccionado && $fechaSeleccionada) {
            $horario = $db->getReference('usuarios/' . $vetSeleccionado . '/horario')->getValue();
            if ($horario) {
                $horariosDisponibles = $this->calcularHorariosDisponibles($horario, $fechaSeleccionada, $vetSeleccionado);
            }
        }

        return view('turnos.create', [
            'mascotas' => $mascotas ?? [],
            'veterinarias' => $veterinarias ?? [],
            'vets' => $vets,
            'veterinariaSeleccionada' => $veterinariaSeleccionada,
            'vetSeleccionado' => $vetSeleccionado,
            'fechaSeleccionada' => $fechaSeleccionada,
            'horariosDisponibles' => $horariosDisponibles,
        ]);
    }

    private function horaKey(string $hora): string
    {
        return str_replace(':', '-', $hora);
    }

    public function calcularHorariosDisponibles(array $horario, string $fecha, string $vetId): array
    {
        $diasSemana = [
            'monday' => 'lunes',
            'tuesday' => 'martes',
            'wednesday' => 'miercoles',
            'thursday' => 'jueves',
            'friday' => 'viernes',
            'saturday' => 'sabado',
            'sunday' => 'domingo',
        ];
        $diaSemana = $diasSemana[strtolower(Carbon::parse($fecha)->englishDayOfWeek)];

        if (!in_array($diaSemana, $horario['dias'] ?? [])) {
            return [];
        }

        $inicio = Carbon::parse($fecha . ' ' . $horario['hora_inicio']);
        $fin = Carbon::parse($fecha . ' ' . $horario['hora_fin']);
        $duracion = (int) $horario['duracion_min'];
        $slots = [];

        while ($inicio->lt($fin)) {
            $slots[] = $inicio->format('H:i');
            $inicio->addMinutes($duracion);
        }

        $db = Firebase::database();
        $ocupados = $db->getReference('agenda/' . $vetId . '/' . $fecha)->getValue() ?? [];
        $ocupadosKeys = array_keys($ocupados);

        return array_values(array_filter($slots, function ($slot) use ($ocupadosKeys) {
            return !in_array($this->horaKey($slot), $ocupadosKeys);
        }));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => 'required|string',
            'mascota_nombre' => 'required|string',
            'veterinaria_id' => 'required|string',
            'veterinaria_nombre' => 'required|string',
            'vet_id' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'motivo' => 'required|string|max:255',
        ]);

        $db = Firebase::database();
        $userId = auth()->id();

        $ocupado = $db->getReference('agenda/' . $data['vet_id'] . '/' . $data['fecha'] . '/' . $this->horaKey($data['hora']))->getValue();
        if ($ocupado) {
            return back()->withInput()->with('error', 'Ese horario ya fue reservado por otra persona. Elegí otro.');
        }

        $turno = [
            'mascota_id' => $data['mascota_id'],
            'mascota_nombre' => $data['mascota_nombre'],
            'veterinaria_id' => $data['veterinaria_id'],
            'veterinaria_nombre' => $data['veterinaria_nombre'],
            'vet_id' => $data['vet_id'],
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'motivo' => $data['motivo'],
            'estado' => 'pendiente',
            'usuario_id' => $userId,
            'usuario_nombre' => auth()->user()->name,
            'created_at' => now()->toDateTimeString(),
        ];

        $ref = $db->getReference('turnos/' . $userId)->push($turno);
        $turnoId = $ref->getKey();

        $turno['turno_id_local'] = $turnoId;
        $db->getReference('turnos_pendientes/' . $turnoId)->set($turno);
        $db->getReference('agenda/' . $data['vet_id'] . '/' . $data['fecha'] . '/' . $this->horaKey($data['hora']))->set($turnoId);

        return redirect('/turnos')->with('success', '¡Turno reservado!');
    }

    public function destroy($id)
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $turno = $db->getReference('turnos/' . $userId . '/' . $id)->getValue();

        if ($turno && !empty($turno['vet_id'])) {
            $db->getReference('agenda/' . $turno['vet_id'] . '/' . $turno['fecha'] . '/' . $this->horaKey($turno['hora']))->remove();
        }

        $db->getReference('turnos_pendientes/' . $id)->remove();
        $db->getReference('turnos/' . $userId . '/' . $id)->remove();

        return redirect('/turnos')->with('success', 'Turno cancelado.');
    }
}