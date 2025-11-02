<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_phone', 20);
            $table->date('order_date')->default(now());
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->boolean('is_processed')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Khóa ngoại liên kết với bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
