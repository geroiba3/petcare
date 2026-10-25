<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $mascotaId)
    {
        $db = Firebase::database();
        $userId = auth()->id();

        $mascota = $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->getValue();
        $historial = $db->getReference('historial/' . $userId . '/' . $mascotaId)->getValue() ?? [];

        if ($historial) {
            uasort($historial, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));
        }
        $tipo = $request->query('tipo','todos');

        if ($tipo !== 'todos') {
            $historial = array_filter($historial, fn($c) => ($c['tipo'] ?? '') === $tipo);
        }
        return view('historial.index', [
            'mascota' => $mascota,
            'mascotaId' => $mascotaId,
            'historial' => $historial,
            'tipo' => $tipo
        ]);
    }
 public function create($mascotaId){
    $db = Firebase::database();
     $userId = auth()->id();
        $mascota = $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->getValue();

        if (!$mascota) {
            return redirect('/mascotas')->with('error', 'Mascota no encontrada.');
        }

        return view('historial.create', ['mascota' => $mascota, 'mascotaId' => $mascotaId]);
    }
  public function store(Request $request, $mascotaId)
    {
        $data = $request->validate([
            'tipo' => 'required|in:vacuna,enfermedad,tratamiento,desparasitacion,consulta',
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date|before_or_equal:today',
            'proximo_vencimiento' => 'nullable|date',
            'descripcion' => 'nullable|string|max:500',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $db = Firebase::database();
        $userId = auth()->id();

        $registro = [
            'tipo' => $data['tipo'],
            'nombre' => $data['nombre'],
            'fecha' => $data['fecha'],
            'proximo_vencimiento' => $data['proximo_vencimiento'] ?? null,
            'descripcion' => $data['descripcion'] ?? '',
            'creado_por' => 'dueno',
            'created_at' => now()->toDateTimeString(),
        ];

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $registro['archivo'] = 'data:' . $archivo->getMimeType() . ';base64,' . base64_encode(file_get_contents($archivo->getRealPath()));
            $registro['archivo_nombre'] = $archivo->getClientOriginalName();
        }

        $db->getReference('historial/' . $userId . '/' . $mascotaId)->push($registro);

        return redirect()->route('historial.index', $mascotaId)->with('success', '¡Registro agregado!');
    }
        public function show($mascotaId, $registroId)
    {
        $db = Firebase::database();
        $userId = auth()->id();

        $mascota = $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->getValue();
        $registro = $db->getReference('historial/' . $userId . '/' . $mascotaId . '/' . $registroId)->getValue();

        if (!$mascota || !$registro) {
            return redirect()->route('historial.index', $mascotaId)->with('error', 'Registro no encontrado.');
        }

        return view('historial.show', [
            'mascota' => $mascota,
            'registro' => $registro,
            'volverUrl' => route('historial.index', $mascotaId),
        ]);
    }
}
