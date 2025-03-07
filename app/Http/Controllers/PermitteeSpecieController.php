<?php

namespace App\Http\Controllers;

use App\Models\Permittee;
use App\Models\PermitteeSpecie;
use App\Models\Specie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PermitteeSpecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $id)
    {
        //
        $permittee_id = Crypt::decryptString($id);
        $permittee_species = PermitteeSpecie::where("permittee_id", $permittee_id)->with([
            "specie"
        ])->paginate(20);
        return view("admin.permittee.permitteespecies.index", [
            "permittee_species" => $permittee_species
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("admin.permittee.permitteespecies.create" ,[]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "permittee_id" => "required",
            "specie_id" => "required",
            "quantity" => "required",
        ]);

        if(PermitteeSpecie::where("permittee_id", $request->permittee_id)->where("specie_id", $request->specie_id)->exists()){
            return redirect()->back()->with("error", "Permittee Specie already exists");
        }

        PermitteeSpecie::create([
            "permittee_id" => $request->permittee_id,
            "specie_id" => $request->specie_id,
            "quantity" => $request->quantity
        ]);

        return redirect()->back()->with("success", "Permittee Specie created successfully");
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
    public function update(Request $request)
    {
        //
        if($request->quantity == 0 || $request->quantity == null || $request->id == ""){
            return redirect()->back()->with("error", "Quantity is required");
        }

        PermitteeSpecie::where("id", $request->id)->update([
            "quantity" => $request->quantity
        ]);

        return redirect()->back()->with("success", "Permittee Specie updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $id = Crypt::decryptString($id);
        //  add validations
        PermitteeSpecie::where("id", $id)->delete();
        return redirect()->back()->with("success", "Permittee Specie deleted successfully");
    }

    public function ajaxGetSpecies(Request $request){
        $_specie = new Specie;
        try {
            $searchkey = $request->search;
            $species = $_specie->searchSpecies($searchkey)->paginate(20);
            return $species;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ajaxGetPermittees(Request $request){
        $_permittee = new Permittee;
        try {
            $searchkey = $request->search;
            $permittees = $_permittee->searchWcpPermittee($searchkey)->paginate(20);
            return $permittees;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
