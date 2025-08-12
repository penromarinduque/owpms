<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Permission extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
}
