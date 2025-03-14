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
        Schema::create('specie_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specie_type_id');
            $table->string('specie_class', 150)->unique();
            $table->tinyInteger('is_active_class')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specie_classes');
    }
};
