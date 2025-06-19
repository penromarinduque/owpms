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
        {{-- @can('accept', $ltp_application)
            <button class="btn btn-sm btn-success"data-bs-toggle="modal" data-bs-target="#acceptApplicationModal"><i class="fas fa-check me-1"></i>Accept Application</button>
        @endcan --}}
        @can('review', $ltp_application)
            <button class="btn btn-sm btn-success"data-bs-toggle="modal" data-bs-target="#reviewApplicationModal"><i class="fas fa-check me-1"></i>Reviewed</button>
        @endcan
        @can('return', $ltp_application)
            <button class="btn btn-sm btn-warning" onclick="showReturnApplicationModal({{ $ltp_application }})"><i class="fas fa-arrow-left me-1"></i>Return Application</button>
        @endcan
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
                    <label>Application Status.</label><br>
                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
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
                        <h6><a href="{{ route('apprequirements.view', ['id' => Crypt::encryptString($attachment->id)]) }}" target="_blank">View Attachment</a></h6>
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

@endsection

@section('includes')
    @include('components.reviewConfirmationModal')
    @include('components.returnApplication')
    @include('components.confirm')
@endsection
