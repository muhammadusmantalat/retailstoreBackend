<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission_component extends Model
{
    use HasFactory;
    Protected $fillable = ['user_id','permission_id'];

    public function users(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
