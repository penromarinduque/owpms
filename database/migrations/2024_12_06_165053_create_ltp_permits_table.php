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
            // $table->enum('status', ['under-review', 'approved', 'rejected'])->default('under-review');
            $table->integer('penro');
            $table->string('approver_designation', 100);
            $table->integer('chief_tsd');
            $table->integer('chief_rps');
            $table->boolean('chief_tsd_signed')->nullable();
            $table->boolean('chief_rps_signed')->nullable();
            $table->boolean('penro_signed')->nullable();
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
