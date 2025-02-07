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
        Schema::create('permittees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('permit_number', 100)->unique();
            $table->enum('permit_type', ['wcp', 'wfp']);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->date('date_of_issue');
            $table->enum('status', ['valid', 'expired', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permittees');
    }
};
