<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\PersonalInfo;
use App\Models\Barangay;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    //
    public function index () {
        $user = Auth::user();

        return view("account.index", [
            "user" => $user
        ]);
    }

    public function editPersonalInfo(string $id) {
        
        $_barangay = new Barangay;
        $barangays = $_barangay->getBarangays();

        $personInfoId = Crypt::decryptString($id);

        $personInfo = PersonalInfo::find($personInfoId);

        Gate::authorize('update', $personInfo);

        return view("account.editPersonalInfo", [
            "personInfo" => $personInfo,
            "barangays" => $barangays
        ]);
    }

    public function updatePersonalInfo(Request $request, string $id) {
        try {
            $personInfoId = Crypt::decryptString($id);
    
            $request->validate([
                'last_name' => 'required',
                'first_name' => 'required',
                'middle_name' => 'required',
                'gender' => 'required',
                'email' => 'required|unique:personal_infos,email,'.$personInfoId,
                'contact_no' => 'required',
                'barangay_id' => 'required'
            ], [
                'barangay_id.required' => 'The barangay/municipality field is required.'
            ]);
    
            $personInfo = PersonalInfo::find($personInfoId);

            Gate::authorize('update', $personInfo);
    
            $personInfo->update([
                'last_name' => $request->input('last_name'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'barangay_id' => $request->input('barangay_id')
            ]);
    
            return redirect()->route('account.index')->with('success', "Personal Information successfully updated");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function activityLogs() {
        $logs = Audit::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(20);
        return view("account.audit-trail", [
            "logs" => $logs
        ]);
    }
}
