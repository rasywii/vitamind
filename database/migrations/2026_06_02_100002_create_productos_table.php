<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['fisico', 'digital'])->default('fisico');
            $table->decimal('precio', 8, 2);
            $table->integer('stock')->default(0);          // solo aplica a productos fisicos
            $table->boolean('controla_stock')->default(true); // false para digitales
            $table->string('archivo_url')->nullable();      // solo para digitales
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
