<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->string('canal')->default('tienda');   // 'tienda' o 'admin'
            $table->string('metodo_pago')->nullable();     // qr / efectivo / transferencia
            $table->timestamps();                           // created_at = fecha del pedido
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
