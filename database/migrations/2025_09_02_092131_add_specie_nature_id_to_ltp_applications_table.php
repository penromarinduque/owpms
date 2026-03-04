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
            $table->unsignedBigInteger('specie_nature_id')->after('purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ltp_applications', function (Blueprint $table) {
            //
            $table->dropColumn('specie_nature_id');
        });
    }
};
