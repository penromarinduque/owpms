<?php

namespace App\Http\Controllers;

use App\ElectronicSigratureTrait;
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
use App\Models\City;
use App\Models\Province;
use App\Models\Signature;
use App\Models\SpecieNature;
use App\Notifications\LtpApplicationSubmitted;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class MyApplicationController extends Controller
{
    use ElectronicSigratureTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $_helper = new ApplicationHelper;
        $_ltp_application = new LtpApplication;

        $status = $request->status ?? 'all';
        $category = $request->category ?? 'submitted';
        $sort_by = $request->has('sort_by') ? $request->sort_by : 'created_at';
        $sort_order = $request->has('sort_order') ? $request->sort_order : 'desc';

        $query = LtpApplication::query();

        if($request->has('application_no')) {
            $query->where([
                ['application_no', 'LIKE', '%' . $request->application_no . '%']
            ]);
        }

        if($status == 'all') {
            $ltp_applications = $query->where([
                'permittee_id' => Auth::user()->wcp()->id
            ])
            ->whereIn('application_status', $_helper->identifyApplicationStatusesByCategory($category))
            ->orderBy($sort_by, $sort_order)
            ->paginate(50);
        }
        else {
            $ltp_applications = $query->where([
                'permittee_id' => Auth::user()->wcp()->id,
                'application_status' => $status
            ])
            ->orderBy($sort_by, $sort_order)
            ->paginate(50);
        }

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
        $provinces = Province::query()->with([
            'region'
        ])->get();
        $permittee = auth()->user()->wcp();
        $countries = City::query()->select('country')->distinct()->get();
        return view('permittee.myapplication.draft.create', [
            'title' => 'Create New Application',
            "provinces" => $provinces,
            "specie_natures" => SpecieNature::all(),
            "species" => $permittee->permittee_species,
            "countries" => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "transport_date" => "required|date|after_or_equal:today",
            "destination" => "nullable|exists:provinces,id",
            "purpose" => "required|string",
            "specie_id" => "required|array",
            "quantity" => "required|array",
            "specie_nature_id" => "required|exists:specie_natures,id",
            "destination_city" => "required_if:purpose,export|exists:cities,id"
        ], [

        ], [
            "transport_date" => "Date of Transport",
            "specie_nature_id" => "Nature of Species"
        ]);

        if (!$request->has('specie_id')) {
            return redirect()->back()->with('error', 'Please select at least one species.');
        }

        if(!LtpApplication::validateSpeciesUponCreation($request->specie_id)){
            return redirect()->back()->with('error', 'Endangered species must be created separately.');
        }
        
        return DB::transaction(function () use ($request) {

            
            $year = date('Y');  
            $latest = LtpApplication::where('year', $year)->orderBy('no', 'DESC')->first();
            $no = $latest ? $latest->no + 1 : 1;
            $application_no = $year.date('md').str_pad($no, 5, '0', STR_PAD_LEFT);
            $ltp_application = LtpApplication::create([
                'permittee_id' => Auth::user()->wcp()->id, 
                'application_no' => $application_no, 
                'application_status' => LtpApplication::STATUS_DRAFT, 
                'application_date' => now(), 
                'transport_date' =>  $request->transport_date, 
                'export_destination' => $request->purpose == 'export' ? $request->destination_city : null,
                'purpose' => $request->purpose, 
                'specie_nature_id' => $request->specie_nature_id,
                'destination' => $request->purpose == 'local sale' ? $request->destination : ($request->purpose == 'export' ? 1: auth()->user()->personalInfo->barangay->municipality->province_id), 
                'digital_signature' => NULL,
                'year' => $year,
                'no' => $no,
            ]);

            if ($ltp_application->id) {
                
                foreach ($request->specie_id as $key => $value) {
                    LtpApplicationSpecie::create([
                        'ltp_application_id' => $ltp_application->id, 
                        'specie_id' => $request->specie_id[$key], 
                        'quantity' => $request->quantity[$key], 
                        'is_endangered' => 0
                    ]);
                }
            }
            return Redirect::route('myapplication.index', ['status' => 'draft', 'category' => 'draft'])->with('success', 'Successfully saved!');
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

        $countries = City::query()->select('country')->distinct()->get();
        $provinces = Province::query()->with([
            'region'
        ])->get();

        $ltp_application = LtpApplication::where([
            "id" => $id,
            "permittee_id" => Auth::user()->wcp()->id
        ])->first();

        Gate::authorize('update', $ltp_application);

        if(!$ltp_application) {
            return redirect()->back()->with('error', 'Application not found!');
        }

        $ltp_application_species = LtpApplicationSpecie::where("ltp_application_id", $ltp_application->id)
            ->join("species", "ltp_application_species.specie_id", "=", "species.id")
            ->with([
                "specie.family",
                "specie",
                "permitteeSpecies" => function (Builder $query) use ($ltp_application) {
                    $query->where("permittee_id", $ltp_application->permittee_id);
                }
            ])
            ->orderBy("species.specie_name", "ASC")
            ->get();
        $permittee = auth()->user()->wcp();

        return view('permittee.myapplication.edit', [
            'title' => 'Edit Application',
            "ltp_application" => $ltp_application,
            "ltp_application_species" => $ltp_application_species,
            "provinces" => $provinces,
            "countries" => $countries,
            "specie_natures" => SpecieNature::all(),
            "species" => $permittee->permittee_species
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
                // "transport_date" => "required|date",
                "purpose" => "required|string",
                "specie_id" => "required|array",
                "quantity" => "required|array",
                "specie_nature_id" => "required|exists:specie_natures,id",
            ],[

            ],[
                "transport_date" => "Application Date",
                "specie_nature_id" => "Nature of Species"
            ]);

            $ltp_application = LtpApplication::find($id);

            Gate::authorize('update', $ltp_application);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }

            $ltp_application->transport_date = now();
            $ltp_application->purpose = $request->purpose;
            $ltp_application->application_status = LtpApplication::STATUS_DRAFT;
            $ltp_application->destination = $request->purpose == 'local sale' ? $request->destination : ($request->purpose == 'export' ? 1: null);
            $ltp_application->specie_nature_id = $request->specie_nature_id;
            $ltp_application->export_destination = $request->purpose == 'export' ? $request->destination_city : null;
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

            return Redirect::route('myapplication.index', ['status' => 'draft', 'category' => 'draft'])->with('success', 'Application successfully updated!');            
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return DB::transaction(function () use ($id) {
            $ltp_application = LtpApplication::find($id);

            Gate::authorize('delete', $ltp_application);
            
            $ltp_application->delete();
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

        // $systemGeneratedDocuments = [
        //     "request_letter" => Storage::disk('s3')->exists($ltp_application->request_letter_path) ? Storage::disk('s3')->temporaryUrl("request_l", now()->addMinutes(30)) : null,
        //     "billing_statement" => null,
        //     "oop" => null,
        //     "inspection_report" => null,
        //     "permit" => null
        // ];

        return view('permittee.myapplication.preview', [
            '_helper' => $_helper,
            'title' => 'LTP Application',
            'wcp' => $ltp_application->permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wcp"),
            'wfp' => $ltp_application->permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wfp"),
            "ltp_application" => $ltp_application,
            "permittee" => $permittee
            // "systemGeneratedDocuments" => $systemGeneratedDocuments
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

        $path = "request_letters/{$application->id}.pdf";

        if (!Storage::disk('s3')->exists($path)) {
            abort(404);
        }

        // Streams the file piece-by-piece from S3 directly to the user
        return Storage::disk('s3')->response($path, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);
    }

    public function submit(Request $request, string $id) {
        $_user = new User;
        $_helper = new ApplicationHelper;
        $_permittee = new Permittee;

        // $validator = Validator::make($request->all(), [
        //     'attach_signature_check' => 'required',
        //     'document' => 'required_if_declined:attach_signature_check|mimes:pdf',
        // ]);

        // if($validator->fails()) {
        //     return redirect()->back()->withErrors($validator, 'submitApplication')->withInput()->with('error', 'Failed to submit application.');
        // }

        return DB::transaction(function () use ($id, $request, $_user, $_helper, $_permittee) {
            $ltp_application = LtpApplication::find(Crypt::decryptString($id));

            Gate::authorize('submit', $ltp_application);

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

            $wfp = $_permittee->getPermitteeWFP($ltp_application->permittee->user_id, "wfp");
            $wcp = $_permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wcp");
            $signature = Signature::where("user_id", auth()->user()->id)->first();
            if(!$signature) {
                return redirect()->back()->with('error', 'You have not uploaded your signature!');
            }

            $eSignatureResponse = $this->getElectronicSignature(
                $ltp_application->permittee->user->personalInfo->first_name . " " . $ltp_application->permittee->user->personalInfo->middle_name . " " . $ltp_application->permittee->user->personalInfo->last_name,
                "Permittee",
                Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30)),
                $ltp_application->application_no,
                "5",
                $ltp_application->id,
                auth()->user()->id,
                null
            );
            
            $signatureImage = 'data:image/png;base64,' . base64_encode($eSignatureResponse->body());

            Pdf::view("pdfs.request-letter", [
                "_helper" => $_helper,
                "application" => $ltp_application,
                "wfp" => $wfp,
                "wcp" => $wcp,
                "signatureUrl" => $signatureImage
            ])
            ->waitUntilReady()
            ->format(Format::A4)
            ->scale(0.9)
            ->margins(0, 0.4, 0.5, 0.3, Unit::Inch)
            ->disk("s3")
            ->save('request_letters/'.$ltp_application->id.'.pdf');

            $ltp_application->application_status = LtpApplication::STATUS_SUBMITTED;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_SUBMITTED,
                "description" => "Application has been submitted."
            ]);

            Notification::send($_user->getAllInspectors(), new LtpApplicationSubmitted($ltp_application));

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
            "attachments" => $attachments,
            "ltp_application" => $ltp_application
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
                "status" => LtpApplicationProgress::STATUS_RESUBMITTED,
                "description" => 'Application has been resubmitted'
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

            $path = $file->storeAs('receipts', $filename);

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

    public function cancel(string $id) {
        return DB::transaction(function () use ($id) {
            $ltp_application = LtpApplication::find(Crypt::decryptString($id));

            Gate::authorize('cancelApplication', $ltp_application);

            if(!$ltp_application) {
                return redirect()->back()->with('error', 'Application not found!');
            }

            $ltp_application->application_status = LtpApplication::STATUS_DRAFT;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_DRAFT,
                "description" => 'Application has been cancelled'
            ]);

            return Redirect::route('myapplication.index')->with('success', 'Application successfully cancelled!');
        });
    }
}
