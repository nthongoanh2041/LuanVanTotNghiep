<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'manufacturer_id',
        'quantity',
        'contact_info',
        'status',
        'user_id',
    ];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
