<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserRole extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'user_roles';

    public $timestamps = true;

    protected $fillable = ['user_id', 'role_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
