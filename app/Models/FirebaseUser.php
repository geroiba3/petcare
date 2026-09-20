<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class FirebaseUser implements Authenticatable
{
    public string $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public bool $activo;
    public ?string $veterinaria_id;
    public ?array $horario;
    public ?string $created_at;
    public ?string $remember_token = null;

    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->activo = $data['activo'] ?? true;
        $this->veterinaria_id = $data['veterinaria_id'] ?? null;
        $this->horario = $data['horario'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->remember_token = $data['remember_token'] ?? null;
    }

    // --- Helpers de rol, iguales a los que ya usás en el resto del código ---
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVet(): bool
    {
        return $this->role === 'vet';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // --- Métodos que pide la interfaz Authenticatable ---
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): string
    {
        return $this->id;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    // --- Convertir a array, útil para guardar en Firebase ---
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'activo' => $this->activo,
            'veterinaria_id' => $this->veterinaria_id,
            'horario' => $this->horario,
            'created_at' => $this->created_at,
            'remember_token' => $this->remember_token,
        ];
    }
}