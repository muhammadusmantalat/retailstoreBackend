<?php

namespace App\Models;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','product_id','quantity','price','sub_total','image','product_name','discount_price','priceAfterDiscount','discount_amount'];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

}
