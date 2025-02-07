<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\SpecieType;

class SpecieTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specie_types = SpecieType::all();

        return view('admin.maintenance.specietypes.index', [
            'specie_types' => $specie_types
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.specietypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'specie_type' => 'required|max:150|unique:specie_types',
            'is_active_type' => 'required',
        ], [
            'specie_type.unique' => 'specie type already exist.'
        ]);
        SpecieType::create($validated);

        return Redirect::route('specietypes.index')->withInput()->with('success', 'Successfully saved!');
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
    public function edit($id)
    {
        $specie_type = SpecieType::find($id);
        return view('admin.maintenance.specietypes.edit', ['specie_type'=>$specie_type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'specie_type' => 'required|max:150',
            'is_active_type' => 'required',
        ]);
        SpecieType::find($id)->update([
            'specie_type' => $request->input('specie_type'),
            'is_active_type' => $request->input('is_active_type'),
        ]);

        return Redirect::route('specietypes.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
