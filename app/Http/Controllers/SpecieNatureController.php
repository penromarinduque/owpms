<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use App\Models\Specie;
use App\Models\SpecieNature;
use Illuminate\Http\Request;

class SpecieNatureController extends Controller
{
    //
    public function index(){
        $specie_natures = SpecieNature::all();
        return view('specie_natures.index', [
            'specie_natures' => $specie_natures
        ]);
    }

    public function create(){
        return view('specie_natures.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:specie_natures,name',
            "description" => 'required|max:255',
        ]);

        $specie_nature = new SpecieNature();
        $specie_nature->name = $request->name;
        $specie_nature->description = $request->description;
        $specie_nature->save();

        return redirect()->route('natureofspecies.index')->with('success', 'Specie Nature created successfully.');
    }

    public function edit($id){
        $specie_nature = SpecieNature::findOrFail($id);
        return view('specie_natures.edit', [
            'specie_nature' => $specie_nature
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255|unique:specie_natures,name,'.$id,
            "description" => 'required|max:255',
        ]);

        $specie_nature = SpecieNature::findOrFail($id);
        $specie_nature->name = $request->name;
        $specie_nature->description = $request->description;
        $specie_nature->save();

        return redirect()->route('natureofspecies.index')->with('success', 'Specie Nature updated successfully.');
    }

    public function destroy($id){
        $specie_nature = SpecieNature::findOrFail($id);

        // Check if any Specie is associated with this SpecieNature
        $associated_species_count = LtpApplication::where('specie_nature_id', $id)->count();
        if ($associated_species_count > 0) {
            return redirect()->route('natureofspecies.index')->with('error', 'Cannot delete Specie Nature because it is associated with existing ltp applications.');
        }

        $specie_nature->delete();
        return redirect()->route('natureofspecies.index')->with('success', 'Specie Nature deleted successfully.');
    }
}
