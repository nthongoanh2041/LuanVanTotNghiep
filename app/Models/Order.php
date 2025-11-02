<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'total_amount',
        'status','is_processed',
        'user_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(Order_items::class);
    }

    public function orderItems()
    {
    return $this->hasMany(Order_items::class, 'order_id');
    }
       public function order_items()
{
    return $this->hasMany(Order_items::class, 'order_id', 'id');
}

}
