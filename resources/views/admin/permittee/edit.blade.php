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
            @if(!empty($permittee))
        	<form method="POST" action="{{ route('permittees.update', [Crypt::encrypt($permittee_id)]) }}" onsubmit="disableSubmitButton('btn_update');">
        	@csrf
        		<div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="lastname" class="form-label mb-0">Lastname</label>
                		<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname" value="{{ $permittee->lastname }}">
                        @error('lastname')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="firstname" class="form-label mb-0">Firstname</label>
                		<input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname" value="{{ $permittee->firstname }}">
                        @error('firstname')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="middlename" class="form-label mb-0">Middle Name</label>
                		<input type="text" class="form-control" name="middlename" id="middlename" placeholder="Middle Name" value="{{ $permittee->middlename }}">
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
                			<option value="male" {{ $permittee->gender=='male' ? 'selected' : '' }} >Male</option>
                			<option value="female" {{ $permittee->gender=='female' ? 'selected' : '' }} >Female</option>
                		</select>
                        @error('gender')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-3">
                		<label for="contact_info" class="form-label mb-0">Contact Number</label>
                		<input type="text" class="form-control" name="contact_info" id="contact_info" placeholder="Contact Number" value="{{ $permittee->contact_info }}">
                        @error('contact_info')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-3">
                		<label for="barangay_id" class="form-label mb-0">Address: Barangay / Municipality</label>
                		<select class="form-control select2" name="barangay_id" id="barangay_id" style="width: 100%;">
                			<option value="">- Barangay / Municipality -</option>
                			@forelse($barangays as $barangay)
                            @php $selected = ($permittee->barangay_id==$barangay->id) ? 'selected' : ''; @endphp
                			<option value="{{$barangay->id}}" {{$selected}}>{{$barangay->barangay.' / '.$barangay->town}}</option>
                			@empty
                			@endforelse
                		</select>
                        @error('barangay_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="business_name" class="form-label mb-0">Business Name</label>
                		<input type="text" class="form-control" name="business_name" id="business_name" placeholder="Business Name" value="{{ $permittee->business_name }}">
                        @error('business_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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