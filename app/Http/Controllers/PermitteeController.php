<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Mail\AccountCreated;
use App\Models\PersonalInfo;
use App\Models\Permittee;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\User;
use App\Models\WildlifeFarm;

class PermitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permittees = User::select('users.id', 'users.username', 'users.email', 'users.is_active_user', 'personal_infos.last_name', 'personal_infos.first_name', 'personal_infos.middle_name', 'personal_infos.gender', 'personal_infos.email', 'personal_infos.contact_no', 'personal_infos.barangay_id', 'barangays.barangay_name', 'municipalities.municipality_name', 'provinces.province_name')
            ->with(['wildlifePermits' => function ($wildlifePermits) {
                $wildlifePermits->select('id', 'user_id', 'permit_number', 'permit_type', 'valid_from', 'valid_to', 'date_of_issue', 'status');
            }])
            ->join('personal_infos', 'personal_infos.user_id', 'users.id')
            ->join('barangays', 'barangays.id', 'personal_infos.barangay_id')
            ->join('municipalities', 'municipalities.id', 'barangays.municipality_id')
            ->join('provinces', 'provinces.id', 'municipalities.province_id')
            ->where('users.usertype', 'permittee')
            ->get();
        return view('admin.permittee.index', [
            'permittees' => $permittees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $_barangay = new Barangay;
        $barangays = $_barangay->getBarangays();

        return view('admin.permittee.create', [
            'barangays' => $barangays
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $_user = new User;
        $_personalinfo = new PersonalInfo;

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'last_name' => 'required|max:150',
                'first_name' => 'required|max:150',
                // 'middle_name' => 'required|max:150',
                'gender' => 'required',
                'contact_no' => 'required',
                'barangay_id' => 'required',
                'email' => 'required|max:150|unique:personal_infos',
                'username' => 'required|max:150|unique:users',
                'password' => 'required',
                'farm_name' => 'required',
                'location' => 'required',
                'size' => 'required',
                'height' => 'required',
                // 'permit_type_wfp' => 'required',
                'permit_number_wfp' => 'required',
                'valid_from_wfp' => 'required',
                'valid_to_wfp' => 'required',
                'date_of_issue_wfp' => 'required',
                // 'permit_type_wcp' => 'required',
                'permit_number_wcp' => 'required',
                'valid_from_wcp' => 'required',
                'valid_to_wcp' => 'required',
                'date_of_issue_wcp' => 'required',
            ], [
                'barangay_id.required' => 'The barangay/municipality field is required.'
            ]);

            if ($validator->fails()) {
                return redirect('/permittees/create')
                            ->withErrors($validator)
                            ->withInput();
            }

            $user = $_user->create([
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password')),
                'usertype' => 'permittee',
                'is_active_user' => 1,
            ]);

            if ($user) {
                $_personalinfo->user_id = $user->id;
                $_personalinfo->last_name = $request->input('last_name');
                $_personalinfo->first_name = $request->input('first_name');
                $_personalinfo->middle_name = $request->input('middle_name');
                $_personalinfo->gender = $request->input('gender');
                $_personalinfo->email = $request->input('email');
                $_personalinfo->contact_no = $request->input('contact_no');
                $_personalinfo->barangay_id = $request->input('barangay_id');
                $_personalinfo->saveOrFail();

                $permittee_wfp = Permittee::create([
                    'user_id' => $user->id, 
                    'permit_number' => $request->permit_number_wfp, 
                    'permit_type' => $request->permit_type_wfp, 
                    'valid_from' => $request->valid_from_wfp, 
                    'valid_to' => $request->valid_to_wfp, 
                    'date_of_issue' => $request->date_of_issue_wfp, 
                    'status' => 'valid'
                ]);

                if ($permittee_wfp) {
                    $farm = WildlifeFarm::create([
                        'permittee_id' => $permittee_wfp->id, 
                        'farm_name' => $request->farm_name, 
                        'location' => $request->location, 
                        'size' => $request->size, 
                        'height' => $request->height
                    ]);
                }

                $permittee_wcp = Permittee::create([
                    'user_id' => $user->id, 
                    'permit_number' => $request->permit_number_wcp, 
                    'permit_type' => $request->permit_type_wcp, 
                    'valid_from' => $request->valid_from_wcp, 
                    'valid_to' => $request->valid_to_wcp, 
                    'date_of_issue' => $request->date_of_issue_wcp, 
                    'status' => 'valid'
                ]);

                // Generate a signed URL for activation
                $activationLink = URL::temporarySignedRoute(
                    'activate-account', // Name of the route
                    now()->addMinutes(60), // Expiry time
                    ['user' => $user->id] // Pass user ID or other identifier
                );

                // Send activation email
                $details = [
                    'title' => 'Account Created',
                    'email' => $request->input('email'),
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'activationLink' => $activationLink,
                ];

                Mail::to($request->input('email'))->send(new AccountCreated($details));
            }

            DB::commit();
            return redirect('/permittees')->with('success', 'Successfully saved!');
        } catch (Exception $e) {
            // return $e;
            DB::rollBack();
            return redirect('/permittees/create')->withInput()->with('error', 'Something went wrong. Please try again!');
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $_permittee = new Permittee;
        $_wildlifefarm = new WildlifeFarm;

        $permittee = $_permittee->getPermittee($id);
        $permit_type = (!empty($permittee)) ? $permittee->permit_type : 'Permit';
        $wildlife_farm = (!empty($permittee)) ? $_wildlifefarm->getWildlifeFarm($permittee->id) : null;

        return view('admin.permittee.show', [
            'permittee' => $permittee,
            'permit_type' => $permit_type,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permittee_id = Crypt::decrypt($id);

        $_barangay = new Barangay;
        $barangays = $_barangay->getBarangays();

        $permittee = Permittee::find($permittee_id);

        return view('admin.permittee.edit', [
            'permittee_id' => $permittee_id,
            'barangays' => $barangays,
            'permittee' => $permittee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permittee_id = Crypt::decrypt($id);

        $validated = $request->validate([
            'lastname' => 'required|max:150',
            'firstname' => 'required|max:150',
            'middlename' => 'required|max:150',
            'gender' => 'required',
            'contact_info' => 'required',
            'barangay_id' => 'required',
            'business_name' => 'required|max:150',
        ], [
            'barangay_id.required' => 'The barangay/municipality field is required.'
        ]);
        Permittee::find($permittee_id)->update([
            'lastname' => $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'middlename' => $request->input('middlename'),
            'gender' => $request->input('gender'),
            'contact_info' => $request->input('contact_info'),
            'barangay_id' => $request->input('barangay_id'),
            'business_name' => $request->input('business_name'),
        ]);

        return Redirect::route('permittees.index')->with('success', 'Successfully saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ajaxUpdateStatus(Request $request)
    {
        $user_id = Crypt::decrypt($request->permittee_id);
        $is_active_user= $request->is_active_permittee;
        $update = User::find($user_id)->update(['is_active_user'=>$is_active_user]);

        if ($update) {
            return Response()->json(['success'=>'Updated', 200]);
        } else {
           return Response()->json(['failed'=>'Failed'], 500); 
        }
    }
}
