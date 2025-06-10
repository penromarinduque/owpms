<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Http\Controllers\Controller;
use App\Models\InspectionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;

class ForSignaturesController extends Controller
{

    //
    public function index(Request $request) {
        $_helper = new ApplicationHelper;
        $type = $request->input('type');
        
        $docs = $this->getDocuments($type);

        
        return view('for_signatures.index', [
            'docs' => $docs,
            "_helper" => $_helper
        ]);
    }

    public function inspectionReportPermitteeSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('permitteeSign', $inspection_report);
        $inspection_report->permittee_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }

    public function inspectionReportInspectorSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('inspectorSign', $inspection_report);
        $inspection_report->inspector_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }
    
    public function inspectionReportApproverSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('approverSign', $inspection_report);
        $inspection_report->approver_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }

    private function getDocuments($type) {
        if($type == 'inspection_report') {
            return InspectionReport::where(function ($query) {
                $query->where('inspector_id', auth()->user()->id)
                    ->where('inspector_signed', false)
                    ->where('permittee_signed', true);
            })->orWhere(function ($query) {
                $query->where('approver_id', auth()->user()->id)
                    ->where('approver_signed', false)
                    ->where('permittee_signed', true)
                    ->where('inspector_signed', true);
            })
            ->orWhere(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('permittee_signed', false);
            })
            ->paginate(50);
        }
        return [];
    }
}
