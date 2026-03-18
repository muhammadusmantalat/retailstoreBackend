<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssignToVendor extends Model
{
    use HasFactory;
    protected $fillable =['store_manager_id','store_id','department_id','vendor_id','product_id','product_price'];

    public function storeManager()
    {
        return $this->belongsTo(User::class,'store_manager_id','id');
    }
    public function store()
    {
    return $this->belongsTo(Store::class,'store_id','id');
    }

    public function departments()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }





}
