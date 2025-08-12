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
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->integer('specie_type_id');
            $table->integer('specie_class_id');
            $table->integer('specie_family_id');
            $table->string('specie_name', 150);
            $table->tinyInteger('is_present')->default(0);
            $table->string('local_name', 150);
            $table->string('wing_span', 50)->nullable();
            $table->char('conservation_status', 50);
            $table->string('color_description', 150)->nullable();
            $table->string('food_plant', 150)->nullable();
            $table->tinyInteger('is_active_specie')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('species');
    }
};
