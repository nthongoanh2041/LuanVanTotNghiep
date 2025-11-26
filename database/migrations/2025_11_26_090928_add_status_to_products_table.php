<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('products', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('quantity')->comment('1 = còn hàng, 0 = sắp hết hàng');
        });

        // Cập nhật giá trị status dựa trên quantity
        DB::table('products')->update([
            'status' => DB::raw('IF(quantity < 10, 0, 1)')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
