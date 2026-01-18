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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference')->unique(); 
            $table->enum('method', [ 'cash','gcash','paymaya','paypal', 'credit_card','bank_transfer','cheque']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', [ 'pending','paid','failed','refunded','partially_refunded'])->default('pending');
            $table->decimal('refunded_amount', 10, 2)->nullable();
            $table->string('refund_reason')->nullable();
            $table->timestamp('refund_requested_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index('payment_reference');
            $table->index('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
