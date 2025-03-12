@extends('layouts.master')

@section('title')
Add New Permittee
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Permittee</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permittees.index') }}">Permittees</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('permittees.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-user-plus me-1"></i>
            Add New Permittee here
        </div>
        <div class="card-body">
            @if(session('failed'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('failed') }}</strong>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
        	<form method="POST" action="{{ route('permittees.store') }}" onsubmit="disableSubmitButton('btn_save');" enctype="multipart/form-data">
        	@csrf
                <h5>Personal Infomation</h5>
        		<div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="last_name" class="form-label mb-0">Lastname<b class="text-danger">*</b></label>
                		<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Lastname" value="{{ old('last_name') }}">
                        @error('last_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="first_name" class="form-label mb-0">Firstname<b class="text-danger">*</b></label>
                		<input type="text" class="form-control" name="first_name" id="first_name" placeholder="Firstname" value="{{ old('first_name') }}" onkeyup="generateUsername('first_name', 'last_name', 'username');">
                        @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="middle_name" class="form-label mb-0">Middle Name</label>
                		<input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" value="{{ old('middle_name') }}">
                        @error('middle_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-2">
                		<label for="gender" class="form-label mb-0">Gender<b class="text-danger">*</b></label>
                		<select class="form-select" name="gender" id="gender" required>
                			<option value="">-Gender-</option>
                			<option value="male" {{(old('gender') == 'male') ? 'selected' : ''}} >Male</option>
                			<option value="female" {{(old('gender') == 'female') ? 'selected' : ''}}>Female</option>
                		</select>
                        @error('gender')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="barangay_id" class="form-label mb-0">Address: Barangay / Municipality<b class="text-danger">*</b></label>
                		<select class="form-control select2" name="barangay_id" id="barangay_id" style="width: 100%;" required>
                			<option value="">- Barangay / Municipality -</option>
                			@forelse($barangays as $barangay)
                            <?php $sel_brgy = (old('barangay_id')==$barangay->id) ? 'selected' : ''; ?>
                			<option value="{{$barangay->id}}" {{$sel_brgy}}>{{$barangay->barangay_name.' / '.$barangay->municipality_name.' / '.$barangay->province_name}}</option>
                			@empty
                			@endforelse
                		</select>
                        @error('barangay_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                    <div class="col-sm-2">
                        <label for="contact_no" class="form-label mb-0">Contact Number<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Contact Number" value="{{ old('contact_no') }}">
                        @error('contact_no')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="email" class="form-label mb-0">Email<b class="text-danger">*</b></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                	<!-- <div class="col-sm-4">
                		<label for="business_name" class="form-label mb-0">Business Name</label>
                		<input type="text" class="form-control" name="business_name" id="business_name" placeholder="Business Name" value="{{ old('business_name') }}">
                        @error('business_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div> -->
                </div>
                <hr>
                <h5>Wildlife Farm and Permit Details</h5>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label for="farm_name">Farm Name<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="farm_name" id="farm_name" placeholder="Farm Name" value="{{ old('farm_name') }}">
                        @error('farm_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <label for="location" class="form-label mb-0">Location<b class="text-danger">*</b></label>
                        <select class="form-control select2" name="location" id="location" style="width: 100%;" required>
                            <option value="">- Barangay / Municipality -</option>
                            @forelse($barangays as $barangay)
                            <?php $sel_brgy = (old('location')==$barangay->id) ? 'selected' : ''; ?>
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
                        <input type="text" class="form-control" name="size" id="size" placeholder="Size" value="{{ old('size') }}">
                        @error('size')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="height">Height<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="height" id="height" placeholder="Height" value="{{ old('height') }}">
                        @error('height')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <input type="hidden" name="permit_type_wfp" id="permit_type_wfp" value="wfp">
                    <div class="col-sm-3">
                        <label for="permit_number_wfp">Permit Number<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="permit_number_wfp" id="permit_number_wfp" placeholder="Permit Number" value="{{ old('permit_number_wfp') }}">
                        @error('permit_number')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="valid_from_wfp">Valid from<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="valid_from_wfp" id="valid_from_wfp" placeholder="Valid from" value="{{ old('valid_from_wfp') }}">
                        @error('valid_from_wfp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="valid_to_wfp">Valid to<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="valid_to_wfp" id="valid_to_wfp" placeholder="Valid to" value="{{ old('valid_to_wfp') }}">
                        @error('valid_to_wfp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="date_of_issue_wfp">Date of Issue<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="date_of_issue_wfp" id="date_of_issue_wfp" placeholder="Date of Issue" value="{{ old('date_of_issue') }}">
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
                <hr>
                <h5>Wildlife Collection Permit Details</h5>
                <div class="row mb-3">
                    <input type="hidden" name="permit_type_wcp" id="permit_type_wcp" value="wcp">
                    <div class="col-sm-3">
                        <label for="permit_number_wcp">Permit Number<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="permit_number_wcp" id="permit_number_wcp" placeholder="Permit Number" value="{{ old('permit_number_wcp') }}">
                        @error('permit_number_wcp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="valid_from_wcp">Valid from<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="valid_from_wcp" id="valid_from_wcp" placeholder="Valid from" value="{{ old('valid_from_wcp') }}">
                        @error('valid_from_wcp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="valid_to_wcp">Valid to<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="valid_to_wcp" id="valid_to_wcp" placeholder="Valid to" value="{{ old('valid_to_wcp') }}">
                        @error('valid_to_wcp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label for="date_of_issue_wcp">Date of Issue<b class="text-danger">*</b></label>
                        <input type="date" class="form-control" name="date_of_issue_wcp" id="date_of_issue_wcp" placeholder="Date of Issue" value="{{ old('date_of_issue_wcp') }}">
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
                <hr>
                <h5>Login Credentials</h5>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label for="username" class="form-label mb-1">Username<b class="text-danger">*</b></label>
                        <!-- <button type="button" class="btn btn-sm btn-secondary" onclick="generateUsername('firstname', 'lastname', 'username');">Generate</button> -->
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}" readonly>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="password" class="form-label mb-1">Password<b class="text-danger">*</b></label>
                        <input id="gen_passw" type="checkbox" onclick="genaratePassword('gen_passw', 'password');" />
                        <label for="gen_passw" class="form-label mb-1">Generate</label>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" aria-label="Text input with checkbox">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="show_passw" aria-label="Checkbox for following text input" onclick="showPass('show_passw', 'password');">
                                <label for="show_passw">Show</label>
                            </div>
                        </div>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <hr>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block float-end"><i class="fas fa-save"></i> Save Permittee</button>
        	</form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">

    function generateUsername2(fname_fld, lname_fld, mname_fld, uname_fld) {
        var fname = document.getElementById(fname_fld).value;
        var nameList = [
          fname,
          document.getElementById(lname_fld).value,
          document.getElementById(mname_fld).value,
        ];

        var userName = "";

        userName = nameList[Math.floor( Math.random() * nameList.length )];
        userName += nameList[Math.floor( Math.random() * nameList.length )];
        if ( Math.random() > 0.5 ) {
            userName += nameList[Math.floor( Math.random() * nameList.length )];
        }

        var str_u = fname;
        str_u = str_u.replace(/ +/g, "");
        // return "owpms_" + userName;
        // Array.from('some string')[0];
        // console.log(Array.from('some string')[0]);
        document.getElementById(uname_fld).value = "owpms_" + str_u.toLowerCase();
    }
</script>
@endsection