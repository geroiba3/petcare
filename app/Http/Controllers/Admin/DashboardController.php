<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $db = Firebase::database();
        $todos = $db->getReference('usuarios')->getValue() ?? [];

        $totalUsuarios = 0;
        $totalVeterinarios = 0;

        foreach ($todos as $usuario) {
            if (!is_array($usuario)) {
                continue;
            }
            if (($usuario['role'] ?? null) === 'user') {
                $totalUsuarios++;
            } elseif (($usuario['role'] ?? null) === 'vet') {
                $totalVeterinarios++;
            }
        }

        return view('admin.dashboard', compact('totalUsuarios', 'totalVeterinarios'));
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