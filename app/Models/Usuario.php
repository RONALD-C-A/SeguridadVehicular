<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'IdUsuario';

    protected $fillable = [
        'Rol',
        'Nombre',
        'NombreUsuario',
        'Password',
        'Email',
        'ResetToken',
        'ResetTokenExpires',
        'Estado',
        'UltimaActualizacion'
    ];

    protected $hidden = [
        'Password',
        'ResetToken'
    ];

    protected $casts = [
        'ResetTokenExpires' => 'datetime',
        'FechaRegistro' => 'datetime',
        'UltimaActualizacion' => 'datetime',
        'Estado' => 'boolean'
    ];

    public $timestamps = false;

    // Mutador para encriptar la contraseña
    public function setPasswordAttribute($valor)
    {
        $this->attributes['Password'] = Hash::make($valor);
    }

    // Accessor para obtener la contraseña encriptada
    public function getAuthPassword()
    {
        return $this->Password;
    }

    // Método para verificar si el usuario está activo
    public function estaActivo()
    {
        return $this->Estado == 1;
    }

    // Método para generar token de restablecimiento
    public function generarTokenRestablecimiento()
    {
        $this->ResetToken = bin2hex(random_bytes(32));
        $this->ResetTokenExpires = now()->addHours(1);
        $this->save();
        
        return $this->ResetToken;
    }

    // Método para verificar si el token es válido
    public function tokenEsValido($token)
    {
        return $this->ResetToken === $token && 
               $this->ResetTokenExpires && 
               $this->ResetTokenExpires > now();
    }

    // Método para limpiar token
    public function limpiarToken()
    {
        $this->ResetToken = null;
        $this->ResetTokenExpires = null;
        $this->save();
    }

    // Método para activar usuario
    public function activarUsuario()
    {
        $this->Estado = 1;
        $this->limpiarToken();
    }
}