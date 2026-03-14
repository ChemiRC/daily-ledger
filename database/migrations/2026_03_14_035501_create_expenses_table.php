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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('description'); // Qué compraste
        $table->decimal('amount', 10, 2); // Cuánto costó
        $table->date('expense_date'); // <-- NUEVA LÍNEA: Campo para la fecha
        $table->boolean('is_essential')->default(true); // El botón: true = necesario, false = innecesario
        $table->timestamps(); // Fecha y hora del gasto
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
