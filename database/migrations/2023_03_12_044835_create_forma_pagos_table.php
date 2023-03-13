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
        Schema::create('forma_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('num_tajeta', 40)->unique();
            $table->string('nombre_tajeta', 30);
            $table->string('codigo', 3)->unique();
            $table->date('fecha_vencimiento');
            $table->string('tipo_pago', 2);
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forma_pagos');
    }
};
