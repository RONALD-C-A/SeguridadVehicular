<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si la tabla ya existe, solo modificamos si es necesario
        if (Schema::hasTable('usuario')) {
            Schema::table('usuario', function (Blueprint $table) {
                // Agregar índices para mejorar rendimiento si no existen
                if (!Schema::hasColumn('usuario', 'created_at')) {
                    $table->index('Email');
                    $table->index('NombreUsuario');
                    $table->index('Estado');
                    $table->index('ResetToken');
                }
            });
        } else {
            // Si no existe, crear la tabla completa
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('IdUsuario');
                $table->enum('Rol', ['CLIENTE', 'ADMINISTRADOR'])->default('CLIENTE');
                $table->string('Nombre', 45);
                $table->string('NombreUsuario', 45)->unique();
                $table->string('Password', 255);
                $table->string('Email', 45)->unique();
                $table->string('ResetToken', 255)->nullable();
                $table->timestamp('ResetTokenExpires')->nullable();
                $table->tinyInteger('Estado')->default(1);
                $table->timestamp('FechaRegistro')->useCurrent();
                $table->timestamp('UltimaActualizacion')->nullable();

                // Índices para optimizar consultas
                $table->index('Email');
                $table->index('NombreUsuario');
                $table->index('Estado');
                $table->index('ResetToken');
                $table->index('FechaRegistro');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de rollback, eliminamos solo los índices que agregamos
        if (Schema::hasTable('usuario')) {
            Schema::table('usuario', function (Blueprint $table) {
                $table->dropIndex(['Email']);
                $table->dropIndex(['NombreUsuario']);
                $table->dropIndex(['Estado']);
                $table->dropIndex(['ResetToken']);
            });
        }
    }
};
