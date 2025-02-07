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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('last_name', 150);
            $table->string('first_name', 150);
            $table->string('middle_name', 150)->nullable();
            $table->enum('gender',['male', 'female']);
            $table->string('email', 100)->unique();
            $table->string('contact_no', 30);
            $table->integer('barangay_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
