<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\PersonalInfo;
use App\Models\Barangay;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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

    public function uploadSignature(Request $request) {
        $userId = $request->input("userId");
        $signature = $request->input("signature");
        $base64 = explode(',', $signature);
        $image = base64_decode($base64[1]);
        $filename = $userId . "-" . time()  .'.png';
        Storage::put("signatures/".$filename, $image);
        Signature::updateOrCreate([
            "user_id" => $userId
        ], [
            "signature" => $filename
        ]);
        return response([
            "message" => "Signature Uploaded successfully"
        ]);
    }

    // public function viewSignature(Request $request, $id) {
    //     $signature = Signature::where("user_id", $id)->first();
    //     $file = Storage::get('signatures/' . $signature->signature);
    //     return response($file)->header('Content-Type', 'image/png');
    //     // return Storage::
    //     // return Storage::get("signature", $signature->signature);
    // }
    public function storeSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required',
        ]);

        // Decode base64 image
        $image_parts = explode(";base64,", $request->signature);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = auth()->user()->id . '.png';

        // Store in 'public/signatures' using Laravel Storage
        Storage::disk('private')->put('signatures/' . $fileName, $image_base64);

        // Save file name (or full path if you prefer) to DB
        User::find(auth()->user()->id)->update(['signature' => $fileName]);

        return redirect()->back()->with('success', 'Signature saved successfully.');
    }

    public function viewSignature(string $id) {
        $_id = Crypt::decryptString($id);

        $user = User::find($_id);

        return Storage::disk('private')->response(
            'signatures/' . $user->signature,
            null, // keep original file name
            ['Content-Type' => 'image/png'] // set MIME type properly
        );

    }

}
