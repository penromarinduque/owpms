<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GeneratedDocumentType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    //
    protected $table = 'generated_document_types';

    protected $guarded = [];
}
