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
        Schema::create('permittee_specie_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('permittee_id');
            $table->integer('species_id');
            $table->enum('request_type', ['add', 'update']);
            $table->enum('request_status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permittee_specie_requests');
    }
};
