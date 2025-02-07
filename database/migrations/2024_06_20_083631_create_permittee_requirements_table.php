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
        Schema::create('permittee_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('permittee_id');
            $table->tinyInteger('type');
            $table->string('requirement', 150);
            $table->tinyInteger('is_active_req');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permittee_requirements');
    }
};
