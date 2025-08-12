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
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->integer('user_id');
            $table->integer('inspector_id');
            $table->integer('approver_id');
            $table->date('inspection_date');
            // $table->text('report_details');
            $table->boolean('permittee_signed')->default(false);
            $table->boolean('inspector_signed')->default(false);
            $table->boolean('approver_signed')->default(false);
            $table->string('approver_position', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_reports');
    }
};
