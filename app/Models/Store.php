<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['storeManger_id', 'store_name','store_address','store_phone_no'];

    public function user()
    {
        return $this->belongsTo(User::class, 'storeManger_id','id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'store_id');
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'store_id');
    }

    public function assignvendors()
    {
        return $this->hasMany(AssignVendor::class, 'store_id');
    }

    public function assignVendorToDepartment()
    {
        return $this->hasMany(AssignVendor::class, 'store_id');
    }

    public function productAssign()
    {
        return $this->hasMany(ProductAssign::class,'store_id');
    }

    public function productAssignToDepartment()
    {
        return $this->hasMany(AssignVendor::class, 'store_id');
    }

    public function productAssignToVendor()
    {
        return $this->hasMany(productAssignToVendor::class, 'store_id','id');
    }

    public function product()
    {
        return $this->hasMany(Product::class,'store_id','id');
    }

    public function salesManager()
    {
        return $this->hasOne(StoreHasSalesManager::class, 'store_id');
    }

        public function discount()
    {
        return $this->hasOne(StoreVendorGenralDiscount::class, 'store_id');
    }

}
