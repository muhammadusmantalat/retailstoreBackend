<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlavour extends Model
{
    use HasFactory;
    protected $fillable =['product_id','flavour_name'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
