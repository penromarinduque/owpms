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
        Schema::create('inspection_videos', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->string('video_url');
            $table->integer('file_size');
            $table->enum('uploaded_by', ['permittee', 'internal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_videos');
    }
};
