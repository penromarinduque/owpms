@extends('layouts.master')

@section('title')
My Applications
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Applications</a></li>
        <li class="breadcrumb-item">View</li>
    </ol>

    <div class="d-flex justify-content-end gap-2 mb-2">
        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('myapplication.printRequestLetter', Crypt::encryptString($ltp_application->id)) }}"><i class="fas fa-print me-1"></i>Print Request Letter</a>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-alt me-1"></i> Permits
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="{{ route('permittees.viewpermit', Crypt::encryptString($ltp_application->permittee->user->wcp()->id)) }}" target="_blank"><i class="fas fa-file-pdf me-1"></i> Wildlife Collectors Permit</a></li>
                <li><a class="dropdown-item" href="{{ route('permittees.viewpermit', Crypt::encryptString($ltp_application->permittee->user->wfp()->id)) }}" target="_blank"><i class="fas fa-file-pdf me-1"></i> Wildlife Farm Permit</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownActionButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-alt me-1"></i> Actions
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownActionButton">
                @if ($ltp_application->application_status == 'draft')
                    <li><a class="dropdown-item" href="#" onclick="showSubmitApplicationModal('{{ route('myapplication.submit', Crypt::encryptString($ltp_application->id)) }}')"><i class="fas fa-upload me-1"></i> Submit Application</a></li>
                @endif
                @can('downloadLtp', $ltp_application)
                    <li><a class="dropdown-item" href="{{ route('ltpapplication.downloadLtp', Crypt::encryptString($ltp_application->id)) }}" target="_blank"><i class="fas fa-download me-1"></i> Download Local Transport Permit</a></li>
                @endcan
            </ul>
        </div>
        
    </div>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-list me-1"></i>
            LTP Application
        </div>
    <div class="card-body">

            @if (auth()->user()->usertype == "permittee")
                <div class="alert alert-info" role="alert">
                    <h6 class="d-flex align-items-center"><i class="fas fa-sticky-note me-2"></i> <strong>Important Notes:</strong> <span class="fw-normal ms-1">Applications can be fulfilled in two ways: a Hybrid Application and a Full Online Application.</span></h6>
                    <h6>For Full Online</h6>
                    <ul>
                        <li>You can submit your application online with the required attachments and we will notify you when your application is accepted.</li>
                    </ul>
                    <h6>For Hybrid Application</h6>
                    <ul>
                        <li>You can submit your application at our office with the required attachments. Ensure you check the requirements on our checklist and have all necessary documents before submission.</li>
                        <li>Please submit two original copies of the application letter, WCP, WFP, and scanned requirements attached to your application for official acceptance.</li>
                    </ul>
                </div>
            @endif

            
            <h6><i class="fas fa-id-card me-1"></i> Permittee Details</h6>
            <div class="bg-light p-3 rounded-2">
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
            </div>

            <br>

            <h6><i class="fas fa-file-alt me-1"></i> Application Details</h6>
            <div class="bg-light p-3 rounded-2">
                <div class="row mb-3 ">
                    <div class="col-lg-3 col-sm-6">
                        <label>Application No.</label>
                        <h6>{{$ltp_application->application_no}}</h6>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <label>Application Status.</label>
                        <h6>
                            <span class="badge  bg-{{$_helper->setApplicationStatusBgColor($ltp_application->application_status)}}">{{$_helper->formatApplicationStatus($ltp_application->application_status)}}</span>
                        </h6>
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
            </div>

            <br>

            <h6><i class="fas fa-paperclip me-2"></i>Attachments</h6>
            
            <div class="bg-light p-3 rounded-2">
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
</div>
@endsection

@section('includes')
    @include('components.returnApplication')
    @include('components.confirm')
    @include('components.submitApplication')
@endsection
