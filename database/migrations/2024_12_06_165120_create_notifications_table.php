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
        // Schema::create('notifications', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('ltp_permit_id');
        //     $table->integer('permittee_id');
        //     $table->enum('notification_type', ['ltp-approved', 'ltp-rejected', 'ltp-ready-for-printing']);
        //     $table->text('notification_message')->nullable();
        //     $table->boolean('is_read')->default(false);
        //     $table->timestamp('sent_at')->useCurrent();
        //     $table->timestamps();
        // });
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('notification_message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('action', 1000);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
