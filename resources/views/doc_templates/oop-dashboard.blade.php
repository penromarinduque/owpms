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
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('myapplication.index') }}">My Applications</a></li>
        <li class="breadcrumb-item active">Create Order of Payment</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="float-end">
                <a href="{{ route('myapplication.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Order of Payment Details
        </div>
        <div class="card-body">
            <form action="{{ url('doc_templates/oop-form') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="select_application" class="form-label mb-0">Select Application<b class="text-danger">*</b></label>
                        <select class="form-control select2" name="select_application" id="select_application" style="width: 100%;">
                            <option value="1" selected>Chainsaw Registration - APP123456</option>
                            <option value="2">Wildlife Farm Permit - APP789012</option>
                            <option value="3">Wildlife Collector Permit - APP345678</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="application_number" class="form-label mb-0">Application Number</label>
                        <input type="text" class="form-control" value="APP123456" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label for="application_id" class="form-label mb-0">Application ID</label>
                        <input type="text" class="form-control" value="CSR-2024-001" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <label for="applicant_name" class="form-label mb-0">Applicant Name</label>
                        <input type="text" class="form-control" value="Juan Dela Cruz" readonly>
                    </div>
                </div>

                <div class="float-end mt-4">
                    <button type="button" class="btn btn-primary btn-block mr-1"><i class="fas fa-eye"></i> Preview</button>
                    <a href="{{ url('oop-form') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-arrow-right"></i> Next
                    </a>
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
    });
</script>
@endsection