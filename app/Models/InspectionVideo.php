<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InspectionVideo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'inspection_videos';

    protected $guarded = [];    
}
