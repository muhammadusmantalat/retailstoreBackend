<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = ['store_manager_id', 'store_id', 'product_id', 'vendor_id', 'status'];

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
}
