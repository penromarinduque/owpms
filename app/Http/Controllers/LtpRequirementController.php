<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\LtpRequirement;

class LtpRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ltprequirements = LtpRequirement::all();
        return view('admin.maintenance.ltprequirements.index', [
            'ltprequirements' => $ltprequirements,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.ltprequirements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'requirement_name' => 'required|max:150',
                'is_mandatory' => 'required',
            ]);
            LtpRequirement::create([
                'requirement_name' => $request->input('requirement_name'),
                'is_mandatory' => $request->input('is_mandatory'),
                'is_active_requirement' => 1,
            ]);

            return Redirect::route('ltprequirements.index')->withInput()->with('success', 'Successfully saved!');
        } catch (\Exception $e) {
            return Redirect::route('ltprequirements.create')->withInput()->with('error', 'Something went wrong. Please try again!');
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
    public function edit($id)
    {
        $ltprequirement = LtpRequirement::find($id);
        return view('admin.maintenance.ltprequirements.edit', [
            'ltprequirement' => $ltprequirement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'requirement_name' => 'required|max:150',
                'is_mandatory' => 'required',
                'is_active_requirement' => 'required',
            ], [
                'is_active_requirement.required' => 'The status options is required.'
            ]);
            LtpRequirement::find($id)->update([
                'requirement_name' => $request->input('requirement_name'),
                'is_mandatory' => $request->input('is_mandatory'),
                'is_active_requirement' => $request->input('is_active_requirement'),
            ]);

            return Redirect::route('ltprequirements.index')->withInput()->with('success', 'Successfully updated!');
        } catch (\Exception $e) {
            return Redirect::route('ltprequirements.edit',[$id])->withInput()->with('error', 'Something went wrong. Please try again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
