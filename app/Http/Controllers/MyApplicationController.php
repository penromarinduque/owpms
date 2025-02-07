<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Permittee;
use App\Models\LtpApplication;
use App\Models\LtpApplicationSpecie;
use App\Models\Specie;

class MyApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('permittee.myapplication.index', [
            'title' => 'My Applications'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permittee.myapplication.draft.create', [
            'title' => 'Create New Application'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        try {
            $ltp_application = LtpApplication::create([
                'permittee_id' => Auth::user()->id, 
                'application_id' => '33213-12', 
                'application_status' => 'draft', 
                'application_date' => date('Y-m-d'), 
                'transport_date' => $request->transport_date, 
                'purpose' => $request->purpose, 
                'destination' => $request->city.', '.$request->state.', '.$request->country, 
                'digital_signature' => NULL
            ]);
            if ($ltp_application->id) {
                if ($request->specie_id) {
                    foreach ($request->specie_id as $key => $value) {
                        LtpApplicationSpecie::create([
                            'ltp_application_id' => $ltp_application->id, 
                            'specie_id' => $request->specie_id[$key], 
                            'quantity' => $request->quantity[$key], 
                            'is_endangered' => 0
                        ]);
                    }
                }
            }
            return Redirect::route('myapplication.index')->with('success', 'Successfully saved!');
        } catch (\Exception $e) {
            return Redirect::route('myapplication.create')->with('errors', $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ajaxGetSpecies(Request $request)
    {
        $_specie = new Specie;

        try {
            $searchkey = $request->searchkey;
            $species = $_specie->searchSpecies($searchkey);
            $str = "";
            if (!empty($species)) {
                foreach ($species as $specie) {
                  $str .= '<div class="result-item" data-id="'.$specie->id.'" data-scientifcname="'.$specie->specie_name.'" data-commonname="'.$specie->local_name.'" data-family="'.$specie->family.'">Scientific Name: <b>'.$specie->specie_name.'</b>, Local Name: <b>'.$specie->local_name.'</b>, Family Name: <b>'.$specie->family.'</b></div>';
                }
            }

            return $str;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function preview(Request $request)
    {
        $_permittee = new Permittee;

        $user_id = $request->user_id;

        $validated = $request->validate([
            'transport_date' => 'required|date',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'purpose' => 'required|string',
            'species' => 'nullable|string',
            'specie_id' => 'array|required',
            'quantity' => 'array|required',
            'quantity.*' => 'required|integer|min:1', // Validate each quantity
        ]);

        $permittee_wfp = $_permittee->getPermitteeWFP($user_id, 'wfp');

        $ltp_species = [];
        if (!empty($request->specie_id)) {
            $species = Specie::select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family')
                ->leftJoin('specie_types', 'specie_types.id', 'species.specie_type_id')
                ->leftJoin('specie_classes', 'specie_classes.id', 'species.specie_class_id')
                ->leftJoin('specie_families', 'specie_families.id', 'species.specie_family_id')
                ->whereIn('species.id', $request->specie_id)
                ->get();

            foreach ($request->specie_id as $key => $value) {
                if (!empty($species)) {
                    foreach ($species as $specie) {
                        if ($request->specie_id[$key] == $specie->id) {
                            $arr_data = (object) ['specie_name' => $specie->specie_name, 'local_name' => $specie->local_name, 'family_name' => $specie->family, 'quantity' => $request->quantity[$key]];
                            array_push($ltp_species, $arr_data);
                        }
                    }
                }
            }
        }


        return view('permittee.myapplication.preview', [
            'data' => $validated,
            'permittee_wfp' => $permittee_wfp,
            'ltp_species' => $ltp_species,
        ]);
    }
}


// ILV0v4q8