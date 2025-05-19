<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Signatory;
use App\Models\GeneratedDocumentType;
use App\Models\SignatoryRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class SignatoryController extends Controller
{
    //
    public function index()
    {
        $signatory_query = Signatory::query();
        $signatories = $signatory_query
        ->orderBy('generated_document_type_id', 'ASC')
        ->orderBy('order', 'ASC')
        ->paginate(20);

        return view('admin.maintenance.signatory.index', [
            'signatories' => $signatories
        ]);
    }

    public function create() {
        $documentTypes = GeneratedDocumentType::where('status', 1)->get();
        $signatoryRoles = SignatoryRole::all();

        return view('admin.maintenance.signatory.create', [
            'documentTypes' => $documentTypes,
            'signatoryRoles' => $signatoryRoles
        ]);
    }

    public function store(Request $request) {
        //
        $request->validate([
            'document_type' => 'required',
            'signatory_role' => 'required',
            'signee' => 'required'
        ]);

        // validate if role and document type is duplicate
        if(Signatory::where([
            'generated_document_type_id' => $request->document_type,
            'signatory_role_id' => $request->signatory_role
        ])->exists()) {
            return redirect()->back()->with('error', 'Signatory already exists.');
        }

        Signatory::create([
            'generated_document_type_id' => $request->document_type,
            'signatory_role_id' => $request->signatory_role,
            'user_id' => $request->signee,
            'order' => $request->signatory_role
        ]);

        return redirect()->route('signatories.index')->with('success', 'Signatory created successfully.');
    }

    public function edit(string $id) {
        $signatory = Signatory::find(Crypt::decryptString($id));

        $documentTypes = GeneratedDocumentType::where('status', 1)->get();
        $signatoryRoles = SignatoryRole::all();

        return view('admin.maintenance.signatory.edit', [
            'signatory' => $signatory,
            'documentTypes' => $documentTypes,
            'signatoryRoles' => $signatoryRoles
        ]);
    }

    public function update(Request $request) {
        //
        $request->validate([
            'document_type' => 'required',
            'signatory_role' => 'required',
            'signee' => 'required',
            'id' => 'required'
        ]);

        // validate if role and document type is duplicate
        if(Signatory::where([
            'generated_document_type_id' => $request->document_type,
            'signatory_role_id' => $request->signatory_role
        ])
        ->whereNot('id', $request->id)
        ->exists()) {
            return redirect()->back()->with('error', 'Signatory already exists.');
        }

        Signatory::where('id', $request->id)->update([
            'generated_document_type_id' => $request->document_type,
            'signatory_role_id' => $request->signatory_role,
            'user_id' => $request->signee,
            'order' => $request->signatory_role
        ]);

        return redirect()->route('signatories.index')->with('success', 'Signatory updated successfully.');
    }

}
