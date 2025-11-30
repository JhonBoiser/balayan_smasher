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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->enum('payment_method', ['card', 'gcash', 'grab_pay', 'cod', 'bank_transfer', 'paymaya'])->default('card');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'cancelled'])->default('pending');
            $table->string('currency')->default('PHP');
            $table->text('response_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
