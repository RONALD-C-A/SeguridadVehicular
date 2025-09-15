<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        Usuario::create([
            'Rol' => 'ADMINISTRADOR',
            'Nombre' => 'Administrador del Sistema',
            'NombreUsuario' => 'admin',
            'Password' => 'admin123', // Se encriptará automáticamente con el mutador
            'Email' => 'admin@sistema.com',
            'Estado' => 1,
            'FechaRegistro' => now(),
        ]);

        // Crear usuario cliente de prueba
        Usuario::create([
            'Rol' => 'CLIENTE',
            'Nombre' => 'Usuario de Prueba',
            'NombreUsuario' => 'usuario_prueba',
            'Password' => '123456', // Se encriptará automáticamente
            'Email' => 'usuario@prueba.com',
            'Estado' => 1,
            'FechaRegistro' => now(),
        ]);
    }
}