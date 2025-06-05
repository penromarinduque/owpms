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
        Schema::create('geottagged_images', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->string('image_url', 150);
            $table->string('geotag', 200)->nullable();
            $table->enum('uploaded_by', ['permittee', 'internal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geottagged_images');
    }
};
