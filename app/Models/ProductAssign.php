<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssign extends Model
{
    use HasFactory;
    Protected $fillable =['store_manager_id','store_id','product_id'];

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id','id');
    }

    public function storeManager()
    {
        return $this->belongsTo(User::class , 'store_manager_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

}
