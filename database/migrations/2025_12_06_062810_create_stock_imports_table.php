<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('stock_imports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id'); // khóa ngoại tới bảng products
        $table->integer('quantity');              // số lượng nhập
        $table->date('import_date');              // ngày nhập
        $table->text('note')->nullable();         // ghi chú
        $table->timestamps();

        // Khóa ngoại
        $table->foreign('product_id')
              ->references('id')
              ->on('products');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_import');
    }
};
