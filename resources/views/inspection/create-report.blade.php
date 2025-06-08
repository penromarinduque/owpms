@extends('layouts.master')

@section('title')
Generate Inspection Report
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Generate Inspection Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Generate Inspection Report</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-pdf me-1"></i>
            Inspection Report Template
        </div>
        <div class="card-body">
            <div class="row bg-light py-4 align-content-center justify-content-center">
                <div class="col-8 bg-white p-5">

                    <h3 class="text-center">INSPECTION REPORT OF WILDLIFE</h3><br>
        
                    <p>TO WHOM IT MAY CONCERN</p>
        
                    <p class="text-justify">
                        &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that on this date, the undersigned has undertaken the inspection of wildlife in the 
                        <span class="fw-bold"><u>&nbsp;&nbsp;DENR PENRO - Marinduque&nbsp;&nbsp;</u></span>
                         of <span class="fw-bold"><u>&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                         and has found the following wildlife.
                    </p>

                    <div class="px-5 py-2">
                        <table class="table border-0">
                            <thead class="border-0">
                                <tr class="border-0">
                                    <th scope="col" class="border-0">Kind of Species</th>
                                    <td width="20px" class="border-0"></td>
                                    <th width="100px" scope="col" class="text-end border-0">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @if(!empty($ltp_application->ltpApplicationSpecies))
                                    @foreach($ltp_application->ltpApplicationSpecies as $ltp_specie)
                                        @php
                                            $total += $ltp_specie->quantity;
                                        @endphp
                                        <tr>
                                            <td class="border-0 pb-0">{{ $ltp_specie->specie->specie_name }}</td>
                                            <td class="border-0 pb-0"></td>
                                            <td class="text-end border-0 pb-0">{{ number_format($ltp_specie->quantity, 0, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end border-0">Total</td>
                                    <td class="text-end border-0">{{ number_format($total, 0, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="text-justify">
                        &nbsp;&nbsp;&nbsp;&nbsp;The inspection was made in the presence of
                         <span class="fw-bold"><u>&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                         at the above address.
                    </p>

                    <br><br>

                    <div class="row ">
                        <div class="col-6 px-5 py-4">
                            <p class="text-center mb-0 fw-bold">{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}</p>
                            <hr class="my-0 border-dark text-dark">
                            <p class="text-center">(Signature of Witness)</p>
                        </div>
                        <div class="col-6 px-5 py-4">
                            <p class="text-center mb-0 fw-bold">{{ auth()->user()->personalInfo->getFullNameAttribute() }}</p>
                            <hr class="my-0 border-dark">
                            <p class="text-center">(Inspecting Officer)</p>
                        </div>
                        <div class="col-6 px-5 py-4">
                            <p class="text-center mb-0 fw-bold">&nbsp;</p>
                            <hr class="my-0 border-dark">
                            <p class="text-center">&nbsp;</p>
                        </div>
                        <div class="col-6 px-5 py-4">
                            <p class="text-center mb-0 fw-bold">Forest Ranger</p>
                            <hr class="my-0 border-dark">
                            <p class="text-center">(Designation)</p>
                        </div>
                    </div>

                    <h5 class="text-center">CERTIFICATE OF CONCURRENCE</h5>

                    <p class="text-justify">
                        &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the inspection report of 
                         <span class="fw-bold"><u>&nbsp;&nbsp;{{ auth()->user()->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                         of PENRO Marinduque, Region <span class="fw-bold"><u>&nbsp;&nbsp;IV-MIMAROPA&nbsp;&nbsp;</u></span>
                         is/are true and correct and has been done this <span class="fw-bold"><u>&nbsp;&nbsp;{{ $_helper->ordinal(now()->format('j')) }}&nbsp;&nbsp;</u></span>
                         day of <span class="fw-bold"><u>&nbsp;&nbsp;{{ now()->format('F Y') }}&nbsp;&nbsp;</u></span>.
                    </p>

                    <p>&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that this statement was given to me voluntarily and with neither coercion nor promise of reward from the personnel of the Department of Environment and Natural Resources.</p>

                    <br><br>

                    <div class="d-flex align-content-end justify-content-end">
                        <div class="" style="min-width: 200px">
                            <p class="text-center mb-0 fw-bold">
                                <select class="form-select select2" name="approver" id="approver">
                                    <option value="">-Select Approver-</option>
                                    @foreach($_user->getAllInternals() as $internal)
                                        <option value="{{ $internal->id }}">{{ $internal->personalInfo->getFullNameAttribute() }}</option>
                                    @endforeach
                                </select>
                            </p>
                            <hr class="my-0 border-dark">
                            <p class="text-center">(Designation)</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script>
    $(function(){
        $('.select2').select2();
    })
</script>   
@endsection