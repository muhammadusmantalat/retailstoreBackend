<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVendorGenralDiscount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function vendor()    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
