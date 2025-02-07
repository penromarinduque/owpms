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
        Schema::create('ltp_permits', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->string('permit_number')->unique();
            $table->enum('status', ['under-review', 'approved', 'rejected'])->default('under-review');
            $table->boolean('chief_tsd_signature');
            $table->boolean('chief_rps_signature');
            $table->boolean('penro_signature');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_permits');
    }
};
