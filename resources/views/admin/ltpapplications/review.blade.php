@extends('layouts.master')

@section('title')
LTP Application
@endsection

@section('active-applications')
active
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Applications</a></li>
        <li class="breadcrumb-item">Review</li>
    </ol>

    <div class="d-flex justify-content-end gap-2 mb-2">
        <button class="btn btn-sm btn-success"data-bs-toggle="modal" data-bs-target="#acceptApplicationModal"><i class="fas fa-check me-1"></i>Accept Application</button>
        <button class="btn btn-sm btn-warning" onclick="showReturnApplicationModal({{ $ltp_application }})"><i class="fas fa-arrow-left me-1"></i>Return Application</button>
    </div>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-list me-1"></i>
            LTP Application
        </div>
        <div class="card-body">
            <h5>Permittee Details</h5>
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label>Permittee Name</label>
                    <h6>{{$permittee->user->personalInfo->first_name}} {{$permittee->user->personalInfo->middle_name}} {{$permittee->user->personalInfo->last_name}}</h6>
                </div>
                <div class="col-12"></div>
                <div class="col-lg-3 col-sm-6">
                    <label>Wildlife Collectors Permit</label>
                    <h6>{{$permittee->user->wcp()->permit_number}} </h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>WCP Status</label>
                    <h6>{{$permittee->user->wcp()->getValidity()}} </h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Wildlife Farm Permit</label>
                    <h6>{{$permittee->user->wfp()->permit_number}} </h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>WFP Status</label>
                    <h6>{{$permittee->user->wfp()->getValidity()}} </h6>
                </div>
                <div class="col-12"></div>
            </div>

            <hr>
            <h5>Application Details</h5>
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label>Application No.</label>
                    <h6>{{$ltp_application->application_no}}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Application Status.</label>
                    <h6>{{$_helper->formatApplicationStatus($ltp_application->application_status)}}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Date Applied</label>
                    <h6>{{$ltp_application->application_date->format('F d, Y')}}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Transport Date</label>
                    <h6>{{$ltp_application->transport_date->format('F d, Y')}}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Purpose</label>
                    <h6>{{$ltp_application->purpose}}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label>Destination</label>
                    <h6>{{$ltp_application->destination}}</h6>
                </div>

            </div>

            <hr>
            <h5>Attachments</h5>
            <div class="row mb-3">
                @forelse($ltp_application->attachments as $attachment)
                    <div class="col-lg-3 col-sm-6">
                        <label>{{ $attachment->ltpRequirement->requirement_name }}</label>
                        <h6><a href="{{ asset('storage/'.$attachment->file_path) }}" target="_blank">View Attachment</a></h6>
                    </div>
                @empty
                    <div class="col-lg-3 col-sm-6">
                        <label>No Attachments Submitted</label>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="acceptApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('ltpapplication.accept', Crypt::encryptString($ltp_application->id)) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="title">Accept Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please double check if all the required attachments are physically submitted and complete before accepting the application. This cannot be undone.</p>
                @foreach ($ltp_requirements as $req)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="req_{{ $req->id }}" name="req[]" value="{{ $req->id }}" >
                        <label class="form-check-label" for="req_{{ $req->id }}">
                            {{ $req->requirement_name }} 
                            @if ($req->is_mandatory)
                                <span class="text-danger">*</span>
                            @endif  
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-submit">Accept</button>
            </div>
        </form>
    </div>
</div>

@include('components.returnApplication')
@include('components.confirm')
@endsection
