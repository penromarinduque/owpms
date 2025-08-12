@extends('layouts.master')

@section('title')
Edit Permittee
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Permittee</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permittees.index') }}">Permittees</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('permittees.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-user-edit me-1"></i>
            Edit Permittee here
        </div>
        <div class="card-body">
            @if(!empty($user))
        	<form method="POST" action="{{ route('permittees.update', [Crypt::encryptString($user_id)]) }}" onsubmit="disableSubmitButton('btn_update');">
        	    @csrf
        	    @if ($errors->any())
        	    <div class="alert alert-danger">
        	        <ul>
        	        	@foreach ($errors->all() as $error)
        	        		<li>{{ $error }}</li>
        	        	@endforeach
        	        </ul>
        	    </div>
        	    @endif
                <div>
                    <h6>Personal Information</h6>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="lastname" class="form-label mb-0">Lastname</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname" value="{{ $user->personalInfo->last_name }}">
                            @error('lastname')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="firstname" class="form-label mb-0">Firstname</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname" value="{{ $user->personalInfo->first_name }}">
                            @error('firstname')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="middlename" class="form-label mb-0">Middle Name</label>
                            <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Middle Name" value="{{ $user->personalInfo->middle_name }}">
                            @error('middlename')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2">
                            <label for="gender" class="form-label mb-0">Gender</label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="">-Gender-</option>
                                <option value="male" {{ $user->personalInfo->gender=='male' ? 'selected' : '' }} >Male</option>
                                <option value="female" {{ $user->personalInfo->gender=='female' ? 'selected' : '' }} >Female</option>
                            </select>
                            @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label for="contact_info" class="form-label mb-0">Contact Number</label>
                            <input type="text" class="form-control" name="contact_info" id="contact_info" placeholder="Contact Number" value="{{ $user->personalInfo->contact_no }}">
                            @error('contact_info')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label for="barangay_id" class="form-label mb-0">Address: Barangay / Municipality</label>
                            <select class="form-control select2" name="barangay_id" id="barangay_id" style="width: 100%;">
                                <option value="">- Barangay / Municipality -</option>
                                @forelse($barangays as $barangay)
                                @php $selected = ($user->personalInfo->barangay_id==$barangay->id) ? 'selected' : ''; @endphp
                                    <option value="{{$barangay->id}}" {{$selected}}>{{$barangay->barangay_name.' / '.$barangay->municipality_name.' / '.$barangay->province_name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('barangay_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- <div class="col-sm-4">
                            <label for="business_name" class="form-label mb-0">Business Name</label>
                            <input type="text" class="form-control" name="business_name" id="business_name" placeholder="Business Name" value="{{ $user->wfp()->wildlifeFarm->farm_name  }}">
                            @error('business_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div> --}}
                    </div>
                </div>
                <hr>
                <div >
                    <h6>Wildlife Farm Permit</h6>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="farm_name">Farm Name<b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="farm_name" id="farm_name" placeholder="Farm Name" value="{{ old('farm_name', $wfp->wildlifeFarm->farm_name) }}">
                            @error('farm_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="location" class="form-label mb-0">Location<b class="text-danger">*</b></label>
                            <select class="form-control select2" name="location" id="location" style="width: 100%;" required>
                                <option value="">- Barangay / Municipality -</option>
                                @forelse($barangays as $barangay)
                                    @php $sel_brgy = (old('location', $wfp->wildlifeFarm->location)==$barangay->id) ? 'selected' : ''; @endphp
                                    <option value="{{$barangay->id}}" {{$sel_brgy}}>{{$barangay->barangay_name.' / '.$barangay->municipality_name.' / '.$barangay->province_name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('location')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="size">Size<b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="size" id="size" placeholder="Size" value="{{ old('size', $wfp->wildlifeFarm->size) }}">
                            @error('size')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="height">Height<b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="height" id="height" placeholder="Height" value="{{ old('height', $wfp->wildlifeFarm->height) }}">
                            @error('height')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <input type="hidden" name="permit_type_wfp" id="permit_type_wfp" value="wfp">
                        <div class="col-sm-3">
                            <label for="permit_number_wfp">Permit Number<b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="permit_number_wfp" id="permit_number_wfp" placeholder="Permit Number" value="{{ old('permit_number_wfp', $wfp->permit_number) }}">
                            @error('permit_number')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="valid_from_wfp">Valid from<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="valid_from_wfp" id="valid_from_wfp" placeholder="Valid from" value="{{ old('valid_from_wfp', $wfp->valid_from->format('Y-m-d')) }}">
                            @error('valid_from_wfp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="valid_to_wfp">Valid to<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="valid_to_wfp" id="valid_to_wfp" placeholder="Valid to" value="{{ old('valid_to_wfp', $wfp->valid_to->format('Y-m-d')) }}">
                            @error('valid_to_wfp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="date_of_issue_wfp">Date of Issue<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="date_of_issue_wfp" id="date_of_issue_wfp" placeholder="Date of Issue" value="{{ old('date_of_issue_wfp', $wfp->date_of_issue->format('Y-m-d')) }}">
                            @error('date_of_issue_wfp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
    
                        <div class="col-sm-2">
                            <label for="wfp_document">Document</label>
                            <input type="file" accept="application/pdf" class="form-control" name="wfp_document" id="wfp_document" placeholder="WFP Document" value="{{ old('wfp_document') }}">
                            @error('wfp_document')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <h6>Wildlife Collection Permit Details</h6>
                    <div class="row mb-3">
                        <input type="hidden" name="permit_type_wcp" id="permit_type_wcp" value="wcp">
                        <div class="col-sm-3">
                            <label for="permit_number_wcp">Permit Number<b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="permit_number_wcp" id="permit_number_wcp" placeholder="Permit Number" value="{{ old('permit_number_wcp', $wcp->permit_number) }}">
                            @error('permit_number_wcp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="valid_from_wcp">Valid from<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="valid_from_wcp" id="valid_from_wcp" placeholder="Valid from" value="{{ old('valid_from_wcp' , $wcp->valid_from->format('Y-m-d')) }}">
                            @error('valid_from_wcp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="valid_to_wcp">Valid to<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="valid_to_wcp" id="valid_to_wcp" placeholder="Valid to" value="{{ old('valid_to_wcp', $wcp->valid_to->format('Y-m-d')) }}">
                            @error('valid_to_wcp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="date_of_issue_wcp">Date of Issue<b class="text-danger">*</b></label>
                            <input type="date" class="form-control" name="date_of_issue_wcp" id="date_of_issue_wcp" placeholder="Date of Issue" value="{{ old('date_of_issue_wcp', $wcp->date_of_issue->format('Y-m-d')) }}">
                            @error('date_of_issue_wcp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="wcp_document">Document</label>
                            <input type="file" accept="application/pdf" class="form-control" name="wcp_document" id="wcp_document" placeholder="WCP Document" value="{{ old('wcp_document') }}">
                            @error('wcp_document')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn_update" class="btn btn-success btn-block float-end"><i class="fas fa-save"></i> Save Changes</button>
        	</form>
            @else
            @endif
        </div>
    </div>
</div>
@endsection