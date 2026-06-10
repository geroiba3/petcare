<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MascotaController extends Controller
{
    public function index()
    {
        $db = Firebase::database();
        $userId = auth()->id();
        $mascotas = $db->getReference('mascotas/' . $userId)->getValue();

        return view('mascotas.index', ['mascotas' => $mascotas ?? []]);
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
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $db = Firebase::database();
        $userId = auth()->id();

        $db->getReference('mascotas/' . $userId)->push([
            'nombre' => $data['nombre'],
            'especie' => $data['especie'],
            'raza' => $data['raza'] ?? '',
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? '',
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect('/mascotas')->with('success', '¡Mascota agregada!');
    }
}