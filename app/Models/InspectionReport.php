<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InspectionReport extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'inspection_reports';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'inspection_date' => 'datetime',
    ];

    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function inspector() {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function permittee() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rpsInitial() {
        return $this->belongsTo(User::class, 'rps_initial_id');
    }

    public function ltpApplication() {
        return $this->belongsTo(LtpApplication::class);
    }

    public function scopePendingSignaturesFor($query, $userId) {
        return $query->where(function ($query) use ($userId) {
                $query->where('inspector_id', $userId)
                    ->whereNull('inspector_signed')
                    ->whereNotNull('permittee_signed');
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('approver_id', $userId)
                    ->whereNull('approver_signed')
                    ->where('rps_signed', true)
                    ->whereNotNull('permittee_signed')
                    ->whereNotNull('inspector_signed');
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('rps_initial_id', $userId)
                    ->where('rps_signed', false)
                    ->whereNull('approver_signed')
                    ->whereNotNull('permittee_signed')
                    ->whereNotNull('inspector_signed');
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('user_id', $userId)
                    ->whereNull('permittee_signed');
            });
    }

    public function scopeSignedDocsFor($query, $userId) {
        return $query->where(function ($query) use ($userId) {
                $query->where('inspector_id', $userId)
                    ->whereNotNull('inspector_signed');
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('approver_id', $userId)
                    ->whereNotNull('approver_signed');
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('rps_initial_id', $userId)
                    ->where('rps_signed', true);
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('user_id', $userId)
                    ->whereNotNull('permittee_signed');
            });
    }
}
