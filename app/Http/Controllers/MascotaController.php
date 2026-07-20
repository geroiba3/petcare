<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MascotaController extends Controller
{
    public function index()
{
    $db = Firebase::database();
    $userId = auth()->user()->id;
    $mascotas = $db->getReference('mascotas/' . $userId)->getValue() ?? [];

    foreach ($mascotas as $id => &$mascota) {
        if (!empty($mascota['fecha_nacimiento'])) {
            $nacimiento = \Carbon\Carbon::parse($mascota['fecha_nacimiento']);
            $hoy = \Carbon\Carbon::now();

            $años = (int) $nacimiento->diffInYears($hoy);
            $meses = (int) $nacimiento->copy()->addYears($años)->diffInMonths($hoy);

            $partes = [];
            if ($años > 0) {
                $partes[] = $años . ' ' . ($años === 1 ? 'año' : 'años');
            }
            if ($meses > 0) {
                $partes[] = $meses . ' ' . ($meses === 1 ? 'mes' : 'meses');
            }
            if (empty($partes)) {
                $partes[] = 'Recién nacido';
            }

            $mascota['edad'] = implode(' con ', $partes);
        } else {
            $mascota['edad'] = null;
        }

        // Filtrar mascotas inactivas
        if (!($mascota['activo'] ?? true)) {
            unset($mascotas[$id]);
            continue;
        }
    }
    return view('mascotas.index', ['mascotas' => $mascotas]);
}

    public function create()
    {
        return view('mascotas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'raza' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoBase64 = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoBase64 = 'data:' . $foto->getMimeType() . ';base64,' . base64_encode(file_get_contents($foto->getRealPath()));
        }

        $db = Firebase::database();
        $userId = auth()->id();

        $db->getReference('mascotas/' . $userId)->push([
            'nombre' => $data['nombre'],
            'especie' => $data['especie'],
            'raza' => $data['raza'] ?? '',
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? '',
            'foto' => $fotoBase64,
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect('/mascotas')->with('success', '¡Mascota agregada!');
    }
    public function edit($id)
{
    $db = Firebase::database();
    $userId = auth()->id();
    $mascota = $db->getReference('mascotas/' . $userId . '/' . $id)->getValue();

    if (!$mascota) {
        return redirect('/mascotas')->with('error', 'Mascota no encontrada.');
    }

    return view('mascotas.edit', ['mascota' => $mascota, 'id' => $id]);
}
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'raza' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
            'foto' => 'nullable|image|max:2048',
        ]);

        $db = Firebase::database();
        $userId = auth()->id();
        $ref = $db->getReference('mascotas/' . $userId . '/' . $id);

        $actualizacion = [
            'nombre' => $data['nombre'],
            'especie' => $data['especie'],
            'raza' => $data['raza'] ?? '',
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? '',
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $actualizacion['foto'] = 'data:' . $foto->getMimeType() . ';base64,' . base64_encode(file_get_contents($foto->getRealPath()));
        }

        $ref->update($actualizacion);

        return redirect('/mascotas')->with('success', '¡Mascota actualizada!');
    }
     public function destroy($id)
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $db->getReference('mascotas/' . $userId . '/' . $id)->remove();

        return redirect('/mascotas')->with('success', 'Mascota eliminada.');
    }

    private function calcularEdad($fecha)
    {
        if (empty($fecha)) {
            return null;
        }

        $nacimiento = \Carbon\Carbon::parse($fecha);
        $hoy = \Carbon\Carbon::now();

        $años = (int) $nacimiento->diffInYears($hoy);
        $meses = (int) $nacimiento->copy()->addYears($años)->diffInMonths($hoy);

        $partes = [];
        if ($años > 0) {
            $partes[] = $años . ' ' . ($años === 1 ? 'año' : 'años');
        }
        if ($meses > 0) {
            $partes[] = $meses . ' ' . ($meses === 1 ? 'mes' : 'meses');
        }
        if (empty($partes)) {
            $partes[] = 'Recién nacido';
        }

        return implode(' con ', $partes);
    }
}