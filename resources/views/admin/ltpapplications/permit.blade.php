@extends('layouts.master')

@section('title')
Local Transport Permit
@endsection

{{-- @section('active-applications')
active
@endsection --}}

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">Local Transport Permit</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Applications</a></li>
        <li class="breadcrumb-item">Local Transport Permit</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-file me-1"></i>
            Local Transport Permit Template
        </div>
        <div class="card-body">
            <form class="row bg-light py-4 align-content-center justify-content-center" method="POST" action="{{ route('ltpapplication.createPermit', Crypt::encryptString($ltp_application->id)) }}" enctype="multipart/form-data">
                @csrf
                <div class="col-8 bg-white p-5 mb-4">
                    <p class="mb-0">Wildlife Transport</p>
                    <p class="mb-0">Permit No. <i>(Auto Generated)</i> </p>
                    <h3 class="text-center">LOCAL TRANSPORT PERMIT</h3><br>
        
                    <p>
                        &nbsp;&nbsp;Pursuant to the Republic Act 9147 dated July 30, 2001. Mr./Ms. 
                        <u class="fw-bold" style="text-transform: uppercase">&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u> of 
                        <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getAddressAttribute() }}&nbsp;&nbsp;</u>
                        is authorized to transport to 
                        <u class="fw-bold">&nbsp;&nbsp;Manila&nbsp;&nbsp;</u>
                        the following wildlife for <u class="fw-bold">&nbsp;&nbsp;export&nbsp;&nbsp;</u> purpose.
                    </p>

                    <hr style="
                        border: none;
                        border-top: 3px solid black;
                        border-image: repeating-linear-gradient(to right, black 0 10px, transparent 10px 20px);
                        border-image-slice: 1;
                        ">  
                    <table class="table border-0 align-middle">
                        <tbody>
                            <tr class="border-0">
                                <td class="text-center border-0 align-middle" colspan="3">Description</td>
                            </tr>
                            <tr class="border-0">
                                <td width="33%" class=" border-0 align-middle">Common/Scientific Name</th>
                                <td width="33%" class="text-center border-0 align-middle">Description (including parts, derivatives, marks, numbers, age and sex if any)</th>
                                <td width="" class="text-end border-0  align-middle">Quantity</th>
                            </tr>
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($ltp_application->ltpApplicationSpecies as $specie)
                                <tr>
                                    <td class="border-0 align-middle">{{ $specie->specie->specie_name }}</td>
                                    <td class="text-center border-0 align-middle">pupae</td>
                                    <td class="text-end border-0 align-middle">{{ number_format($specie->quantity, 0, '.', ',') }}</td>
                                    @php
                                        $count += $specie->quantity;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-end fw-bold border-0" colspan="3">Total &nbsp;&nbsp;{{ number_format($count, 0, '.', ',') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr style="
                        border: none;
                        border-top: 3px solid black;
                        border-image: repeating-linear-gradient(to right, black 0 10px, transparent 10px 20px);
                        border-image-slice: 1;
                        ">
                    <p>
                        &nbsp;&nbsp;The above mentioned specimens shall be transported by Air/Sea express on or before 
                        <input style="width: 150px;" type="date" name="transport_date" value="{{ $ltp_application->transport_date->format('Y-m-d') }}" class="form-control d-inline">
                        and have been inspected, verified and found in accordance with existing wildlife laws, rules and regulations.
                    </p>
                    <p>
                        &nbsp;&nbsp;Local Transport fee in the amount of <u class="fw-bold">&nbsp;&nbsp; &#x20B1; {{ number_format($ltp_application->paymentOrder->ltpFee->amount, 2, '.', ',') }}&nbsp;&nbsp;</u>
                        was paid under the DENR PENRO Official Receipt No. <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->paymentOrder->payment_reference }}&nbsp;&nbsp;</u>
                        dated <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->paymentOrder->issued_date->format('F d, Y') }}&nbsp;&nbsp;</u>.
                    </p>
                    <p>
                        &nbsp;&nbsp;This permit is not valid without the dry seal of the signing officer or if it contains erasure or alteration.
                    </p>

                    <div class="d-flex justify-content-end">
                        <div class="d-flex flex-column align-items-end">
                            <select name="approver" class="form-select text-center fw-bold select2 @error('approver') is-invalid @enderror" id="approver" onchange="approverChanged(event)">
                                @foreach ($_user->getAllInternals() as $user)
                                    <option value="{{ $user->id }}" data-designation="{{ $user->empPosition ? $user->empPosition->position : '' }}" class="fw-bold" {{ $user->id == old('user_id') ? 'selected' : ($user->id == 11 ? 'selected' : '') }} style="text-transform: uppercase"><u>&nbsp;&nbsp;{{ $user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></option>
                                @endforeach
                            </select>
                            @error('approver')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <hr class="my-0 border-dark">
                            <input type="text" id="approver_designation" name="approver_designation" class="form-control  @error('approver_designation') is-invalid @enderror" placeholder="Designation">
                            @error('approver_designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <br>

                <div class="col-8 ">
                    <h6>Initials</h6>
                    <div class="row">
                        {{-- <div class="col-sm-6">
                            <select name="chief_rps" id="chief_rps" class="form-select select2 @error('chief_rps') is-invalid @enderror">
                                <option value="">-Select Chief RPS-</option>
                                @foreach ($_user->getAllInternals() as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == old('chief_rps') ? 'selected' : '' }} style="text-transform: uppercase"><u>&nbsp;&nbsp;{{ $user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></option>
                                @endforeach
                            </select>
                            @error('chief_rps')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>                                
                            @enderror
                        </div> --}}
                        <div class="col-sm-6">
                            <select name="chief_tsd" id="chief_tsd" class="form-select select2 @error('chief_tsd') is-invalid @enderror">
                                <option value="">-Select Chief TSD-</option>
                                @foreach ($_user->getAllInternals() as $user)
                                    <option value="{{ $user->id }}" {{ old('chief_tsd') && old('chief_tsd') == $user->id ? 'selected' : ($user->id == 12 ? 'selected' : '') }} style="text-transform: uppercase"><u>&nbsp;&nbsp;{{ $user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></option>
                                @endforeach
                            </select>
                            @error('chief_tsd')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>                                
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-1">
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.returnApplication')
@include('components.confirm')
@endsection

@section('script-extra')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#approver').change();
        });

        function approverChanged(event) {
            console.log("changed")
            $('#approver_designation').val($(event.target).find(':selected').attr('data-designation'));
        }
    </script>
@endsection
