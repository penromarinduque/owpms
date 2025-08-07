<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LtpPermit extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'ltp_permits';

    protected $guarded = [];


    public function scopePendingSignaturesFor($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where(function($sub) use ($userId) {
                $sub->where('chief_tsd', $userId)
                    ->where(function ($q) {
                        $q->where('chief_tsd_signed', false)
                        ->orWhereNull('chief_tsd_signed');
                    });
            })
            ->orWhere(function($sub) use ($userId) {
                $sub->where('penro', $userId)
                    ->where(function ($q) {
                        $q->where('penro_signed', false)
                        ->orWhereNull('penro_signed');
                    })
                    ->where('chief_tsd_signed', true);
            });
        });
    }

    public function ltpApplication(){
        return $this->belongsTo(LtpApplication::class, 'ltp_application_id', 'id');
    }

    public function approver(){
        return $this->belongsTo(User::class, 'penro', 'id');
    }

    public function getStatusAttribute() {
        if(!$this->chief_tsd_signed) {
            return '<span class="badge bg-secondary">Pending Chief TSD Signature</span>';
        } else if(!$this->penro_signed) {
            return '<span class="badge bg-secondary">Pending PENRO Signature</span>';
        } else {
            return '<span class="badge bg-success">Approved</span>';
        }
    }
    
}
