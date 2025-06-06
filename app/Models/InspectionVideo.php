<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionVideo extends Model
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'inspection_videos';

    protected $guarded = [];    
}
