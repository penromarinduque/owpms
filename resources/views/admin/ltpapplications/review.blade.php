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
                    <h6>{{format_application_status($ltp_application->application_status)}}</h6>
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
                <div class="col-lg-3 col-sm-6">
                    <label>Application No.</label>
                    <h6>{{$ltp_application->application_no}}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection