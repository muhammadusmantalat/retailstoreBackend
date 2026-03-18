<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    // protected $fillable = ['vendor_name', 'email', 'phone_no', 'order_dates', 'store_manager_id', 'store_id','delivery_days','salesman_name','salesman_phone_no','order_frequency','delivery_frequency','general_discount','image','overcharged_prices'];
    protected $guarded = [];


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function storeManager()
    {
        return $this->belongsTo(User::class, 'store_manager_id', 'id');
    }

    public function assignVendors()
    {
        return $this->hasMany(AssignVendor::class, 'vendor_id', 'id');
    }
    public function assignVendorToDepartment()
    {
        return $this->hasMany(AssignVendorToDepartment::class, 'vendor_id', 'id');
    }

    public function productAssignToVendor()
    {
        return $this->hasMany(productAssignToVendor::class, 'vendor_id','id');
    }

    public function order()
    {
        return $this->hasMany(Orders::class, 'vendor_id','id');
    }

    public function salesMen()
    {
        return $this->hasOne(StoreHasSalesManager::class, 'whole_seller_id');
    }

    public function discount()
    {
        return $this->hasOne(StoreVendorGenralDiscount::class, 'vendor_id');
    }
}
