<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasRoles, HasPermissions, HasApiTokens, Notifiable;
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $fillable = ['first_name', 'last_name', 'email', 'image', 'phone', 'address', 'user_type', 'password', 'is_active', 'fcm_token'];

    public function store()
    {
        return $this->hasMany(Store::class, 'storeManger_id', 'id');
    }
    public function assignVendorToDepartment()
    {
        return $this->hasMany(AssignVendorToDepartment::class, 'store_manager_id', 'id');
    }
    public function getAssignPermission()
    {
        return $this->hasMany(Permission_component::class, 'user_id');
    }
}
