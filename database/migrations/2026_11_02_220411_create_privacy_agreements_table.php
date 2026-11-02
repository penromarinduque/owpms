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
        Schema::create('privacy_agreements', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->foreignId("user_id");
            $table->foreignId("ltp_application_id");
            $table->double("version");
            $table->string("status", 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_agreements');
    }
};
