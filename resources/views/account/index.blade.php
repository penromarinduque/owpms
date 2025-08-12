@extends('layouts.master')

@section('title')
Account
@endsection

@section('content')
<div class="container px-4">
    <h2 class="mt-4">Account</h2>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-circle me-2"></i>Account Info</div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Username</small></label>
                    <h6>{{ $user->username }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Email</small></label>
                    <h6>{{ $user->email }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">User Type</small></label>
                    <h6>{{ Str::title($user->usertype) }}</h6>
                </div>
            </div>
            <div id="list-example" class="list-group">
                <a class="list-group-item list-group-item-action" href="{{ route('password.request') }}"><i class="fas fa-key me-2"></i>Reset Password</a>
                <a class="list-group-item list-group-item-action" href="#list-item-2"><i class="fas fa-user-edit me-2"></i>Change Username</a>
                <a class="list-group-item list-group-item-action" href="#list-item-3"><i class="fas fa-envelope me-2"></i>Change Email</a>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user me-2"></i>Personal Info</div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">First Name</small></label>
                    <h6>{{ $user->personalInfo->first_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Middle Name</small></label>
                    <h6>{{ $user->personalInfo->middle_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Last Name</small></label>
                    <h6>{{ $user->personalInfo->last_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Gender</small></label>
                    <h6>{{ ucfirst($user->personalInfo->gender) }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Email</small></label>
                    <h6>{{ $user->personalInfo->email }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Contact Number</small></label>
                    <h6>{{ ucfirst($user->personalInfo->contact_no) }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Address</small></label>
                    <h6>{{ $user->personalInfo->barangay->barangay_name }}, {{ $user->personalInfo->barangay->municipality->municipality_name }}, {{ $user->personalInfo->barangay->municipality->province->province_name }}</h6>
                </div>
            </div>
            <div id="list-example" class="list-group">
                <a class="list-group-item list-group-item-action" href="{{ route('account.personalInfo.edit', [Crypt::encryptString($user->personalInfo->id)]) }}"><i class="fas fa-user-edit me-2"></i>Update Personal Info</a>
            </div>
        </div>
    </div>
    @if ($user->usertype == 'permittee')
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-id-card me-2"></i>Permittee Info</div>
        </div>
        <div class="card-body">
            <h5>Wildlife Collectors Permit</h5>
            <div class="row mb-3">
                @php
                    $wcp = $user->wcp();
                @endphp
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Permit No.</small></label>
                    <h6>{{ $wcp->permit_number }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid From</small></label>
                    <h6>{{ $wcp->valid_from->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid To</small></label>
                    <h6>{{ $wcp->valid_to->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Date Issued</small></label>
                    <h6>{{ $wcp->date_of_issue->format("F d, Y") }}</h6>
                </div>
            </div>
            <hr>
            <h5>Wildlife Farm Permit</h5>
            <div class="row mb-3">
                @php
                    $wfp = $user->wfp();
                @endphp
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Permit No.</small></label>
                    <h6>{{ $wfp->permit_number }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid From</small></label>
                    <h6>{{ $wcp->valid_from->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid To</small></label>
                    <h6>{{ $wcp->valid_to->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Date Issued</small></label>
                    <h6>{{ $wcp->date_of_issue->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Farm Name</small></label>
                    <h6>{{ $wfp->wildlifeFarm->farm_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Location</small></label>
                    <h6>{{ $wfp->wildlifeFarm->barangay->barangay_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->province->province_name }}</h6>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection