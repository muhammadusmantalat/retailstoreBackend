<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignVendor extends Model
{
    use HasFactory;

    protected $fillable = ['store_manager_id', 'store_id', 'department_id', 'vendor_id'];

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


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function assignVendorToDepartment()
    {
        return $this->belongsTo(AssignVendorToDepartment::class, 'store_id', 'id');
    }
}
