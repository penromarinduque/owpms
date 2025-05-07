@extends('layouts.master')

@section('title')
LTP Fees
@endsection

@section('active-ltpfees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">LTP Fees</h2>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item" ><a href="{{ route('ltpfees.index') }}">LTP Fees</a></li>
        <li class="breadcrumb-item active">Edit New Fee</li>
    </ol>

    

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-money-check-alt me-2"></i>Edit Fee</div>
        </div>
        <div class="card-body">
            <form action="{{ route('ltpfees.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="fee_name" class="form-label">Fee Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fee_name" name="fee_name" placeholder="Fee Name" value="{{ old('fee_name') ?? $fee->fee_name }}">
                            @error('fee_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (Php) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" min="0" value="{{ old('amount') ?? $fee->amount }}">
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="legal_basis" class="form-label">Legal Basis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="legal_basis" name="legal_basis" placeholder="Legal Basis" value="{{ old('legal_basis') ?? $fee->legal_basis }}">
                            @error('legal_basis')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-2">    
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
