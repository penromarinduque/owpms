<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Signatory;
use App\Models\GeneratedDocumentType;
use App\Models\SignatoryRole;


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
}
