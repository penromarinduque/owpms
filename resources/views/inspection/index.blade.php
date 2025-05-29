@extends('layouts.master')

@section('title')
Inspection
@endsection


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inspection</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">LTP Applications</a></li>
        <li class="breadcrumb-item active">Inspection</li>
    </ol>

    <div class="row align-items-stretch mb-2">
        <div class="col-md mb-2">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-list me-1"></i>
                    Application Details
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr>
                                <th>Application No.:</th><td>{{ $ltp_application->application_no }}</td>
                            </tr>
                            <tr>
                                <th>Purpose:</th><td>{{ $ltp_application->purpose }}</td>
                            </tr>
                            <tr>
                                <th>Destination:</th><td>{{ $ltp_application->destination }}</td>
                            </tr>
                            <tr>
                                <th>Transport Date.:</th><td>{{ $ltp_application->transport_date->format("F d, Y") }} <span class="text-secondary">({{ $ltp_application->transport_date->diffForHumans() }})</span></td>
                            </tr>
                            <tr>
                                <th>Status:</th><td>{{ $ltp_application->application_status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php
            $permittee = $ltp_application->permittee;
            $farm = $ltp_application->permittee->user->wfp()->wildlifeFarm;
        @endphp
        <div class="col-md mb-2">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-list me-1"></i>
                    Permittee Details
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr>
                                <th width="50%">Permittee Name:</th>
                                <td width="50%">{{ $permittee->user->personalInfo->first_name . ' ' . $permittee->user->personalInfo->last_name }}</td>
                            </tr>
                            <tr>
                                <th width="50%">Farm Name:</th>
                                <td width="50%">{{ $farm->farm_name }}</td>
                            </tr>
                            <tr>
                                <th width="50%">Location:</th>
                                <td width="50%">{{ $farm->barangay->barangay_name . ', ' . $farm->barangay->municipality->municipality_name . ', ' . $farm->barangay->municipality->province->province_name }}</td>
                            </tr>
                            <tr>
                                <th width="50%">Email:</th>
                                <td width="50%">{{ $permittee->user->personalInfo->email }}</td>
                            </tr>
                            <tr>
                                <th width="50%">Contact No.:</th>
                                <td width="50%">{{ $permittee->user->personalInfo->contact_no }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Inspection Proofs
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-camera me-1"></i>Inspection Photos</h6>
                    @can('uploadInspectionProof', $ltp_application)
                        <div class="d-flex justify-content-end align-items-center gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-camera me-1"></i>Take Photo
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Add Photo
                            </button>
                        </div>
                    @endcan
                </div>
                <img class="d-block mx-auto" src="{{ asset('images/undraw_photos_09tf.png') }}" alt="" width="150px">
                
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-video me-1"></i>Inspection Video</h6>
                    @can('uploadInspectionProof', $ltp_application)
                        <button type="button" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Video
                        </button>
                    @endcan
                </div>
                <img class="d-block mx-auto" src="{{ asset('images/undraw_video-files_cxl9.png') }}" alt="" width="150px">
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Submit Inspection Proofs</button>
            </div>
        </div>
    </div>
</div>
@endsection