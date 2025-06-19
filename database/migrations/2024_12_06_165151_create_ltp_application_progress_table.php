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
        Schema::create('ltp_application_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->integer('user_id');
            $table->enum('status', ['submitted', 'under-review', 'returned', 'resubmitted', 'accepted', 'payment-in-process', 'paid', 'for-inspection', 'inspection-rejected',  'approved', 'expired', 'released', 'reviewed']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_application_progress');
    }
};
