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
                	<div class="col-sm-4">
                		<label for="name" class="form-label">Name</label>
                		<input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ $user->name }}">
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
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
                    <div class="col-sm-4">
                		
                	</div>
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