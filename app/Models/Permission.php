<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
}
