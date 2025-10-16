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
        Schema::create('manufacturers', function (Blueprint $table) {
           $table->id(); // Mã NSX - khóa chính
            $table->string('country', 100); // Nơi sản xuất
            $table->string('name', 100); // Tên nhà sản xuất
            $table->text('description')->nullable(); // Mô tả
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
