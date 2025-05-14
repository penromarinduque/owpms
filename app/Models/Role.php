<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'roles';
    protected $id = 'id';    
    public $guarded = [];

    public function rolePermissions() {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }
}
