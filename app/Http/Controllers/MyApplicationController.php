<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Permittee;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\LtpApplicationSpecie;
use App\Models\PermitteeSpecie;
use App\Models\Specie;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MyApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'draft';
        $ltp_applications = LtpApplication::where([
            'permittee_id' => Auth::user()->wcp()->id,
            'application_status' => $status
        ])->paginate(50);
        return view('permittee.myapplication.index', [
            'title' => 'My Applications',
            "ltp_applications" => $ltp_applications
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
        // try {
        return DB::transaction(function () use ($request) {
            $request->validate([
                "transport_date" => "required|date",
                "city" => "required|string",
                "state" => "required|string",
                "country" => "required|string",
                "purpose" => "required|string",
                "specie_id" => "required|array",
                "quantity" => "required|array"
            ]);
            
            $ltp_application = LtpApplication::create([
                'permittee_id' => Auth::user()->wcp()->id, 
                'application_no' => date('YmdHis').rand(100,999), 
                'application_status' => LtpApplication::STATUS_DRAFT, 
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
        });
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', $e);
        // }
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
        $id = Crypt::decryptString($id);

        $ltp_application = LtpApplication::where([
            "id" => $id,
            "permittee_id" => Auth::user()->wcp()->id
        ])->first();

        if(!$ltp_application) {
            return redirect()->back()->with('error', 'Application not found!');
        }

        $ltp_application_species = LtpApplicationSpecie::where("ltp_application_id", $ltp_application->id)->with([
            "specie.family", 
            "specie",
            "permitteeSpecies" => function (Builder $query) use ($ltp_application) {
                $query->where("permittee_id", Auth::user()->wcp()->id)
                ->first();
            }
        ])->get();

        return view('permittee.myapplication.edit', [
            'title' => 'Edit Application',
            "ltp_application" => $ltp_application,
            "ltp_application_species" => $ltp_application_species
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return DB::transaction(function () use ($request, $id) {
            $request->validate([
                "transport_date" => "required|date",
                "purpose" => "required|string",
                "specie_id" => "required|array",
                "quantity" => "required|array"
            ]);

            $ltp_application = LtpApplication::find($id);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }

            $ltp_application->transport_date = $request->transport_date;
            $ltp_application->purpose = $request->purpose;
            $ltp_application->save();

            LtpApplicationSpecie::where("ltp_application_id", $ltp_application->id)->delete();

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

            return Redirect::route('myapplication.index')->with('success', 'Application successfully updated!');            
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return DB::transaction(function () use ($id) {
            LtpApplication::where("id", $id)->delete();
            LtpApplicationSpecie::where("ltp_application_id", $id)->delete();
            return Redirect::route('myapplication.index')->with('success', 'Application successfully deleted!');
        });
    }

    public function ajaxGetSpecies(Request $request)
    {
        $_specie = new Specie;

        try {
            $searchkey = $request->searchkey;

            $permittee = Permittee::where([
                "user_id" => Auth::user()->id,
                "permit_type" => "wcp"
            ])->first();
            $permittee_species = PermitteeSpecie::where("permittee_id", $permittee->id)->pluck("specie_id");
            $species = $_specie->searchSpecies($searchkey)->whereIn("species.id", $permittee_species)->get();
            $str = "";
            if (!empty($species)) {
                foreach ($species as $specie) {
                    $permittee_specie = PermitteeSpecie::where("permittee_id", $permittee->id)->where("specie_id", $specie->id)->first();
                    $str .= '<div class="result-item" data-maxqty="'.($permittee_specie ? $permittee_specie->quantity : 0).'" data-id="'.$specie->id.'" data-scientifcname="'.$specie->specie_name.'" data-commonname="'.$specie->local_name.'" data-family="'.$specie->family.'" >Scientific Name: <b>'.$specie->specie_name.'</b>, Local Name: <b>'.$specie->local_name.'</b>, Family Name: <b>'.$specie->family.'</b></div>';
                }
            }

            return $str;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function preview(Request $request, string $id)
    {
        $_permittee = new Permittee;

        $id = Crypt::decryptString($id);

        $application = LtpApplication::where([
            "id" => $id
        ])
        ->with([
            "permittee",
            "ltpApplicationSpecies",
        ])
        ->first();
        
        $wfp = $_permittee->getPermitteeWFP($application->permittee->user_id, "wfp");
        $wcp = $_permittee->getPermitteeWCP($application->permittee->user_id, "wcp");

        // return $wfp;

        return view('permittee.myapplication.preview', [
            "application" => $application,
            "wfp" => $wfp,
            "wcp" => $wcp
        ]);
    }

    public function submit(string $id) {
        return DB::transaction(function () use ($id) {
            $ltp_application = LtpApplication::find($id);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }
            $ltp_application->application_status = LtpApplication::STATUS_SUBMITTED;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "status" => LtpApplicationProgress::STATUS_SUBMITTED
            ]);

            return Redirect::route('myapplication.index')->with('success', 'Application successfully submitted!');
        });
    }
}
