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
    Schema::create('payment_webhooks', function (Blueprint $table) {
        $table->id();

        $table->enum('provider', ['paypal', 'apple', 'google']);
        $table->string('event_type')->nullable();
        $table->string('event_id')->nullable();

        $table->json('payload');
        $table->boolean('processed')->default(false);

        $table->timestamps();

        $table->index(['provider', 'event_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('payment_webhooks');
}
};
