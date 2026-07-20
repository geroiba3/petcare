<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class VeterinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $db = Firebase::database();
        $veterinarias = $db->getReference('veterinarias')->getValue() ?? [];

        return view('admin.veterinarias.index', ['veterinarias' => $veterinarias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.veterinarias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'horarios' => 'nullable|string|max:255',
        ]);

        $db = Firebase::database();

        $db->getReference('veterinarias')->push([
            'nombre' => $data['nombre'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'] ?? '',
            'horarios' => $data['horarios'] ?? '',
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('admin.veterinarias.index')->with('success', '¡Veterinaria agregada!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $db = Firebase::database();
        $veterinaria = $db->getReference('veterinarias/' . $id)->getValue();

        if (!$veterinaria) {
            return redirect()->route('admin.veterinarias.index')->with('error', 'Veterinaria no encontrada.');
        }

        return view('admin.veterinarias.edit', ['veterinaria' => $veterinaria, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'horarios' => 'nullable|string|max:255',
        ]);

        $db = Firebase::database();
        $db->getReference('veterinarias/' . $id)->update($data);

        return redirect()->route('admin.veterinarias.index')->with('success', '¡Veterinaria actualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $db = Firebase::database();
        $db->getReference('veterinarias/' . $id)->remove();

        return redirect()->route('admin.veterinarias.index')->with('success', 'Veterinaria eliminada.');
    }
}
