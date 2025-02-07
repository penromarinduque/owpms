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
        Schema::create('sub_breeders', function (Blueprint $table) {
            $table->id();
            $table->integer('permittee_id');
            $table->integer('specie_id');
            $table->string('sub_breeder_name');
            $table->string('sub_breeder_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_breeders');
    }
};
