<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturer_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('manufacturer_id');
            $table->integer('quantity');
            $table->string('contact_info');
            $table->enum('status', ['Đang gửi', 'Đã gửi', 'Đã xử lý'])->default('Đã gửi');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturer_requests');
    }
};
