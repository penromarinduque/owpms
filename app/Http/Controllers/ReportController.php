<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Facades\Pdf;

class ReportController extends Controller
{
    //
    public function issuedPermits(){
        return view('reports.issued-permits');
    }

    public function printIssuedPermits(Request $request){
        $request->validate([
            "type" => "required|integer",
            "month" => "",
            "quarter" => "",
            "semester" => "",
            "year" => "required|integer"
        ]);

        $startDate = null;
        $endDate = null;

        if(in_array($request->frequency, [4])){
            $startDate = $request->year . "-01-01";
            $endDate = $request->year . "-12-31";
        }
        else if(in_array($request->frequency, [3])){
                $startDate = $this->identifyStartDateBySemester($request->semester, $request->year);
                $endDate = $this->identifyEndDateBySemester($request->semester, $request->year);
        }
        else if(in_array($request->frequency, [2])){
                $startDate = $this->identifyStartDateByQuarter($request->quarter, $request->year);
                $endDate = $this->identifyEndDateByQuarter($request->quarter, $request->year);
        }
        else if(in_array($request->frequency, [1])){
                $startDate = $request->year . "-" . str_pad($request->month, 2, "0", STR_PAD_LEFT) . "-01";
                $endDate = date("Y-m-t", strtotime($startDate));
        }


        if(in_array($request->type, [1])){
            
            $monthsConfig = (object)[
                (object)[
                    "id" => 1,
                    "name" => "JAN",
                    "fullName" => "January",
                    "semester" => 1,
                    "quarter" => 1,
                    "startDate" => $request->year . "-01-01",
                    "endDate" => $request->year . "-01-31",
                ],
                (object)[
                    "id" => 2,
                    "name" => "FEB",
                    "fullName" => "February",
                    "semester" => 1,
                    "quarter" => 1,
                    "startDate" => $request->year . "-02-01",
                    "endDate" => $request->year . "-02-28",
                ],
                (object)[
                    "id" => 3,
                    "name" => "MAR",
                    "fullName" => "March",
                    "semester" => 1,
                    "quarter" => 1,
                    "startDate" => $request->year . "-03-01",
                    "endDate" => $request->year . "-03-31",
                ],
                (object)[
                    "id" => 4,
                    "name" => "APR",
                    "fullName" => "April",
                    "semester" => 1,
                    "quarter" => 2,
                    "startDate" => $request->year . "-04-01",
                    "endDate" => $request->year . "-04-30",
                ],
                (object)[
                    "id" => 5,
                    "name" => "MAY",
                    "fullName" => "May",
                    "semester" => 1,
                    "quarter" => 2,
                    "startDate" => $request->year . "-05-01",
                    "endDate" => $request->year . "-05-31",
                ],
                (object)[
                    "id" => 6,
                    "name" => "JUN",
                    "fullName" => "June",
                    "semester" => 1,
                    "quarter" => 2,
                    "startDate" => $request->year . "-06-01",
                    "endDate" => $request->year . "-06-30",
                ],
                (object)[
                    "id" => 7,
                    "name" => "JUL",
                    "fullName" => "July",
                    "semester" => 2,
                    "quarter" => 3,
                    "startDate" => $request->year . "-07-01",
                    "endDate" => $request->year . "-07-31",
                ],
                (object)[
                    "id" => 8,
                    "name" => "AUG",
                    "fullName" => "August",
                    "semester" => 2,
                    "quarter" => 3,
                    "startDate" => $request->year . "-08-01",
                    "endDate" => $request->year . "-08-31",
                ],
                (object)[
                    "id" => 9,
                    "name" => "SEP",
                    "fullName" => "September",
                    "semester" => 2,
                    "quarter" => 3,
                    "startDate" => $request->year . "-09-01",
                    "endDate" => $request->year . "-09-30",
                ],
                (object)[
                    "id" => 10,
                    "name" => "OCT",
                    "fullName" => "October",
                    "semester" => 2,
                    "quarter" => 4,
                    "startDate" => $request->year . "-10-01",
                    "endDate" => $request->year . "-10-31",
                ],
                (object)[
                    "id" => 11,
                    "name" => "NOV",
                    "fullName" => "November",
                    "semester" => 2,
                    "quarter" => 4,
                    "startDate" => $request->year . "-11-01",
                    "endDate" => $request->year . "-11-30",
                ],
                (object)[
                    "id" => 12,
                    "name" => "DEC",
                    "fullName" => "December",
                    "semester" => 2,
                    "quarter" => 4,
                    "startDate" => $request->year . "-12-01",
                    "endDate" => $request->year . "-12-31",
                ],
            ];

            $selectedMonthsConfig = $request->frequency == 1 ? collect($monthsConfig)->where("id", $request->month) : 
            ($request->frequency == 2 ? collect($monthsConfig)->where("quarter", $request->quarter) :
             ($request->frequency == 3 ? collect($monthsConfig)->where("semester", $request->semester) : collect($monthsConfig)));
            $selectedMonthsQuery = $selectedMonthsConfig->map(function($item){
                return DB::raw("COUNT(CASE WHEN la.application_date BETWEEN '" . $item->startDate . "' AND '" . $item->endDate . "' THEN 1 END) as m" . $item->id);
            });
            $data = DB::table('users as u')
                ->select([
                    'u.id',
                    'p.id as permittee_id',
                    'p.permit_type',
                    'pi.first_name',
                    'pi.middle_name',
                    'pi.last_name',
                    'brgy.barangay_name',
                    'mun.municipality_name',
                    'prov.province_name',
                    'wf.farm_name',
                    ...$selectedMonthsQuery,
                    DB::raw("COUNT(CASE WHEN la.application_date BETWEEN '2026-01-01' AND '2026-03-31' THEN 1 END) as mTotal"),
                    DB::raw("IFNULL(SUM(fees.total_collected), 0) as collected_fees"),
                    DB::raw("IFNULL(SUM(transported.total_transported), 0) as transported_species")
                ])
                ->leftJoin('personal_infos as pi', 'pi.user_id', '=', 'u.id')
                ->leftJoin('barangays as brgy', 'brgy.id', '=', 'pi.barangay_id')
                ->leftJoin('municipalities as mun', 'brgy.municipality_id', '=', 'mun.id')
                ->leftJoin('provinces as prov', 'mun.province_id', '=', 'prov.id')
                ->leftJoin('permittees as p', 'p.user_id', '=', 'u.id')
                ->leftJoin('wildlife_farms as wf', 'wf.permittee_id', '=', 'p.id')
                
                // Left Join for ltp_applications with date boundaries
                ->leftJoin('ltp_applications as la', function($join) use ( $startDate, $endDate) {
                    $join->on('la.permittee_id', '=', 'p.id')
                        ->whereBetween('la.application_date', [$startDate, $endDate])
                        ->whereIn('la.application_status', ['released', 'approved', 'expired']);
                })
                
                // Left Join Subquery for Fees
                ->leftJoinSub(function($query) {
                    $query->from('payment_orders as po')
                        ->select('po.ltp_application_id', DB::raw('SUM(lf.amount) as total_collected'))
                        ->join('ltp_fees as lf', 'lf.id', '=', 'po.ltp_fee_id')
                        ->groupBy('po.ltp_application_id');
                }, 'fees', 'fees.ltp_application_id', '=', 'la.id')
                
                // Left Join Subquery for Transported Species Qty
                ->leftJoinSub(function($query) {
                    $query->from('ltp_application_species as las')
                        ->select('las.ltp_application_id', DB::raw('SUM(las.quantity) as total_transported'))
                        ->groupBy('las.ltp_application_id');
                }, 'transported', 'transported.ltp_application_id', '=', 'la.id')
                ->where('u.usertype', '=', 'permittee')
                ->groupBy([
                    'u.id', 
                    'p.id', 
                    'p.permit_type',
                    'pi.first_name', 
                    'pi.middle_name', 
                    'pi.last_name', 
                    'brgy.barangay_name', 
                    'mun.municipality_name', 
                    'prov.province_name', 
                    'wf.farm_name'
                ])
                ->get();
            return Pdf::view('reports.templates.issued-ltp', [
                "data" => $data,
                "frequency" => $request->frequency,
                "quarter" => $request->quarter,
                "semester" => $request->semester,
                "year" => $request->year,
                "month" => $request->month,
                "selectedMonthsConfig" => $selectedMonthsConfig
            ])
            ->waitUntilReady()
            ->margins(30, 10, 30, 10)
            
            ->format($this->identifyReportFormat($request->paper))
            ->orientation($this->identifyReportOrientation($request->orientation))
            ->headerView('pdfs.header')
            ->footerView('pdfs.footer');
        }
        else if($request->type == 2){
            $data  = DB::table('ltp_applications as la')
                ->select([
                    'la.id',
                    'la.permittee_id',
                    'permits.permit_number',
                    'la.purpose',
                    'pi.first_name',
                    'pi.middle_name',
                    'pi.last_name',
                    'payment_orders.payment_date as issuance_date',
                    DB::raw('permits.approved_at + INTERVAL 1 MONTH as expiration_date'),
                    'provinces.province_name',
                    DB::raw('SUM(las.quantity) as total_species'),
                    'lf.amount'
                ])
                ->leftJoin('permittees as p', 'p.id', '=', 'la.permittee_id')
                ->leftJoin('personal_infos as pi', 'pi.user_id', '=', 'p.user_id')
                ->leftJoin('ltp_permits as permits', 'permits.ltp_application_id', '=', 'la.id')
                ->leftJoin('payment_orders as po', 'po.ltp_application_id', '=', 'la.id')
                ->leftJoin('ltp_fees as lf', 'lf.id', '=', 'po.ltp_fee_id')
                ->leftJoin('provinces', 'provinces.id', '=', 'la.destination')
                ->join('ltp_application_species as las', 'las.ltp_application_id', '=', 'la.id')
                ->whereIn('la.application_status', ['expired', 'approved', 'released'])
                ->whereBetween('la.application_date', [$startDate, $endDate])
                ->groupBy([
                    'la.id',
                    'la.permittee_id',
                    'permits.permit_number',
                    'la.purpose',
                    'pi.first_name',
                    'pi.middle_name',
                    'pi.last_name',
                    'permits.approved_at',
                    'provinces.province_name',
                    'lf.amount',
                    'la.permittee_id',      // Included because it's used in ORDER BY
                    'la.application_date'   // Included because it's used in ORDER BY
                ])
                ->orderBy('la.permittee_id', 'asc')
                ->orderBy('la.application_date', 'asc')
                ->get();
                // return $data;
                return Pdf::view('reports.templates.issued-ltp-by-permittee', [
                    "data" => $data,
                    "frequency" => $request->frequency,
                    "quarter" => $request->quarter,
                    "semester" => $request->semester,
                    "year" => $request->year,
                    "month" => $request->month
                ])
                ->waitUntilReady()
                ->margins(30, 10, 30, 10)
                ->scale(0.75)
                ->format($this->identifyReportFormat($request->paper))
                ->orientation($this->identifyReportOrientation($request->orientation))
                ->headerView('pdfs.header')
                ->footerView('pdfs.footer');
        }
        else if($request->type == 3){
            
        }
        else if($request->type == 4){
            $data = DB::table('ltp_applications as la')
            ->select([
                'la.id',
                'la.permittee_id',
                'permits.permit_number',
                'la.purpose',
                'pi.first_name as first_name',
                'pi.middle_name as middle_name',
                'pi.last_name as last_name',
                'po.payment_date as issuance_date',
                DB::raw('permits.approved_at + INTERVAL 1 MONTH as expiration_date'),
                'provinces.province_name',
                'las.quantity as total_species', // Individual quantity for this specific species row
                'lf.amount',
                'species.local_name as local_name',           // Individual species name for this row
                'species.specie_name as specie_name',
                "sn.name as specie_nature_name",
                "b.barangay_name as barangay_name", 
                "m.municipality_name as municipality_name",
            ])
            ->leftJoin('permittees as p', 'p.id', '=', 'la.permittee_id')
            ->leftJoin('personal_infos as pi', 'pi.user_id', '=', 'p.user_id')
            ->leftJoin('ltp_permits as permits', 'permits.ltp_application_id', '=', 'la.id')
            ->leftJoin('payment_orders as po', 'po.ltp_application_id', '=', 'la.id')
            ->leftJoin('ltp_fees as lf', 'lf.id', '=', 'po.ltp_fee_id')
            ->leftJoin('provinces', 'provinces.id', '=', 'la.destination')
            ->leftJoin('barangays as b', 'b.id', '=', 'pi.barangay_id')
            ->leftJoin('municipalities as m', 'm.id', '=', 'b.municipality_id')
            ->join('ltp_application_species as las', 'las.ltp_application_id', '=', 'la.id')
            ->leftJoin('species', 'species.id', '=', 'las.specie_id')
            ->leftJoin("specie_natures as sn", 'sn.id', '=', 'la.specie_nature_id')
            ->whereIn('la.application_status', ['expired', 'approved', 'released'])
            ->whereBetween('la.application_date', [$startDate, $endDate])
            ->orderBy('la.permittee_id', 'asc')
            ->orderBy('la.application_date', 'asc')
            ->get();

            // return $data;

            return Pdf::view('reports.templates.bmbsr', [
                    "data" => $data,
                    "frequency" => $request->frequency,
                    "quarter" => $request->quarter,
                    "semester" => $request->semester,
                    "year" => $request->year,
                    "month" => $request->month
                ])
                ->waitUntilReady()
                ->margins(30, 10, 20, 10)
                ->scale(0.5)
                ->format($this->identifyReportFormat($request->paper))
                ->orientation($this->identifyReportOrientation($request->orientation))
                ->headerView('pdfs.header')
                ->footerView('pdfs.footer');
        }
        
    }

