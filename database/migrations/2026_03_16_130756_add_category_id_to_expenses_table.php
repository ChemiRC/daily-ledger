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
    Schema::table('expenses', function (Blueprint $table) {
        // Añadimos la columna category_id y la conectamos a la tabla categories
        $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
