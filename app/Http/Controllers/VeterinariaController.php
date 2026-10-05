<?php

namespace App\Http\Controllers;

use Kreait\Laravel\Firebase\Facades\Firebase;

class VeterinariaController extends Controller
{
    public function index()
    {
        $db = Firebase::database();

        $veterinarias = $db->getReference('veterinarias')->getValue() ?? [];
        $usuarios = $db->getReference('usuarios')->getValue() ?? [];

        $medicosPorVeterinaria = [];
        foreach ($usuarios as $uid => $usuario) {
            if (!is_array($usuario) || ($usuario['role'] ?? null) !== 'vet') {
                continue;
            }
            $vetId = $usuario['veterinaria_id'] ?? null;
            if (!$vetId) {
                continue;
            }
            $medicosPorVeterinaria[$vetId][] = $usuario['name'] ?? '(sin nombre)';
        }

        $veterinarias = collect($veterinarias)->map(function ($veterinaria, $id) use ($medicosPorVeterinaria) {
            $veterinaria['id'] = $id;
            $veterinaria['medicos'] = $medicosPorVeterinaria[$id] ?? [];
            return $veterinaria;
        })->values();

        return view('veterinarias.index', ['veterinarias' => $veterinarias]);
    }
}