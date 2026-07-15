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
        //
        Schema::table("inspection_reports", function (Blueprint $table) {
            $table->string("permittee_signed")->nullable()->change();
            $table->string("inspector_signed")->nullable()->change();
            $table->string("approver_signed")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
