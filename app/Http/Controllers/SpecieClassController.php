<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\SpecieClass;

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
        $validated = $request->validate([
            'specie_class' => 'required|max:150|unique:specie_classes',
            'is_active_class' => 'required',
        ], [
            'specie_type.unique' => 'specie class already exist.'
        ]);
        SpecieClass::create($validated);

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
        $specie_class = SpecieClass::find($id);
        return view('admin.maintenance.specieclasses.edit', ['specie_class'=>$specie_class]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'specie_class' => 'required|max:150',
            'is_active_class' => 'required',
        ]);
        SpecieClass::find($id)->update([
            'specie_class' => $request->input('specie_class'),
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
}
