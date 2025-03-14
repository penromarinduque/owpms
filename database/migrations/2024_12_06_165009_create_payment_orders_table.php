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
            $table->enum('status', ['pending', 'paid', 'cancelled']);
            $table->enum('payment_method', ['cash', 'linkbiz', 'gcash', 'paymaya', 'bank-transfer', 'others']);
            $table->string('payment_reference')->nullable();
            $table->string('receipt_url')->nullable();
            $table->date('issued_date');
            $table->integer('prepared_by');
            $table->integer('approved_by');
            $table->text('remarks')->nullable();
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
