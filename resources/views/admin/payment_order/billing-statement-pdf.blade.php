



@extends('layouts.pdf')

@section('title') Assesment of Fees and Charges @endsection

<style>
    * {
        font-size: 13px;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }

    .col {
        flex: 1 0 0%;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .col-auto {
        flex: 0 0 auto;
        width: auto;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .mx-5 {
        margin-left: 3rem;
        margin-right: 3rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .fw-semibold {
        font-weight: 600;
    }
</style>


@section('content')
    {{-- Assesment if Fees and Charges --}}
    <div style="width: 700px" class=" mx-auto mb-4 p-0">
        <div class="row mx-5 w-100">
            <div class="col-auto">
                <img src="{{ public_path('images/logo-small.png') }}" width="60" alt="">
            </div>
            <div class="col">
                <h6 class="mb-0 fw-semibold">Republic of the Philippines</h6>
                <h6 class="mb-0 fw-semibold">Department of Environment and Natural Resources</h6>
                <h6 class="mb-0 fw-semibold">PENRO Marinduque</h6>
            </div>
        </div>
        <div class="" style="border: 1px solid black; margin-top: 10px;">
            <h6 class="fw-bold text-center mb-2">Assesment of Fees and Charges</h6>
            <table style="width: 100%" class="mb-3">
                <tr>
                    <td width="30%"></td>
                    <td width="30%"></td>
                    <td width="15%" class="fw-bold">Bill No.</td>
                    <td width="25%" style="border-bottom: 1px solid black" class="text-center fst-italic" >{{ $payment_order->order_no }}</td>
                </tr>
                <tr>
                    <td ></td>
                    <td ></td>
                    <td  class="fw-bold">Date:</td>
                    <td  style="border-bottom: 1px solid black" class="text-center" >{{ $payment_order->issued_date->format('F d, Y') }}</td>
                </tr>
            </table>
            <table style="width: 100%">
                <tr class="">
                    <td width="20%" class="fw-bold">Name/Payee:</td>
                    <td width="80%" style="border-bottom: 1px solid black">{{ $ltp_application->permittee->user->personalInfo->first_name }} {{ $ltp_application->permittee->user->personalInfo->last_name }}</td>
                </tr>
                <tr class="pt-2">
                    <td width="20%" class="fw-bold pt-2">Address:</td>
                    <td width="80%" style="border-bottom: 1px solid black">{{ $ltp_application->permittee->user->personalInfo->address }}</td>
                </tr>
                <tr class="pt-2">
                    <td colspan="2" class="fw-bold pt-2">Nature of Application/Permit/Documents being secured:</td>
                </tr>
                <tr >
                    <td width="20%" class="fw-bold"></td>
                    <td width="80%" >LTP Application</td>
                </tr>
            </table>
            <table class=""style="width: 100%; border-bottom: 1px solid black; border-top: 1px solid black;">
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" class="text-center fw-bold" style="border-right: 1px solid black">Legal Basis (DAO/SEC)</td>
                    <td width="39%" class="text-center fw-bold" style="border-right: 1px solid black">Description and Computation of Fees and Charges Assessed</td>
                    <td width="41%" class="text-center fw-bold">Amount</td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black" class="text-center">{{ $ltp_fee->legal_basis }}</td>
                    <td width="39%" style="border-right: 1px solid black" class="text-center">{{ $ltp_fee->fee_name }}</td>
                    <td width="41%" class="text-center">{{ $ltp_fee->amount }}</td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="39%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="41%">&nbsp;</td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="39%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="41%">&nbsp;</td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="39%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="41%"> </td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="39%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="41%">&nbsp;</td>
                </tr>
                <tr style="border-bottom: 1px solid black">
                    <td width="20%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="39%" style="border-right: 1px solid black">&nbsp;</td>
                    <td width="41%">&nbsp;</td>
                </tr>
                <tr >
                    <td colspan="2" width="59%" style="border-right: 1px solid black" class="fw-bold text-center">TOTAL</td>
                    <td width="41%" class="text-center">{{ $ltp_fee->amount }}</td>
                </tr>
            </table>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td width="50%" class="fw-bold">Prepared by:</td>
                    <td width="50%" class="fw-bold">Approved by:</td>
                </tr>
                <tr>
                    <td width="50%">&nbsp;</td>
                    <td width="50%">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                    <td width="15%"></td>
                    <td width="35%" style="border-bottom: 1px solid black; " class="text-center fw-bold text-uppercase" id="prepared_by__td">{{ $prepared_by->personalInfo->getFullNameAttribute(true) }}</td>
                    <td width="15%"></td>
                    <td width="35%" style="border-bottom: 1px solid black; " class="text-center fw-bold text-uppercase" id="approved_by__td">{{ $approved_by->personalInfo->getFullNameAttribute(true) }}</td>
                </tr>
                <tr>
                    <td width="15%"></td>
                    <td width="35%"  class="text-center" id="prepared_by_position__td">{{ $payment_order->prepared_by_position }}</td>
                    <td width="15%"></td>
                    <td width="35%"  class="text-center" id="approved_by_position__td">{{ $payment_order->approved_by_position }}</td>
                </tr>
            </table>
        </div>
        <table style="width: 100%;">
            <tr>
                <td width="14%" class="fw-bold">Received:</td>
                <td width="17%" style="border-bottom: 1px solid black"></td>
                <td width="2%"></td>
                <td width="14%" class="fw-bold">Tracking No.</td>
                <td width="17%" style="border-bottom: 1px solid black"></td>
                <td width="16%"></td>
            </tr>
            <tr>
                <td width="" class="fw-bold">Date:</td>
                <td width="" style="border-bottom: 1px solid black"></td>
                <td width=""></td>
                <td width="" class="fw-bold">Time:</td>
                <td width="" style="border-bottom: 1px solid black"></td>
                <td width=""></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td width="" class="fw-bold">Released Date:</td>
                <td width="" style="border-bottom: 1px solid black"></td>
                <td width=""></td>
                <td width="" class="fw-bold">Time:</td>
                <td width="" style="border-bottom: 1px solid black"></td>
                <td width=""></td>
            </tr>
        </table>
    </div>


    {{-- Order of Payment --}}
    {{-- <div style="page-break-before: always; width: 800px;font-family: 'Times New Roman', Times, serif" class=" mx-auto ">
        <p class="text-end mb-0"><i>Appendix 28</i></p>
        <div style=" border: 2px solid black; " class=" mx-auto mb-4 p-0">
            <br>
            <table class="w-100">
                <tr>
                    <td width="50%" class=" fw-bold">Entity Name : <u>DENR-PENRO</u></td>
                    <td width="50%" class="text-end fw-bold">Serial No.: <div class="" style="display: inline-block; border-bottom: 1px solid black; width: 100px" ><p class="mb-0 text-center"></p></div></td>
                </tr>
                <tr>
                    <td width="50%" class=" fw-bold">Fund Cluster : <u>101</u></td>
                    <td width="50%" class="text-end fw-bold">Date: <div class="" style="display: inline-block; border-bottom: 1px solid black; width: 100px" ><p class="mb-0 text-center">{{ date("d-M-y") }}</p></div></td>
                </tr>
            </table>
            <br>
            <h5 class="text-center fw-bold">ORDER OF PAYMENT</h5>
            <br>
            <p class="fw-bold mb-0">The Collecting Officer</p>
            <p>Cash/Treasury Unit</p>

            <table class="w-100">
                <tr>
                    <td width="60px"></td>
                    <td  style="white-space: nowrap; width: 1%">Please issue Official Receipt in favor of &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td width="" style="border-bottom: 1px solid black; " class="text-center text-uppercase fw-bold">{{ $ltp_application->permittee->user->personalInfo->first_name }} {{ $ltp_application->permittee->user->personalInfo->last_name }}</td>
                </tr>
                <tr>
                    <td ></td>
                    <td ></td>
                    <td class="text-center" style="font-size: 13px">(Name of Payor)</td>
                </tr>
            </table>
            <table class="w-100">
                <tr>
                    <td width="100%" style="border-bottom: 1px solid black" class="text-center fw-bold">{{ $ltp_application->permittee->user->personalInfo->address }}</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center" style="font-size: 13px">(Address/Office of Payor)</td>
                </tr>
            </table>
            <table class="w-100">
                <tr>
                    <td style="white-space: nowrap; width: 1%">in the amount of &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="border-bottom: 1px solid black" class="text-center fw-bold">{{ $_helper->numberToWords($ltp_fee->amount) }} Pesos Only ({{ $ltp_fee->amount }})</td>
                </tr>
            </table>
            <table class="w-100">
                <tr>
                    <td style="white-space: nowrap; width: 1%">for the payment of &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="border-bottom: 1px solid black" class="text-center fw-bold"></td>
                </tr>
            </table>
            <table class="w-100">
                <tr>
                    <td width="100%" style="border-bottom: 1px solid black" class="text-center fw-bold">Local Transport Permit</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center" style="font-size: 13px">(Purpose)</td>
                </tr>
            </table>
            <table class="w-100">
                <tr>
                    <td style="white-space: nowrap; width: 1%">per Bill No. &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="border-bottom: 1px solid black" class="text-center"><i>(Generated Bill No.)</i></td>
                    <td style="white-space: nowrap; width: 1%">&nbsp;&nbsp;&nbsp;&nbsp; dated &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="border-bottom: 1px solid black" class="text-center fw-bold">{{ date("F d, Y") }}.</td>
                    <td width="100px" class="text-center"></td>
                </tr>
            </table>
            <br>
            <p>Please deposit the collections under Bank Account/s:</p>
            
            <table style="width: 100%; ">
                <tr>    
                    <td width="20"  class="text-center"><u>No.</u></td>
                    <td width="5" ></td>
                    <td width="30" class="text-center"><u>Name of Bank</u></td>
                    <td width="5" ></td>
                    <td width="30" class="text-center"><u>Amount</u></td>
                </tr>
                <tr>    
                    <td width="20" style="border-bottom: 1px solid black" class="text-center fw-bold">3402-2455-30</td>
                    <td width="5" ></td>
                    <td width="30" style="border-bottom: 1px solid black" class="text-center fw-bold">LBP-BOAC</td>
                    <td width="5" ></td>
                    <td width="30" style="border-bottom: 1px solid black" class="text-center fw-bold">{{ $ltp_fee->amount }}</td>
                </tr>
                <tr>    
                    <td width="20" style="border-bottom: 1px solid black" class="text-center fw-bold">&nbsp;</td>
                    <td width="5" ></td>
                    <td width="30" style="border-bottom: 1px solid black" class="text-center fw-bold">&nbsp;</td>
                    <td width="5" ></td>
                    <td width="30" style="border-bottom: 1px solid black" class="text-center fw-bold">&nbsp;</td>
                </tr>
                <tr>    
                    <td width="20"  class="text-center fw-bold">&nbsp;</td>
                    <td width="5" ></td>
                    <td width="30"  class="text-center fw-bold">&nbsp;</td>
                    <td width="5" ></td>
                    <td width="30" style="border-bottom: 4px double black;" class="text-center fw-bold">{{ $ltp_fee->amount }}</td>
                </tr>   
            </table>
            <br>
            <table style="width: 100%; ">
                <tr>    
                    <td width="50%" ></td>
                    <td width="50%" style="border-bottom: 1px solid black; ">&nbsp;</td>
                </tr>
                <tr>    
                    <td width="50%" ></td>
                    <td width="50%" class="text-center fw-bold" style="text-transform: uppercase;" id="oop_approved_by__td"></td>
                </tr>
                <tr>    
                    <td width="50%" ></td>
                    <td width="50%" class="text-center" id="oop_approved_by_position__td"></td>
                </tr>
            </table>
            <br>
        </div>
    </div> --}}
@endsection