    private function identifyStartDateBySemester($semester, $year){
        if($semester == 1){
            return $year . "-01-01";
        }else if($semester == 2){
            return $year . "-07-01";
        }
        return null;
    }

     private function identifyStartDateByQuarter($quarter, $year){
        if($quarter == 1){
            return $year . "-01-01";
        }else if($quarter == 2){
            return $year . "-04-01";
        }else if($quarter == 3){
            return $year . "-07-01";
        }else if($quarter == 4){
            return $year . "-10-01";
        }
        return null;
    }

    private function identifyEndDateBySemester($semester, $year){
        if($semester == 1){
            return $year . "-06-30";
        }else if($semester == 2){
            return $year . "-12-31";
        }
        return null;
    }

     private function identifyEndDateByQuarter($quarter, $year){
        if($quarter == 1){
            return $year . "-03-31";
        }else if($quarter == 2){
            return $year . "-06-30";
        }else if($quarter == 3){
            return $year . "-09-30";
        }else if($quarter == 4){
            return $year . "-12-31";
        }
        return null;
    }

    private function identifyReportFormat($format){
        if($format == "a4"){
            return Format::A4;
        }else if($format == "letter"){
            return Format::Letter;
        }else if($format == "legal"){
            return Format::Legal;
        }
        return null;
    }

    private function identifyReportOrientation($orientation){
        if($orientation == "portrait"){
            return Orientation::Portrait;
        }else if($orientation == "landscape"){
            return Orientation::Landscape;
        }
        return null;
    }
}
