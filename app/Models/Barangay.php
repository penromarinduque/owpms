<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = ['municipality_id', 'barangay'];

    public function municipality(){
        return $this->belongsTo(Municipality::class);
    }

    public function getBarangays($province_id = 23)
    {
        $barangays = Barangay::select('barangays.id', 'barangays.barangay_name', 'municipalities.municipality_name', 'provinces.province_name')
            ->join('municipalities', 'municipalities.id', 'barangays.municipality_id')
            ->join('provinces', 'provinces.id', 'municipalities.province_id')
            ->where('municipalities.province_id', $province_id)
            ->orderBy('municipalities.municipality_name', 'ASC')
            ->orderBy('barangays.barangay_name', 'ASC')
            ->get();
        return $barangays;
    }
}
