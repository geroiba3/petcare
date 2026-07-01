<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($mascotaId)
    {
        $db = Firebase::database();
        $userId = auth()->id();

        $mascota = $db->getReference('mascotas/' . $userId . '/' . $mascotaId)->getValue();
        $historial = $db->getReference('historial/' . $userId . '/' . $mascotaId)->getValue() ?? [];

        if ($historial) {
            uasort($historial, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));
        }

        return view('historial.index', [
            'mascota' => $mascota,
            'mascotaId' => $mascotaId,
            'historial' => $historial,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
