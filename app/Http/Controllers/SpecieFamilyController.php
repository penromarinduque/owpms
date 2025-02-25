<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\SpecieFamily;

class SpecieFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specie_families = SpecieFamily::select('specie_families.*')->orderBy('family','ASC')->get();

        return view('admin.maintenance.speciefamilies.index', [
            'specie_families' => $specie_families
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.speciefamilies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'family' => 'required|max:150|unique:specie_families',
            'is_active_family' => 'required',
            'specie_class' => 'required',
        ], [
            'family.unique' => 'specie family already exist.'
        ]);
        SpecieFamily::create([
            'specie_class_id' => $request->specie_class,
            'family' => $request->family,
            'is_active_family' => $request->is_active_family
        ]);

        return Redirect::route('speciefamilies.index')->withInput()->with('success', 'Successfully saved!');
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
        $specie_family = SpecieFamily::find($id);
        return view('admin.maintenance.speciefamilies.edit', ['specie_family'=>$specie_family]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'family' => 'required|max:150',
            'is_active_family' => 'required',
        ]);
        SpecieFamily::find($id)->update([
            'family' => $request->input('family'),
            'is_active_family' => $request->input('is_active_family'),
        ]);

        return Redirect::route('speciefamilies.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
