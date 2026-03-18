<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHasSalesManager extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function storeManager()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
