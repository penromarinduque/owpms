<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    //
    public function attachSignature(Request $request) {
        $pdfUrl = $request->input('pdfUrl');
        return view('documents.attach-signature', [
            "pdfUrl" => $pdfUrl
        ]);
    }
}
