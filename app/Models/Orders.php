<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['store_manager_id','store_id','vendor_id','total_quantity','total_price','status','invoice_number','store_manager_name','store_name','vendor_name','date','order_code','store_phone_no','store_address'];

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id','id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id','id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'store_manager_id','id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id','id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
    public function audit()
    {
        return $this->hasOne(Audit::class, 'order_id', 'id');
    }

    public function checkOrder()
    {
        return $this->hasOne(checkOrder::class, 'order_id', 'id'); // Ensure casing is consistent
    }




}
