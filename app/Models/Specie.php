<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Specie extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['specie_type_id', 'specie_class_id', 'specie_family_id', 'specie_name', 'is_present', 'local_name', 'wing_span', 'conservation_status', 'color_description', 'food_plant', 'is_active_specie'];

    const C_STATUS_RARE = "rare";
    const C_STATUS_THREATENED = "threatened";
    const C_STATUS_VULNERABLE = "vulnerable";
    const C_STATUS_ENDANGERED = "endangered";

    public function family(){
        return $this->belongsTo(SpecieFamily::class, 'specie_family_id', 'id');
    }

    public function getSpecies()
    {
        $species = $this->select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family')
            ->leftJoin('specie_types', 'specie_types.id', 'species.specie_type_id')
            ->leftJoin('specie_classes', 'specie_classes.id', 'species.specie_class_id')
            ->leftJoin('specie_families', 'specie_families.id', 'species.specie_family_id')
            ->get();
        return $species;
    }

    public function searchSpecies($searchkey)
    {
        $species = $this->select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family', 'species.specie_name as text')
            ->leftJoin('specie_types', 'specie_types.id', 'species.specie_type_id')
            ->leftJoin('specie_classes', 'specie_classes.id', 'species.specie_class_id')
            ->leftJoin('specie_families', 'specie_families.id', 'species.specie_family_id')
            ->where(function ($qry) use ($searchkey) {
                $qry->where('species.specie_name','LIKE',"%".$searchkey."%")
                    ->orWhere('species.local_name','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_types.specie_type','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_classes.specie_class','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_families.family','LIKE',"%".$searchkey."%");
            });
        return $species;
    }

    public function getSpecie($id)
    {
        $specie = $this->select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family')
            ->leftJoin('specie_types', 'specie_types.id', 'species.specie_type_id')
            ->leftJoin('specie_classes', 'specie_classes.id', 'species.specie_class_id')
            ->leftJoin('specie_families', 'specie_families.id', 'species.specie_family_id')
            ->where('species.id', $id)
            ->first();
        return $specie;
    }

    public function conservationStatus($stat=null)
    {
        $arr_constats = ['rare'=>'Rare', 'threatened'=>'Threatened', 'vulnerable'=>'Vulnerable'];
        if ($stat!=null) {
            foreach ($arr_constats as $key => $value) {
                if ($key==$stat) {
                    return $value;
                }
            }
        } else {
            return $arr_constats;
        }
    }

    public function topExportedSpecie() {
        $data =  DB::table('species')
            ->select([
                'ltp_application_species.specie_id',
                'species.specie_name',
                'species.local_name',
                DB::raw('SUM(ltp_application_species.quantity) as exports'),
            ])
            ->leftJoin('ltp_application_species', 'ltp_application_species.specie_id', '=', 'species.id')
            ->leftJoin(DB::raw('(
                SELECT DISTINCT ltp_application_id
                FROM ltp_application_progress
                WHERE status = "released"
            ) as progress'), 'ltp_application_species.ltp_application_id', '=', 'progress.ltp_application_id')
            ->groupBy(
                'ltp_application_species.specie_id',
                'species.specie_name',
                'species.local_name'
            )
            ->having('exports', '>', 0)
            ->orderByDesc('exports')
            ->limit(10)
            ->get();

        return $data;
    }

    public function totalExport() {
        // return DB::table('ltp_application_species')
        // ->join('ltp_application_progress', 'ltp_application_species.ltp_application_id', '=', 'ltp_application_progress.ltp_application_id')
        // ->where('ltp_application_progress.status', 'released')
        // ->sum('ltp_application_species.quantity');

        return DB::table('ltp_application_species')
            ->join(DB::raw('(
                SELECT DISTINCT ltp_application_id
                FROM ltp_application_progress
                WHERE status = "released"
            ) as released_apps'), function ($join) {
                $join->on('ltp_application_species.ltp_application_id', '=', 'released_apps.ltp_application_id');
            })
            ->sum('ltp_application_species.quantity');
    }
}
