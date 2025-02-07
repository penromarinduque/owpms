@extends('layouts.master')

@section('title')
Create User
@endsection

@section('active-users')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-user-plus me-1"></i>
            Add New User here
        </div>
        <div class="card-body">
        	<form method="POST" action="{{ route('users.store') }}" onsubmit="disableSubmitButton('btn_save');">
        	@csrf
        		 <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="lastname" class="form-label">Last name</label>
                		<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last name" value="{{ old('lastname') }}">
                        @error('lastname')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                    <div class="col-sm-4">
                        <label for="firstname" class="form-label">First name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name" value="{{ old('firstname') }}">
                        @error('firstname')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                	<div class="col-sm-4">
                		<label for="email" class="form-label">Email</label>
                		<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="username" class="form-label">Username <!-- <small><i>(6-16 characters)</i></small> --></label>
                        <button type="button" class="btn btn-sm" onclick="generateUsername('firstname', 'lastname', 'username');">Generate</button>
                		<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}">
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                    <div class="col-sm-4">
                        <span class="float-end"><input type="checkbox" name="gen_pass" id="gen_pass" onclick="genaratePassword('gen_pass', 'password');"> <label for="gen_pass">Generate Password</label></span>
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Text input with checkbox">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="chk_show_pass" aria-label="Checkbox for following text input" onclick="showPass('chk_show_pass', 'password');">&nbsp;<label for="chk_show_pass">Show</label>
                            </div>
                        </div>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block float-end"><i class="fas fa-save"></i> Save</button>
        	</form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    
</script>
@endsection