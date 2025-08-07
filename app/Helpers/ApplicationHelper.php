<?php

namespace App\Helpers;

use App\Models\InspectionReport;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\LtpPermit;
use App\Models\PaymentOrder;
use App\Models\Permittee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;

class ApplicationHelper
{
    public $_inspection_report;
    public $_ltp_permit;
    public $_payment_order;

    public function __construct() {
        $this->_inspection_report = new InspectionReport();
        $this->_ltp_permit = new LtpPermit();
        $this->_payment_order = new PaymentOrder();
    }
    public static function formatApplicationStatus($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'Draft',
            LtpApplication::STATUS_SUBMITTED => 'Submitted',
            LtpApplication::STATUS_UNDER_REVIEW => 'Under Review',
            LtpApplication::STATUS_REVIEWED => 'Reviewed',
            LtpApplication::STATUS_RETURNED => 'Returned',
            LtpApplication::STATUS_RESUBMITTED => 'Resubmitted',
            LtpApplication::STATUS_ACCEPTED => 'Accepted',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'Payment in Process',
            LtpApplication::STATUS_PAID => 'Paid',
            LtpApplication::STATUS_FOR_INSPECTION => 'For Inspection',
            LtpApplication::STATUS_INSPECTED => 'Inspected',
            LtpApplication::STATUS_APPROVED => 'Approved',
            LtpApplication::STATUS_RELEASED => 'Released',
            LtpApplication::STATUS_EXPIRED => 'Expired',
            LtpApplication::STATUS_REJECTED => 'Rejected',
            LtpApplication::STATUS_INSPECTION_REJECTED => 'Inspection Rejected',    
            default => 'Unknown',
        };
    }

    public function setApplicationStatusBsColor($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'secondary',
            LtpApplication::STATUS_SUBMITTED => 'primary',
            LtpApplication::STATUS_UNDER_REVIEW => 'warning',
            LtpApplication::STATUS_REVIEWED => 'warning',
            LtpApplication::STATUS_RETURNED => 'warning',
            LtpApplication::STATUS_RESUBMITTED => 'primary',
            LtpApplication::STATUS_ACCEPTED => 'success',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'secondary',
            LtpApplication::STATUS_PAID => 'success',
            LtpApplication::STATUS_FOR_INSPECTION => 'secondary',
            LtpApplication::STATUS_INSPECTION_REJECTED => 'danger',
            LtpApplication::STATUS_INSPECTED => 'primary',
            LtpApplication::STATUS_APPROVED => 'success',
            LtpApplication::STATUS_RELEASED => 'success',
            LtpApplication::STATUS_EXPIRED => 'secondary',
            default => 'secondary',
        };
    }

    public function setApplicationStatusBgColor($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'secondary',
            LtpApplication::STATUS_SUBMITTED => 'primary',
            LtpApplication::STATUS_UNDER_REVIEW => 'warning',
            LtpApplication::STATUS_REVIEWED => 'warning',
            LtpApplication::STATUS_RETURNED => 'warning',
            LtpApplication::STATUS_RESUBMITTED => 'primary',
            LtpApplication::STATUS_ACCEPTED => 'success',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'secondary',
            LtpApplication::STATUS_PAID => 'success',
            LtpApplication::STATUS_FOR_INSPECTION => 'secondary',
            LtpApplication::STATUS_INSPECTION_REJECTED => 'danger',
            LtpApplication::STATUS_INSPECTED => 'primary',
            LtpApplication::STATUS_APPROVED => 'success',
            LtpApplication::STATUS_RELEASED => 'success',
            LtpApplication::STATUS_EXPIRED => 'secondary',
            default => 'secondary',
        };
    }

    public function setPermitStatusColor(Permittee $permit)
    {
        $now = Carbon::now();
        $validTo = $permit->valid_to;

        if ($now->gte($validTo)) {
            return 'permit-lv5'; // Already expired
        } elseif ($now->gte($validTo->copy()->subMonths(2))) {
            return 'permit-lv4'; // Within 2 months of expiry
        } elseif ($now->gte($validTo->copy()->subMonths(3))) {
            return 'permit-lv3'; // Within 3 months of expiry
        } elseif ($now->gte($validTo->copy()->subMonths(6))) {
            return 'permit-lv2'; // Within 6 months of expiry
        } elseif ($now->gte($validTo->copy()->subYear())) {
            return 'permit-lv1'; // Within 1 year of expiry
        }

        return null; // Or a default class
    }

    public function identifyApplicationStatusesByCategory($category)
    {
        if($category == "submitted") {
            return [
                LtpApplication::STATUS_SUBMITTED,
                LtpApplication::STATUS_RESUBMITTED,
                LtpApplication::STATUS_UNDER_REVIEW
            ];
        }

        if($category == "reviewed") {
            return [
                LtpApplication::STATUS_REVIEWED
            ];
        }

        if($category == "returned") {
            return [
                LtpApplication::STATUS_RETURNED
            ];
        }

        if($category == "accepted") {
            return [
                LtpApplication::STATUS_ACCEPTED,
                LtpApplication::STATUS_PAYMENT_IN_PROCESS,
                LtpApplication::STATUS_PAID,
                LtpApplication::STATUS_FOR_INSPECTION,
                LtpApplication::STATUS_INSPECTED
            ];
        }

        if($category == "rejected") {
            return [
                LtpApplication::STATUS_REJECTED,
                LtpApplication::STATUS_INSPECTION_REJECTED,
                // LtpApplication::STATUS_CANCELLED
            ];
        }

        if($category == "approved") {
            return [
                LtpApplication::STATUS_APPROVED,
                LtpApplication::STATUS_RELEASED
            ];
        }

        if($category == "expired") {
            return [
                LtpApplication::STATUS_EXPIRED
            ];
        }

        if($category == "draft") {
            return [
                LtpApplication::STATUS_DRAFT
            ];
        }

        return [];
    }

    public function ordinal($number) {
        $suffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $suffixes[$number % 10];
    }

    function setForSignatoriesDocumentName($type){
        if($type == "inspection_report"){
            return "Inspection Report";
        }
        else if($type == "ltp"){
            return "Local Transport Permit";
        }
        else if($type == "payment_order"){
            return "Order of Payment";
        }
        else if($type == "billing_statement"){
            return "Billing Statement";
        }
        else if($type == "request_letter"){
            return "Request Letter";
        }
        return "";
        
    }

    public function displayBadgeCount($color, $count) {
        if($count <= 0){
            return "";
        }
        if($count > 99){
            return "<span class='badge rounded-pill bg-$color'>99+</span>";
        }
        return "<span class='badge rounded-pill bg-$color'>$count</span>";
    }

    public function getForSignatoriesCount($type){
        if($type == "inspection_report"){
            return $this->_inspection_report->pendingSignaturesFor(auth()->user()->id)->count();
        }
        else if($type == "ltp"){
            return $this->_ltp_permit->pendingSignaturesFor(auth()->user()->id)->count();
        }
        else if($type == "billing_statement"){
            return $this->_payment_order->pendingBillingStatementSignaturesFor(auth()->user()->id)->count();
        }
        else if($type == "payment_order"){
            return $this->_payment_order->pendingOopSignaturesFor(auth()->user()->id)->count();
        }
        else if($type == "request_letter"){
            // return $_request_letter->getForSignatoriesCount();
        }
        return 0;
    }

    public function test(){
        return "Helper Facade working";
    }

    /**
     * Generates a QR code for the given URL, LTP Application ID and document type.
     *
     * @param string $url
     * @return string
     */
    public function generateQrCode($url){
        return 'https://api.qrcode-monkey.com/qr/custom?size=1000&data='. $url .'&config={"eye" : "frame0","eyeBall" : "ball0","logo" : "http://owpms.penromarinduque.gov.ph/images/logo-small.png","logoMode" : "clean"}';
    }

    public function formatTimelineDescription(LtpApplicationProgress $log) {
        $color = $this->setApplicationStatusBgColor($log->status);
        return '<span class="badge  bg-'.$color.'">'. $this->formatApplicationStatus($log->status) .'</span><br><div class="bg-light p-2">'. (!in_array($log->description, ['', null, ' ', 'N/A', '<></>', '<p><br></p>']) ? $log->description : '<span class="text-muted">No Remarks</span>') . '</div>';
    }

    public function ltpApplicationCountByStatus($status)
    {
        $query = LtpApplication::query();
        $conditions = [];
        if(auth()->user()->usertype == 'permittee') {
            $conditions['permittee_id'] = auth()->user()->wcp()->id;
        }

        $query->whereIn('application_status', $this->identifyApplicationStatusesByCategory($status))
            ->where($conditions);

        if(in_array('LTP_APPLICATION_INSPECT', auth()->user()->getUserPermissions()))
        {
            $query->where(function ($query) {
                $query->where('io_user_id', Auth::user()->id)
                      ->orWhereNull('io_user_id');
            });
        }

        return $query->count();
    }

    public function numberToWords($num) {
        $numberToWords = new NumberToWords();

        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $words = ucwords($numberTransformer->toWords($num));

        return $words;
    }

}
