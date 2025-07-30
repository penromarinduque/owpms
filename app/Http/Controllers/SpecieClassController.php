<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\SpecieClass;
use App\Models\SpecieFamily;
use App\Models\SpecieType;

class SpecieClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specie_classes = SpecieClass::all();

        return view('admin.maintenance.specieclasses.index', [
            'specie_classes' => $specie_classes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.specieclasses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'specie_class' => 'required|max:150|unique:specie_classes',
            'is_active_class' => 'required',
            // 'specie_type' => 'required',
        ], [
            // 'specie_type.unique' => 'specie class already exist.'
        ]);

        SpecieClass::create([
            // 'specie_type_id' => $request->specie_type,
            'specie_class' => $request->specie_class,
            'is_active_class' => $request->is_active_class
        ]);

        return Redirect::route('specieclasses.index')->withInput()->with('success', 'Successfully saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $specie_class = SpecieClass::with("specieType")->find($id);
        $specie_types = SpecieType::where("is_active_type", 1)->get();
        return view('admin.maintenance.specieclasses.edit', [
            'specie_class' => $specie_class,
            'specie_types' => $specie_types
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'specie_class' => 'required|max:150',
            'is_active_class' => 'required',
            // 'specie_type' => 'required',
        ]);
        SpecieClass::find($id)->update([
            'specie_class' => $request->input('specie_class'),
            // 'specie_type_id' => $request->input('specie_type'),
            'is_active_class' => $request->input('is_active_class'),
        ]);

        return Redirect::route('specieclasses.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function apiSearch(Request $request) {
        $search = $request->keyword;
        $specie_classes = SpecieClass::selectRaw("*, specie_class as text")->where('specie_class', 'like', '%' . $search . '%')
            ->where("is_active_class", 1)
            ->get();
        return response()->json($specie_classes);
    }

    public function apiGetByType(Request $request) {
        $specie_type_id = $request->specie_type_id;
        $specie_classes = SpecieClass::where('specie_type_id', $specie_type_id)->get();
        return response()->json($specie_classes);
    }

    
}
