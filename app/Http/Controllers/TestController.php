<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestController extends Controller
{
    //
    public function create() {
        return view('test.create');
    }

    public function store(Request $request) {
        /** 
         * start pdf qr affixingfrom gpt
        **/ 
        // $imageData = QrCode::format('png')->size(100)->generate('https://example.com');
        // Storage::put('temp/qrcodes/qr_code.png', $imageData);
        // $qrPath = storage_path('app/public/temp/qrcodes/qr-code.png');

        // $sourcePdf = $request->file('pdf_file');
        // $outputPdf = storage_path('app/public/tempupdated.pdf');

        // $pdf = new Fpdi();

        // // Import existing PDF pages
        // $pageCount = $pdf->setSourceFile($sourcePdf);

        // // Add each page and overlay QR code on the last page
        // for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        //     $template = $pdf->importPage($pageNo);
        //     $size = $pdf->getTemplateSize($template);

        //     $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        //     $pdf->useTemplate($template);

        //     // Add QR only to the last page (optional)
        //     if ($pageNo === $pageCount) {
        //         // Adjust X and Y as needed
        //         $pdf->Image($qrPath, $size['width'] - 40, $size['height'] - 40, 30, 30);
        //     }
        // }

        // $pdf->Output($outputPdf, 'F'); // Save the file

        // return response()->download($outputPdf)->deleteFileAfterSend(true);
    }
}
