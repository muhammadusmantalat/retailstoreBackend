<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommandBy extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'store_manager_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
