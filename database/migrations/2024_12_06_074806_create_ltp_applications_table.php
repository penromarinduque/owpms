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
        Schema::create('ltp_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('permittee_id');
            $table->string('application_no');
            $table->enum('application_status', ['draft', 'submitted', 'under-review', 'returned', 'resubmitted', 'accepted', 'payment-in-process', 'paid', 'for-inspection', 'inspection-rejected', 'approved', 'expired'])->default('draft');
            $table->date('application_date');
            $table->date('transport_date');
            $table->text('purpose')->nullable();
            $table->string('destination');
            $table->string('digital_signature', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltp_applications');
    }
};
