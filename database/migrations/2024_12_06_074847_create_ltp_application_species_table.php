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
        Schema::create('ltp_application_species', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->integer('specie_id');
            $table->integer('quantity');
            $table->boolean('is_endangered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_application_species');
    }
};
