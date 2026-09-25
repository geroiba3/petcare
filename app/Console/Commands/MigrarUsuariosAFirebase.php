<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MigrarUsuariosAFirebase extends Command
{
    protected $signature = 'petcare:migrar-usuarios';
    protected $description = 'Migra los usuarios de SQLite a Firebase Realtime Database, preservando sus IDs actuales como clave.';

    public function handle()
    {
        $usuarios = User::all();
        $db = Firebase::database();

        $this->info("Encontrados {$usuarios->count()} usuarios en SQLite.");

        foreach ($usuarios as $user) {
            $existente = $db->getReference('usuarios/' . $user->id)->getValue();

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
                'activo' => $user->activo ?? true,
                'veterinaria_id' => $user->veterinaria_id ?? null,
                'horario' => $existente['horario'] ?? null, // preservamos si ya estaba cargado
                'created_at' => $user->created_at?->toDateTimeString(),
            ];

            $db->getReference('usuarios/' . $user->id)->update($data);

            $this->line("Migrado: {$user->email} (id: {$user->id})");
        }

        $this->info('¡Migración completa!');
    }
}