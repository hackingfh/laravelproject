<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number', 32)->unique()->index();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled'])->index();
            $table->json('shipping_address');
            $table->string('payment_method', 64);
            $table->string('payment_status', 32)->default('pending')->index();
            $table->string('tracking_number', 64)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('whatsapp_sent')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
