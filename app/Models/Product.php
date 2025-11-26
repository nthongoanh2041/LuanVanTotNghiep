<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'price', 'description', 'category_id', 'scent_id','image', 'manufacturer_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
     public function scent()
    {
        return $this->belongsTo(Scent::class);
    }

    protected static function booted()
{
    static::saving(function ($product) {
        $product->status = $product->quantity < 10 ? 0 : 1;
    });
}

}
