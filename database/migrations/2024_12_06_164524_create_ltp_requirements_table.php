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
        Schema::create('ltp_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('requirement_name', 150);
            $table->boolean('is_mandatory');
            $table->tinyInteger('is_active_requirement')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_requirements');
    }
};
