<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Signatory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    protected $table = 'signatories';
    
    protected $guarded = [];

    public function documentType()
    {
        return $this->belongsTo(GeneratedDocumentType::class, 'generated_document_type_id', 'id');
    }

    public function signatoryRole()
    {
        return $this->belongsTo(SignatoryRole::class, 'signatory_role_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
