



@extends('layouts.pdf')

@section('title') Order of Payment @endsection

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

    {{-- Order of Payment --}}
    <div class="mx-auto" style="width: 100%; ">
        <table style="width: 100%; ">
            <tr>
                @for ($i = 0; $i < 2; $i++)
                <td>
                    <div class="w-100">
                        <div style=" font-family: 'Times New Roman', Times, serif" class=" mx-auto ">
                            <p class="text-end mb-0"><i>Appendix 28</i></p>
                            <div style=" border: 2px solid black; " class=" mx-auto mb-4 p-0">
                                <table class="w-100">
                                    <tr>
                                        <td width="50%" class=" fw-bold">Entity Name : <u>DENR-PENRO</u></td>
                                        <td width="50%" class="text-end fw-bold">Serial No.: <div class="" style="display: inline-block; border-bottom: 1px solid black; width: 100px" ><p class="mb-0 text-center"></p></div></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class=" fw-bold">Fund Cluster : <u>151</u></td>
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
                                        <td style="border-bottom: 1px solid black" class="text-center fw-bold">{{ $payment_order->order_number }}</td>
                                        <td style="white-space: nowrap; width: 1%">&nbsp;&nbsp;&nbsp;&nbsp; dated &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td style="border-bottom: 1px solid black" class="text-center fw-bold">{{ date("F d, Y") }}.</td>
                                        <td width="100px" class="">.</td>
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
                                        <td width="50%" class="text-center fw-bold" style="text-transform: uppercase;" id="oop_approved_by__td">{{ $oop_approved_by->personalInfo->getFullNameAttribute(true) }}</td>
                                    </tr>
                                    <tr>    
                                        <td width="50%" ></td>
                                        <td width="50%" class="text-center" id="oop_approved_by_position__td">{{ $oop_approved_by->empPosition->position }}</td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </td>
                @endfor
            </tr>
        </table>
        
    </div>
@endsection

