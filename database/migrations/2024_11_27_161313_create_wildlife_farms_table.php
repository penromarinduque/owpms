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
        Schema::create('wildlife_farms', function (Blueprint $table) {
            $table->id();
            $table->integer('permittee_id');
            $table->string('farm_name', 150);
            $table->string('location', 200);
            $table->string('size', 50)->nullable();
            // $table->string('height', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wildlife_farms');
    }
};
