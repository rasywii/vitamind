<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('autor')->default('Equipo VitaMind');
            $table->string('imagen')->nullable();
            $table->text('extracto')->nullable();
            $table->longText('contenido');            // HTML del articulo
            $table->unsignedInteger('tiempo_lectura')->default(3); // minutos
            $table->unsignedInteger('vistas')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
