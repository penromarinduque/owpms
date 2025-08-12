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
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('ltp_application_id');
            $table->integer('ltp_fee_id');
            $table->string('order_number')->unique();
            $table->string('serial_number')->unique()->nullable();
            $table->integer('year');
            $table->integer('no');
            $table->enum('status', ['pending', 'paid', 'cancelled']);
            $table->enum('payment_method', ['cash', 'linkbiz', 'gcash', 'paymaya', 'bank-transfer', 'others']);
            $table->string('payment_reference')->nullable();
            $table->string('receipt_url')->nullable();
            $table->date('issued_date');
            $table->integer('prepared_by');
            $table->integer('approved_by');
            $table->integer('oop_approved_by');
            $table->boolean('prepared_signed')->nullable()->default(false);
            $table->boolean('approved_signed')->nullable()->default(false);
            $table->boolean('oop_approved_signed')->nullable()->default(false);
            $table->string('prepared_by_position');
            $table->string('approved_by_position');
            $table->string('oop_approved_by_position');
            $table->string('address')->nullable();
            $table->text('remarks')->nullable();
            $table->string('document', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
