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
        Schema::create('products', function (Blueprint $table) {
           $table->id(); // id - khóa chính, auto increment
            $table->string('name', 100); // Tên sản phẩm
            $table->unsignedBigInteger('category_id'); // Mã nhóm hàng - khóa ngoại
            $table->unsignedBigInteger('scent_id')->nullable(); // Mã mùi
            $table->unsignedBigInteger('manufacturer_id'); // Mã NSX - khóa ngoại
            $table->decimal('price', 10, 2); // Giá sản phẩm
            $table->text('description')->nullable(); // Mô tả
            $table->string('image', 255)->nullable(); // Hình ảnh
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('scent_id')->references('id')->on('scents')->onDelete('set null');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
