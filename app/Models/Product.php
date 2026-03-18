<?php

namespace App\Models;

use App\Models\ProductsPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'upc_ipc', 'price', 'store_manager_id', 'store_id', 'status'];

    public function productAssignToVendors()
    {
        return $this->hasMany(productAssignToVendor::class, 'product_id', 'id');
    }

    public function department()
    {
        return $this->hasMany(Department::class, 'department_id', 'id');
    }
    public function storeManager()
    {
        return $this->belongsTo(User::class, 'store_manager_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function productFlavour()
    {
        return $this->hasMany(ProductFlavour::class, 'product_id', 'id');
    }

    public function productImage()
    {
        return $this->hasMany(ProductsPhoto::class, 'product_id', 'id');
    }

    public function productAssignToDepartment()
    {
        return $this->hasMany(ProductAssignToDepartment::class, 'product_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }


}
