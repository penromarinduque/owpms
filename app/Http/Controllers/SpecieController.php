<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Models\Specie;
use App\Models\SpecieType;
use App\Models\SpecieClass;
use App\Models\SpecieFamily;

class SpecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $_specie = new Specie;

        // return $_specie->searchSpecies('Butterfly');
        $species = Specie::select('species.*', 'specie_types.specie_type', 'specie_classes.specie_class', 'specie_families.family')
            ->join('specie_types', 'specie_types.id', 'species.specie_type_id')
            ->join('specie_classes', 'specie_classes.id', 'species.specie_class_id')
            ->join('specie_families', 'specie_families.id', 'species.specie_family_id')
            ->get();
        return view('admin.species.index', [
            'species' => $species
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specie_types = SpecieType::where('is_active_type',1)->get();
        $specie_classes = SpecieClass::where('is_active_class',1)->get();
        $specie_families = SpecieFamily::where('is_active_family',1)->get();

        return view('admin.species.create', [
            'specie_types' => $specie_types,
            'specie_classes' => $specie_classes,
            'specie_families' => $specie_families,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'specie_type_id' => 'required',
            'specie_class_id' => 'required',
            'specie_family_id' => 'required',
            'specie_name' => 'required|max:150|unique:species',
            'local_name' => 'required|max:150|unique:species',
            'conservation_status' => 'required',
            'color_description' => 'required',
            'food_plant' => 'required',
        ], [
            'specie_type_id.required' => 'specie type is required.',
            'specie_class_id.required' => 'specie class is required.',
            'specie_family_id.required' => 'specie family is required.',
            'specie_name.unique' => 'specie name already exist.',
            'local_name.unique' => 'local name already exist.',
        ]);               
        $present = ($request->input('present')) ? 1 : 0;

        Specie::create([
            'specie_type_id' => $request->input('specie_type_id'),
            'specie_class_id' => $request->input('specie_class_id'),
            'specie_family_id' => $request->input('specie_family_id'),
            'specie_name' => $request->input('specie_name'),
            'is_present' => $present,
            'local_name' => $request->input('local_name'),
            'wing_span' => $request->input('wing_span'),
            'conservation_status' => $request->input('conservation_status'),
            'color_description' => $request->input('color_description'),
            'food_plant' => $request->input('food_plant'),
            'is_active_specie' => 1,
        ]);
        return Redirect::route('species.index')->with('success', 'Successfully saved!');
        // try {
        // } catch (\Exception $e) {
            // Redirect::back()->with('error', 'Oops! Something went wrong. Please try again.');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $_specie = new Specie;

        $specie_id = Crypt::decrypt($id);
        $specie = $_specie->getSpecie($specie_id);

        return view('admin.species.show', [
            'specie' => $specie
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specie_id = Crypt::decrypt($id);
        $specie = Specie::find($specie_id);
        if (!empty($specie)) {
            $specie_types = SpecieType::where('is_active_type',1)->orWhere('id',$specie->specie_type_id)->get();
            $specie_classes = SpecieClass::where('is_active_class',1)->orWhere('id',$specie->specie_class_id)->get();
            $specie_families = SpecieFamily::where('is_active_family',1)->orWhere('id',$specie->specie_family_id)->get();

            return view('admin.species.edit', [
                'specie_types' => $specie_types,
                'specie_classes' => $specie_classes,
                'specie_families' => $specie_families,
                'specie_id' => $specie_id,
                'specie' => $specie,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $specie_id = Crypt::decrypt($id);

        $validated = $request->validate([
            'specie_type_id' => 'required',
            'specie_class_id' => 'required',
            'specie_family_id' => 'required',
            'specie_name' => 'required|max:150',
            'local_name' => 'required|max:150',
            'conservation_status' => 'required',
            'color_description' => 'required',
            'food_plant' => 'required',
        ], [
            'specie_type_id.required' => 'specie type is required.',
            'specie_class_id.required' => 'specie class is required.',
            'specie_family_id.required' => 'specie family is required.',
        ]);               
        $present = ($request->input('present')) ? 1 : 0;

        Specie::find($specie_id)->update([
            'specie_type_id' => $request->input('specie_type_id'),
            'specie_class_id' => $request->input('specie_class_id'),
            'specie_family_id' => $request->input('specie_family_id'),
            'specie_name' => $request->input('specie_name'),
            'is_present' => $present,
            'local_name' => $request->input('local_name'),
            'wing_span' => $request->input('wing_span'),
            'conservation_status' => $request->input('conservation_status'),
            'color_description' => $request->input('color_description'),
            'food_plant' => $request->input('food_plant'),
            'is_active_specie' => 1,
        ]);
        return Redirect::route('species.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ajaxUpdateStatus(Request $request)
    {
        $specie_id = Crypt::decrypt($request->specie_id);
        $is_active_specie = $request->is_active_specie;

        $update = Specie::find($specie_id)->update(['is_active_specie'=>$is_active_specie]);
        if ($update) {
            return Response()->json(['success'=>'Updated']);
        } else {
           return Response()->json(['failed'=>'Failed']); 
        }
    }
}
