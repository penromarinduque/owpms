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
        Schema::create('ltp_review_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_permit_id');
            $table->integer('user_id');
            $table->enum('action_taken', ['reviewed', 'approved', 'rejected']);
            $table->text('remarks')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_review_logs');
    }
};
