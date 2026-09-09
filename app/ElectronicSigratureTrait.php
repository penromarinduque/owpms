<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait ElectronicSigratureTrait
{
    //

    private function getElectronicSignature(
        $signee_name,
        $signee_position,
        $signature_url,
        $document_title,
        $document_type, 
        $document_id, 
        $user_id, 
        $other_details = []
        )
    {
        try {
            return Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env('ESIGNATURE_API_URL') . '/signatures/record', [
                'api_key' => env('ESIGNATURE_API_KEY'),
                'signature_url' => $signature_url,
                'signee_name' => $signee_name,
                'signee_position' => $signee_position,
                'document_title' => $document_title,
                'document_type' => $document_type,
                'document_id' => $document_id,
                'user_id' => $user_id,
                'other_details' => $other_details,
            ]);
        } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error occurred while processing the electronic signature request.',
                    'error' => $th->getMessage(),
                ], 500);
        }
    }
}
