<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE manufacturer_requests MODIFY COLUMN status ENUM('Đã gửi', 'Đã xác nhận', 'Từ chối') DEFAULT 'Đã gửi'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE manufacturer_requests MODIFY COLUMN status ENUM('Đang gửi', 'Đã gửi', 'Đã xử lý') DEFAULT 'Đã gửi'");
    }
};

