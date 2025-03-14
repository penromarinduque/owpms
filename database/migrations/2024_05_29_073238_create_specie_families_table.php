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
        Schema::create('specie_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specie_class_id');
            $table->string('family', 150)->unique();
            $table->tinyInteger('is_active_family')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specie_families');
    }
};
