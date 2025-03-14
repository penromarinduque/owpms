<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Specie extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['specie_type_id', 'specie_class_id', 'specie_family_id', 'specie_name', 'is_present', 'local_name', 'wing_span', 'conservation_status', 'color_description', 'food_plant', 'is_active_specie'];

        
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
        $species = $this->select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family')
            ->leftJoin('specie_types', 'specie_types.id', 'species.specie_type_id')
            ->leftJoin('specie_classes', 'specie_classes.id', 'species.specie_class_id')
            ->leftJoin('specie_families', 'specie_families.id', 'species.specie_family_id')
            ->where(function ($qry) use ($searchkey) {
                $qry->where('species.specie_name','LIKE',"%".$searchkey."%")
                    ->orWhere('species.local_name','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_types.specie_type','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_classes.specie_class','LIKE',"%".$searchkey."%")
                    ->orWhere('specie_families.family','LIKE',"%".$searchkey."%");
            })
            ->get();
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
}
