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

    public function ltpApplication() {
        return $this->belongsTo(LtpApplication::class);
    }

    public function scopePendingSignaturesFor($query, $userId) {
        return $query->where(function ($query) use ($userId) {
                $query->where('inspector_id', $userId)
                    ->where('inspector_signed', false)
                    ->where('permittee_signed', true);
            })->orWhere(function ($query)  use ($userId)  {
                $query->where('approver_id', $userId)
                    ->where('approver_signed', false)
                    ->where('permittee_signed', true)
                    ->where('inspector_signed', true);
            })
            ->orWhere(function ($query)  use ($userId)  {
                $query->where('user_id', $userId)
                    ->where('permittee_signed', false);
            });
    }
}
