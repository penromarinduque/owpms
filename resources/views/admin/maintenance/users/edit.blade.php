@extends('layouts.master')

@section('title')
Edit User
@endsection

@section('active-users')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-user-edit me-1"></i>
            Edit User here
        </div>
        <div class="card-body">
        	@if(!empty($user))
        	<form method="POST" action="{{ route('users.update', [Crypt::encrypt($user->id)]) }}" onsubmit="disableSubmitButton('btn_update');">
        	@csrf
            <div class="row mb-3">
                    <div class="col-sm-4 mb-2">
                        <label for="firstname" class="form-label">First Name <b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name" value="{{ old('firstname') ?? $user->personalInfo->first_name }}">
                        @error('firstname')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-4 mb-2">
                        <label for="middlename" class="form-label">Middle Name <b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Middle name" value="{{ old('middlename') ?? $user->personalInfo->middle_name }}">
                        @error('middlename')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-4 mb-2">
                		<label for="lastname" class="form-label">Last Name <b class="text-danger">*</b></label>
                		<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last name" value="{{ old('lastname') ?? $user->personalInfo->last_name }}">
                        @error('lastname')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                    <div class="col-sm-4 mb-2">
                            <label for="gender" class="form-label">Gender <b class="text-danger">*</b></label>
                		<select name="gender" id="gender" class="form-select" placeholder="Select Gender">
                            <option value="">-Select Gender-</option>
                            <option value="male" {{ (old('gender') == 'male' || $user->personalInfo->gender == 'male') ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ (old('gender') == 'female' || $user->personalInfo->gender == 'female') ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                </div>
        		 <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="email" class="form-label">Email</label>
                		<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $user->email }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">

                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="username" class="form-label">Username <small><i>(6-16 characters)</i></small></label>
                		<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ $user->username }}">
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                    <div class="col-sm-4">
                    	<span class="float-end"><input type="checkbox" name="gen_pass" id="gen_pass" onclick="genaratePassword('gen_pass', 'password');" disabled> <label for="gen_pass">Generate Password</label></span>
                        <label for="password" class="form-label">Password</label> | <input type="checkbox" name="opt_cp" id="opt_cp" onclick="optChangePassword('opt_cp', ['password', 'gen_pass', 'chk_show_pass']);"> <label for="opt_cp">Change Password?</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Text input with checkbox" disabled>
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="chk_show_pass" aria-label="Checkbox for following text input" onclick="showPass('chk_show_pass', 'password');" disabled>&nbsp;<label for="chk_show_pass">Show</label>
                            </div>
                        </div>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @if($user->usertype != 'permittee')
                        <div class="col-sm-4 mb-2">
                            <label for="position" class="form-label">Position/Designation <b class="text-danger">*</b> </label>
                            <select name="position" id="position" class="form-select select2" placeholder="Select Position/Designation">
                                <option value="">-Select Position/Designation-</option>
                                @foreach ($_position->getAllPositions() as $position)
                                    <option value="{{ $position->id }}" {{ (old('position') == $position->id || $user->position == $position->id) ? 'selected' : '' }}>{{ $position->position }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif  
                </div>
                <button type="submit" id="btn_update" class="btn btn-primary btn-block float-end"><i class="fas fa-save"></i> Save Changes</button>
        	</form>
        	@else
        	@endif
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    function optChangePassword(chkbox_id, inputs) {
        var chkd = $('#'+chkbox_id).is(':checked');
        // console.log(inputs);
        inputs.forEach(function (item) {
            if (chkd) {
                console.log(item);
                $('#'+item).prop('disabled', false);
                if (item=='password') {
                    $('#'+item).prop('required', true);
                }
            } else {
                $('#'+item).prop('disabled', true);
                if (item=='password') {
                    $('#'+item).val('');
                }
            }
        });
    }
</script>
@endsection