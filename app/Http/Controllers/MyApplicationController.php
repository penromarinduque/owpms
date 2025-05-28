<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Permittee;
use App\Models\LtpApplication;
use App\Models\LtpApplicationAttachment;
use App\Models\LtpApplicationProgress;
use App\Models\LtpApplicationSpecie;
use App\Models\LtpRequirement;
use App\Models\PermitteeSpecie;
use App\Models\Specie;
use App\Models\PaymentOrder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApplicationHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MyApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $_helper = new ApplicationHelper;
        $_ltp_application = new LtpApplication;

        $status = $request->status ?? 'draft';
        $ltp_application_query = LtpApplication::where([
            'permittee_id' => Auth::user()->wcp()->id,
            'application_status' => $status
        ])
        ->orderBy('created_at', 'DESC');

        $ltp_applications = $ltp_application_query->paginate(50);

        return view('permittee.myapplication.index', [
            'title' => 'My Applications',
            "ltp_applications" => $ltp_applications,
            "_helper" => $_helper,
            "_ltp_application" => $_ltp_application,
            "permittee" => auth()->user()->wcp()
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decryptString($id);

            $ltp_application = LtpApplication::find($id);

            Gate::authorize('view', $ltp_application);

            return view('permittee.myapplication.show', [
                "title" => "Application Details",
                "ltp_application" => $ltp_application,
                "_helper" => new ApplicationHelper
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
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

        Gate::authorize('update', $ltp_application);

        if(!$ltp_application) {
            return redirect()->back()->with('error', 'Application not found!');
        }

        $ltp_application_species = LtpApplicationSpecie::where("ltp_application_id", $ltp_application->id)->with([
            "specie.family", 
            "specie",
            "permitteeSpecies" => function (Builder $query) use ($ltp_application) {
                $query->where("permittee_id", $ltp_application->permittee_id);
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

            Gate::authorize('update', $ltp_application);

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
            $ltp_application = LtpApplication::where("id", $id)->delete();

            Gate::authorize('delete', $ltp_application);

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
        $_helper = new ApplicationHelper;
        $application_id = Crypt::decryptString($id);

        $ltp_application = LtpApplication::query()->with(['attachments'])->find($application_id);

        Gate::authorize('view', $ltp_application);

        $permittee = Permittee::find($ltp_application->permittee_id);

        return view('permittee.myapplication.preview', [
            '_helper' => $_helper,
            'title' => 'LTP Application',
            'wcp' => $ltp_application->permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wcp"),
            'wfp' => $ltp_application->permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wfp"),
            "ltp_application" => $ltp_application,
            "permittee" => $permittee
        ]);
    }

    public function printRequestLetter(Request $request, string $id)
    {
        $_permittee = new Permittee;
        $_helper = new ApplicationHelper;

        $id = Crypt::decryptString($id);

        $application = LtpApplication::where([
            "id" => $id
        ])
        ->with([
            "permittee",
            "ltpApplicationSpecies",
        ])
        ->first();

        Gate::authorize('view', $application);
        
        $wfp = $_permittee->getPermitteeWFP($application->permittee->user_id, "wfp");
        $wcp = $_permittee->getPermitteeWCP($application->permittee->user_id, "wcp");

        // return $wfp;

        return view('permittee.myapplication.printRequestLetter', [
            "_helper" => $_helper,
            "application" => $application,
            "wfp" => $wfp,
            "wcp" => $wcp
        ]);
    }

    public function submit(string $id) {
        return DB::transaction(function () use ($id) {
            $ltp_application = LtpApplication::find(Crypt::decryptString($id));

            Gate::authorize('update', $ltp_application);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }

            // Compliance Checks
            if(!LtpApplication::validateSpecies($ltp_application->id)) {
                return redirect()->back()->with('error', 'Application cannot have both endangered and non-endangered species! Endagered species must be submitted separately.');
            }

            if(!Permittee::validatePermit(Permittee::PERMIT_TYPE_WCP , Auth::user()->id) || !Permittee::validatePermit(Permittee::PERMIT_TYPE_WFP , Auth::user()->id)) {
                return redirect()->back()->with('error', 'Your WCP and/or WFP permit has expired or is not valid. Please renew your permit before submitting your application.');
            }

            if(!LtpApplication::validateRequirements($ltp_application->id)) {
                return redirect()->back()->with('error', 'Application does not have all required attachments!');
            }

            $ltp_application->application_status = LtpApplication::STATUS_SUBMITTED;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_SUBMITTED
            ]);

            return Redirect::route('myapplication.index')->with('success', 'Application successfully submitted!');
        });
    }

    public function requirements(string $id) {
        $id = Crypt::decryptString($id);

        $ltp_application = LtpApplication::find($id);

        Gate::authorize('update', $ltp_application);

        $requirements = LtpRequirement::where([
            "is_active_requirement" => 1
        ])
        ->get();

        $attachments = LtpApplicationAttachment::where([
            "ltp_application_id" => $id
        ])
        ->get();

        return view('permittee.myapplication.requirements', [
            "title" => "Application Requirements",
            "id" => $id,
            "requirements" => $requirements,
            "attachments" => $attachments
        ]);
    }

    public function resubmit(string $id) {
        return DB::transaction(function () use ($id) {
            $ltp_application = LtpApplication::find(Crypt::decryptString($id));

            Gate::authorize('update', $ltp_application);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }

            // Compliance Checks
            if(!LtpApplication::validateSpecies($ltp_application->id)) {
                return redirect()->back()->with('error', 'Application cannot have both endangered and non-endangered species! Endagered species must be submitted separately.');
            }

            if(!Permittee::validatePermit(Permittee::PERMIT_TYPE_WCP , Auth::user()->id) || !Permittee::validatePermit(Permittee::PERMIT_TYPE_WFP , Auth::user()->id)) {
                return redirect()->back()->with('error', 'Your WCP and/or WFP permit has expired or is not valid. Please renew your permit before submitting your application.');
            }

            if(!LtpApplication::validateRequirements($ltp_application->id)) {
                return redirect()->back()->with('error', 'Application does not have all required attachments!');
            }

            $ltp_application->application_status = LtpApplication::STATUS_RESUBMITTED;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_RESUBMITTED
            ]);

            return Redirect::route('myapplication.index')->with('success', 'Application successfully resubmitted!');
        });
    }

    public function uploadReceipt(Request $request, string $id) {
        try {
            $validator = Validator::make($request->all(), [
                'receipt_file' => 'required|mimes:jpg,jpeg,png,pdf|max:1024'
            ], [
                
            ]);

            if($validator->fails()) {
                session(['forward_url' => url()->current()]);
                return redirect()->back()->withErrors($validator, 'uploadReceipt')->withInput()->with('error', 'Failed to upload receipt');
            }

            $ltp_application_id = Crypt::decryptString($id);

            if(!$request->hasFile('receipt_file')) {
                return redirect()->back()->with('error', 'Please select a receipt!');
            }

            $ltp_application = LtpApplication::find($ltp_application_id);

            Gate::authorize('uploadReceipt', $ltp_application);

            $file = $request->file('receipt_file');

            $filename = 'receipt_' . $ltp_application_id . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('receipts', $filename, 'private');

            PaymentOrder::where([
                "ltp_application_id" => $ltp_application_id,
                "status" => PaymentOrder::STATUS_PAID
            ])->first()->update([
                "receipt_url" => $path
            ]);

            return redirect()->back()->with('success', 'Receipt uploaded successfully!');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // public function viewPaymentOrder(string $id) {
    //     $application_id = Crypt::decryptString($id);
    //     $application = LtpApplication::find($application_id);

    //     $payment_order = PaymentOrder::where([
    //         "ltp_application_id" => $application_id
    //     ])->first();

    //     $path = storage_path('app/private/' . $payment_order->document);
        
    //     return response()->file($path);
    // }
}
