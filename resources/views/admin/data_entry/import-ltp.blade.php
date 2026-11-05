@extends('layouts.master')

@section('title')
Import LTP
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">Import LTP</h1>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-upload me-1"></i>
            Import LTP
        </div>
        <div class="card-body">
            <p>In case of system maintenance or downtime and the application proceeds manually use this form to import LTP data.</p>
            <form action="{{ route('migration.save-ltp') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Application No. -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="application_no" class="form-label">Application No.</label>
                            <input type="text" class="form-control @error('application_no') is-invalid @enderror" id="application_no" name="application_no" value="{{ old('application_no') }}" required>
                            @error('application_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order No. -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="order_no" class="form-label">Order No.</label>
                            <input type="text" class="form-control @error('order_no') is-invalid @enderror" id="order_no" name="order_no" value="{{ old('order_no') }}" required>
                            @error('order_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Serial No. -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="serial_no" class="form-label">Serial No.</label>
                            <input type="text" class="form-control @error('serial_no') is-invalid @enderror" id="serial_no" name="serial_no" value="{{ old('serial_no') }}" required>
                            @error('serial_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- No. -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="no" class="form-label">Payment Tracking No.</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" value="{{ old('no') }}" required>
                            @error('no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Reference -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="payment_reference" class="form-label">Payment Reference</label>
                            <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}" required>
                            @error('payment_reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Receipt Date -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="receipt_date" class="form-label">Receipt Date</label>
                            <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" id="receipt_date" name="receipt_date" value="{{ old('receipt_date') }}" required>
                            @error('receipt_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Permit Number -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="permit_number" class="form-label">Permit Number</label>
                            <input type="text" class="form-control @error('permit_number') is-invalid @enderror" id="permit_number" name="permit_number" value="{{ old('permit_number') }}" required>
                            @error('permit_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Permit No. No. -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="permit_no_no" class="form-label">Permit Tracking No.</label>
                            <input type="text" class="form-control @error('permit_no_no') is-invalid @enderror" id="permit_no_no" name="permit_no_no" value="{{ old('permit_no_no') }}" required>
                            @error('permit_no_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Date Approved -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="date_approved" class="form-label">Date Approved</label>
                            <input type="date" class="form-control @error('date_approved') is-invalid @enderror" id="date_approved" name="date_approved" value="{{ old('date_approved') }}" required>
                            @error('date_approved')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Date Released -->
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="mb-3">
                            <label for="date_released" class="form-label">Date Released</label>
                            <input type="date" class="form-control @error('date_released') is-invalid @enderror" id="date_released" name="date_released" value="{{ old('date_released') }}" required>
                            @error('date_released')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection