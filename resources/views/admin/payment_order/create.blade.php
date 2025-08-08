@extends('layouts.master')

@section('title')
Order of Payment
@endsection

@section('active-myapplication')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4">Create Order of Payment and Assesment of Fees and Charges</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">Applications</a></li>
        <li class="breadcrumb-item active">Create Order of Payment</li>
    </ol>

    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Form
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('paymentorder.store') }}">
                @csrf
                <input type="hidden" name="ltp_application_id" value="{{ $ltp_application->id }}">
                <input type="hidden" name="ltp_fee_id" value="{{ $ltp_fee->id }}">
                {{-- <input type="hidden" name="prepared_by" value="{{ $signatories['prepare']->user_id }}">
                <input type="hidden" name="approved_by" value="{{ $signatories['approve']->user_id }}"> --}}
                @error('ltp_fee_id')<p class="text-danger">{{ $message }}</p>@enderror
                @error('ltp_application_id')<p class="text-danger">{{ $message }}</p>@enderror
                @error('prepared_by')<p class="text-danger">{{ $message }}</p>@enderror
                @error('approved')<p class="text-danger">{{ $message }}</p>@enderror
                <h6>Assesment of Fees and Charges Signatories</h6>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        Prepared By:
                        <select name="prepared_by" id="prepared_by" class="form-select select2" onchange="preparedByChanged()">
                            <option value="">Select Signatory</option>
                            @foreach ($_user->getAllInternals() as $user)
                                <option value="{{ $user->id }}" {{ old('prepared_by') == $user->id ? 'selected' : (8 == $user->id ? 'selected' : '') }} data-position="{{ $user->empPosition ? $user->empPosition->position : '' }}" data-name="{{ $user->personalInfo->getFullNameAttribute() }}">{{ strtoupper($user->personalInfo->getFullNameAttribute()) }}</option>
                            @endforeach
                        </select>
                        @error('prepared_by')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2 col-md-6">
                        Approved By:
                        <select name="approved_by" id="approved_by" class="form-select" onchange="approvedByChanged()">
                            <option value="">Select Signatory</option>
                            @foreach ($_user->getAllInternals() as $user)
                                <option value="{{ $user->id }}" {{ old('prepared_by') == $user->id ? 'selected' : (12 == $user->id ? 'selected' : '') }} data-position="{{ $user->empPosition ? $user->empPosition->position : '' }}" data-name="{{ $user->personalInfo->getFullNameAttribute() }}">{{ strtoupper($user->personalInfo->getFullNameAttribute()) }}</option>
                            @endforeach
                        </select>
                        @error('approved_by')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <h6>Order of Payment Signatories</h6>
                <div class="row">
                    <div class="mb-2 col-md-6">
                        Approved By:
                        <select name="oop_approved_by" id="oop_approved_by" class="form-select select2" onchange="oopApprovedByChanged()">
                            <option value="">Select Signatory</option>
                            @foreach ($_user->getAllInternals() as $user)
                                <option value="{{ $user->id }}" {{ old('oop_approved_by') == $user->id ? 'selected' : (17 == $user->id ? 'selected' : '') }} data-position="{{ $user->empPosition ? $user->empPosition->position : '' }}" data-name="{{ $user->personalInfo->getFullNameAttribute() }}">{{ strtoupper($user->personalInfo->getFullNameAttribute()) }}</option>
                            @endforeach
                        </select>
                        @error('oop_approved_by')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-check"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-receipt me-1"></i>
            Previews
        </div>
        <div class="card-body overflow-auto" style="max-height: 500px;">
            {{-- Assesment if Fees and Charges --}}
            <div style="width: 800px" class=" mx-auto mb-4 p-0">
                <div class="row mx-5 m-">
                    <div class="col-auto">
                        <img src="{{ asset('images/logo-small.png') }}" width="60" alt="">
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
                            <td width="25%" style="border-bottom: 1px solid black" class="text-center fst-italic" >(Generated Bill No.)</td>
                        </tr>
                        <tr>
                            <td ></td>
                            <td ></td>
                            <td  class="fw-bold">Date:</td>
                            <td  style="border-bottom: 1px solid black" class="text-center" >{{ date('F d, Y') }}</td>
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
                            <td width="20%" style="border-right: 1px solid black" class="text-center">Section 9</td>
                            <td width="39%" style="border-right: 1px solid black" class="text-center">Fund 151</td>
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
                            <td width="35%" style="border-bottom: 1px solid black" class="text-center fw-bold" id="prepared_by__td"></td>
                            <td width="15%"></td>
                            <td width="35%" style="border-bottom: 1px solid black" class="text-center fw-bold" id="approved_by__td"></td>
                        </tr>
                        <tr>
                            <td width="15%"></td>
                            <td width="35%"  class="text-center" id="prepared_by_position__td"></td>
                            <td width="15%"></td>
                            <td width="35%"  class="text-center" id="approved_by_position__td"></td>
                        </tr>
                    </table>
                </div>
                <table style="width: 100%;">
                    <tr>
                        <td width="13%" class="fw-bold">Received:</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="2%"></td>
                        <td width="13%" class="fw-bold">Tracking No.</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="18%"></td>
                    </tr>
                    <tr>
                        <td width="13%" class="fw-bold">Date:</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="2%"></td>
                        <td width="13%" class="fw-bold">Time:</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="18%"></td>
                    </tr>
                    <tr><td></td></tr>
                    <tr>
                        <td width="13%" class="fw-bold">Released Date:</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="2%"></td>
                        <td width="13%" class="fw-bold">Time:</td>
                        <td width="17%" style="border-bottom: 1px solid black"></td>
                        <td width="18%"></td>
                    </tr>
                </table>
            </div>

            <hr>

            {{-- Order of Payment --}}
            <div style="width: 800px;font-family: 'Times New Roman', Times, serif" class=" mx-auto ">
                <p class="text-end mb-0"><i>Appendix 28</i></p>
                <div style=" border: 2px solid black; " class=" mx-auto mb-4 p-0">
                    <br>
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
            </div>
        </div>
    </div>
</div>
@endsection


@section('script-extra')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('#approved_by').change();
        $('#prepared_by').change();
        $('#oop_approved_by').change();
    });

    function generateBillNo() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hour = String(now.getHours()).padStart(2, '0');
        const minute = String(now.getMinutes()).padStart(2, '0');
        const second = String(now.getSeconds()).padStart(2, '0');
        const milliseconds = String(now.getMilliseconds()).padStart(3, '0');
        const randomNumber = Math.floor(1000 + Math.random() * 9000); // 4-digit random number

        $('#bill_no').val(`${year}-${month}-${day}${hour}${minute}${second}${milliseconds}${randomNumber}`);
    }

    function preparedByChanged() {
        console.log($('#prepared_by option:selected').data('position'));
        $("#prepared_by__td").text($('#prepared_by option:selected').data('name'));
        $('#prepared_by_position__td').text($('#prepared_by option:selected').data('position'));
    }

    function approvedByChanged() {
        console.log($('#approved_by option:selected').data('position'));
        $("#approved_by__td").text($('#approved_by option:selected').data('name'));
        $('#approved_by_position__td').text($('#approved_by option:selected').data('position'));

    }
    
    function oopApprovedByChanged() {
        console.log($('#oop_approved_by option:selected').data('position'));
        $("#oop_approved_by__td").text($('#oop_approved_by option:selected').data('name'));
        $('#oop_approved_by_position__td').text($('#oop_approved_by option:selected').data('position'));

    }
</script>
@endsection