@extends('layouts.master')

@section('title')
Order of Payment
@endsection

@section('active-myapplication')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create Order of Payment Form</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">Applications</a></li>
        <li class="breadcrumb-item active">Create Order of Payment</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Order of Payment Form
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('paymentorder.store') }}">
                @csrf
                <input type="hidden" name="ltp_application_id" value="{{ $ltp_application->id }}">
                <input type="hidden" name="ltp_fee_id" value="{{ $ltp_fee->id }}">
                <input type="hidden" name="prepared_by" value="{{ $signatories['prepare']->user_id }}">
                <input type="hidden" name="approved_by" value="{{ $signatories['approve']->user_id }}">
                @error('ltp_fee_id')<p class="text-danger">{{ $message }}</p>@enderror
                @error('ltp_application_id')<p class="text-danger">{{ $message }}</p>@enderror
                @error('prepared_by')<p class="text-danger">{{ $message }}</p>@enderror
                @error('approved')<p class="text-danger">{{ $message }}</p>@enderror

                {{-- <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Bill No. <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="" name="bill_no" id="bill_no">
                            <button class="btn btn-sm btn-outline-primary" type="button" onclick="generateBillNo();"><i class="fas fa-redo me-1"></i>Generate</button>
                        </div>
                    </div>
                </div> --}}
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  value="{{ $_ltp_application->getWildlifeFarmLocation($ltp_application->permittee->user->id) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Nature of Application <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="LTP Application">
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Fees and Charges</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Legal Basis</th>
                                        <th style="width: 50%">Description</th>
                                        <th style="width: 20%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $ltp_fee->legal_basis }}
                                        </td>
                                        <td>
                                            {{ $ltp_fee->fee_name }}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" value="{{ $ltp_fee->amount }}" step="0.01">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total:</td>
                                        <td>₱ {{ number_format($ltp_fee->amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Prepared By</h6>
                                <div class="border-bottom mb-2 py-2">
                                    <select class="form-select select2 @error('prepared_by') is-invalid @enderror" name="prepared_by" id="prepared_by" onchange="preparedByChanged()">
                                        <option value="">Select Prepared By</option>
                                        @foreach ($_user->getAllInternals() as $user)
                                            <option value="{{ $user->id }}" {{ old('prepared_by') == $user->id ? 'selected' : (auth()->user()->id == $user->id ? 'selected' : '') }} data-position="{{ $user->empPosition ? $user->empPosition->position : '' }}">{{ strtoupper($user->personalInfo->getFullNameAttribute()) }}</option>
                                        @endforeach
                                    </select>
                                    @error('prepared_by')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="">
                                    <input class="form-control text-center @error('prepared_by_position') is-invalid @enderror" type="text" name="prepared_by_position" id="prepared_by_position" value="{{ old('prepared_by_position', 'Inspecting Officer') }}">
                                    @error('prepared_by_position')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Approved By</h6>
                                <div class="border-bottom mb-2 py-2">
                                    <select class="form-select select2 @error('approved_by') is-invalid @enderror" name="approved_by" id="approved_by" onchange="approvedByChanged()">
                                        <option value="">Select Approved By</option>
                                        @foreach ($_user->getAllInternals() as $user)
                                            <option value="{{ $user->id }}" {{ old('approved_by') == $user->id ? 'selected' : '' }} data-position="{{ $user->empPosition ? $user->empPosition->position : '' }}">{{ strtoupper($user->personalInfo->getFullNameAttribute()) }}</option>
                                        @endforeach
                                    </select>
                                    @error('approved_by')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="">
                                    <input class="form-control text-center @error('approved_by_position') is-invalid @enderror" type="text" name="approved_by_position" id="approved_by_position">
                                    @error('approved_by_position')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-check"></i>
                        Create Order of Payment 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('script-extra')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('#approved_by_position').change();
        $('#prepared_by_position').change();
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
        $('#prepared_by_position').val($('#prepared_by option:selected').data('position'));
    }

    function approvedByChanged() {
        console.log($('#approved_by option:selected').data('position'));
        $('#approved_by_position').val($('#approved_by option:selected').data('position'));
    }
</script>
@endsection


