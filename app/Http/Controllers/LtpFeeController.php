<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LtpFee;
use Illuminate\Support\Facades\Crypt;

class LtpFeeController extends Controller
{
    //
    public function index()
    {
        $fees = LtpFee::query()
        ->orderBy('is_active', 'DESC')
        ->paginate(10);

        return view('admin.maintenance.ltpfees.index', [
            'fees' => $fees
        ]);
    }

    public function create(){
        return view('admin.maintenance.ltpfees.create');
    }

    public function store(Request $request){
        $request->validate([
            'fee_name' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'legal_basis' => 'required',
        ]);

        $duplicate = LtpFee::where([
            'legal_basis' => $request->legal_basis,
            'amount' => $request->amount,
        ])
        ->exists();

        if($duplicate){
            return redirect()->back()->with('error', 'Fee already exists');
        }

        LtpFee::create([
            'fee_name' => $request->fee_name,
            'amount' => $request->amount,
            'legal_basis' => $request->legal_basis,
            'is_active' => 0
        ]);

        return redirect()->route('ltpfees.index')->with('success', 'Fee created successfully');
    }

    public function edit(string $id) {
        $fee_id = Crypt::decryptString($id);
        $fee = LtpFee::find($fee_id);

        return view('admin.maintenance.ltpfees.edit', [
            'fee' => $fee
        ]);
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'fee_name' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'legal_basis' => 'required'
        ]);

        $fee_id = Crypt::decryptString($id);
        $fee = LtpFee::find($fee_id);

        $duplicate = LtpFee::where([
            'legal_basis' => $request->legal_basis,
            'amount' => $request->amount,
            ['id', '!=', $fee_id]
        ])
        ->exists();

        if($duplicate){
            return redirect()->back()->with('error', 'Fee already exists');
        }

        if($request->boolean('status')){
            LtpFee::where('id', '!=', $fee_id)->update([
                'is_active' => 0
            ]);
        }

        $fee->update([
            'fee_name' => $request->fee_name,
            'amount' => $request->amount,
            'legal_basis' => $request->legal_basis,
            'is_active' => $request->boolean('status')
        ]);

        return redirect()->route('ltpfees.index')->with('success', 'Fee updated successfully');
    }

    public function destroy(Request $request) {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->id;

        LtpFee::find($id)->delete();
        
        return redirect()->back()->with([
            'success' => "LTP Fee deleted successfully"
        ]);
    }
}
