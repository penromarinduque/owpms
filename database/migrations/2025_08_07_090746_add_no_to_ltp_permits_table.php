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
        Schema::table('ltp_permits', function (Blueprint $table) {
            //
            // Adding 'no' column to the ltp_permits table
            $table->integer('no')->after('permit_number');
            $table->dropColumn('chief_rps_signed');
            $table->dropColumn('chief_rps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ltp_permits', function (Blueprint $table) {
            //
        });
    }
};
