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
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->enum('provider', ['paypal', 'apple', 'google']);
        $table->string('provider_order_id')->nullable();
        $table->string('provider_transaction_id')->nullable();

        $table->enum('product_type', ['lifetime', 'subscription'])->default('lifetime');

        $table->decimal('amount', 8, 2);
        $table->string('currency', 3)->default('USD');

        $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');

        $table->json('receipt_data')->nullable();   // Apple / Google receipts
        $table->json('raw_response')->nullable();   // Respuesta completa del proveedor

        $table->timestamp('confirmed_at')->nullable();

        $table->timestamps();

        $table->index(['provider', 'provider_order_id']);
        $table->index(['user_id', 'status']);
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
