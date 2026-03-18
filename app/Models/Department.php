<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['department_name', 'store_id', 'store_manager_id','tax_status','image'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id','id');
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'department_id','id');
    }

    public function assignvendors()
    {
        return $this->hasMany(AssignVendor::class, 'department_id','id');
    }

    public function assignVendorToDepartment()
    {
        return $this->hasMany(AssignVendor::class, 'department_id','id');
    }

    public function productAssign()
    {
        return $this->hasMany(ProductAssign::class,'department_id','id');
    }

    public function productAssignToDepartment()
    {
        return $this->hasMany(AssignVendor::class, 'department_id','id');
    }

    public function productAssignToVendor()
    {
        return $this->hasMany(productAssignToVendor::class, 'department_id','id');
    }

    public function product()
    {
        return $this->hasMany(ProductAssign::class,'product_id','id');
    }

}
