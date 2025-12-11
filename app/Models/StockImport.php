<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    use HasFactory;

    protected $table = 'stock_imports';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
        'import_date',
    ];

    /**
     * Mỗi phiếu nhập thuộc về 1 sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
