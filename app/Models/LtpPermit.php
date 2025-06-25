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
                $sub->where('chief_rps', $userId)
                    ->where(function ($q) {
                        $q->where('chief_rps_signed', false)
                        ->orWhereNull('chief_rps_signed');
                    });
            })
            ->orWhere(function($sub) use ($userId) {
                $sub->where('chief_tsd', $userId)
                    ->where(function ($q) {
                        $q->where('chief_tsd_signed', false)
                        ->orWhereNull('chief_tsd_signed');
                    })
                    ->where('chief_rps_signed', true);
            })
            ->orWhere(function($sub) use ($userId) {
                $sub->where('penro', $userId)
                    ->where(function ($q) {
                        $q->where('penro_signed', false)
                        ->orWhereNull('penro_signed');
                    })
                    ->where('chief_rps_signed', true)
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
    
}
