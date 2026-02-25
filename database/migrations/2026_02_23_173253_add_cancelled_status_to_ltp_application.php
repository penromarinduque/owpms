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
        Schema::table('ltp_applications', function (Blueprint $table) {
            //
            $table->enum('application_status',['draft', 'submitted', 'under-review', 'returned', 'resubmitted', 'accepted', 'payment-in-process', 'paid', 'for-inspection', 'inspection-rejected', 'inspected', 'approved', 'expired', 'released', 'reviewed', 'cancelled'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ltp_application', function (Blueprint $table) {
            //
        });
    }
};
