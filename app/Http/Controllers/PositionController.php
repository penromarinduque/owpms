<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Models\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::all();
        return view('admin.maintenance.positions.index', [
            'positions' => $positions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|max:150|unique:positions',
        ], [
            'position.unique' => 'The position was already exist.'
        ]);
        Position::create([
            'position' => $request->input('position'),
            'description' => $request->input('description'),
        ]);

        return Redirect::route('positions.index')->withInput()->with('success', 'Successfully saved!');
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
        $position = Position::find($id);
        return view('admin.maintenance.positions.edit', [
            'position' => $position,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'position' => 'required|max:150',
        ]);
        Position::find($id)->update([
            'position' => $request->input('position'),
            'description' => $request->input('description'),
        ]);

        return Redirect::route('positions.index')->withInput()->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
