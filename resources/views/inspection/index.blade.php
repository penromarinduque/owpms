@extends('layouts.master')

@section('title')
Inspections
@endsection


@section('content')
<style>
    .image-proof-con{
        transition: all 0.2s ease-in-out;
    }
    .image-proof-con:hover{
        /* background-color: rgb(209, 209, 209); */
        /* opacity: 0.8; */
        scale: 1.1;

    }
</style>
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0"><i class="fas fa-camera me-1"></i>Inspection Photos</h6>
                    @can('uploadInspectionProof', $ltp_application)
                        <div class="d-flex justify-content-end align-items-center gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-camera me-1"></i>Take Photo
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="showUploadInspectionPhotosModal('{{ route('inspection.uploadPhotos', Crypt::encryptString($ltp_application->id)) }}')">
                                <i class="fas fa-plus me-1"></i>Add Photo
                            </button>
                        </div>
                    @endcan
                </div>
                @if ($images->count() > 0)
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <strong>Note:</strong> Please ensure all photos are clear and relevant to the inspection.
                        </div>
                    </div>
                    <div class="row justify-content-start align-items-stretch p-2 gap-1">
                        @foreach ($images as $image)
                            <div class="col-5 col-sm-4 col-md-3 col-lg-2">
                                <div class="card border-0 shadow-sm h-100 image-proof-con">
                                    <img 
                                        src="{{ route('inspection.viewPhoto', [
                                            'ltp_application_id' => Crypt::encryptString($ltp_application->id),
                                            'id' => Crypt::encryptString($image->id)
                                        ]) }}" 
                                        class="card-img-top img-fluid rounded" 
                                        alt="Photo"
                                        style="object-fit: cover; height: 150px;">
                                    <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download me-1"></i>Download
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirDeleteModal ('{{ route('inspection.deletePhoto',['id' => Crypt::encryptString($image->id), 'ltp_application_id' => Crypt::encryptString($ltp_application->id)]) }}' ,{{$image->id}}, 'Are you sure you want to delete this photo?','Delete Photo')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <img class="d-block mx-auto" src="{{ asset('images/undraw_photos_09tf.png') }}" alt="" width="150px">
                @endif
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

@section('includes')
    @include('components.uploadInspectionPhotos')
    @include('components.confirmDelete')
@endsection