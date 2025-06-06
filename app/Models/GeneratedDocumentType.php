<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedDocumentType extends Model
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'generated_document_types';

    protected $guarded = [];
}
