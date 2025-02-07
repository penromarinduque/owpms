@extends('layouts.master')

@section('title')
Edit LTP Requirement
@endsection

@section('active-ltprequirements')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit LTP Requirement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ltprequirements.index') }}">LTP Requirements</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('ltprequirements.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-edit me-1"></i>
            Edit LTP Requirement here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('ltprequirements.update', [$ltprequirement->id]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                @if(!empty($ltprequirement))
                <div class="row mb-3">
                	<div class="col-sm-5">
                		<label for="requirement_name" class="form-label">Requirement Name</label>
	                    <input type="text" class="form-control" name="requirement_name" id="requirement_name" value="{{ $ltprequirement->requirement_name }}">
	                    @error('requirement_name')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                	<div class="col-sm-1"></div>
                    <div class="col-sm-3">
                		<label class="form-label">Is mandatory?</label><br>
	                    <input type="radio" name="is_mandatory" id="is_mandatory_yes" value="1" {{($ltprequirement->is_mandatory==1) ? 'checked' : ''}}>
                        <label for="is_mandatory_yes" class="form-label">YES</label>&nbsp;&nbsp;
                        <input type="radio" name="is_mandatory" id="is_mandatory_no" value="0" {{($ltprequirement->is_mandatory==0) ? 'checked' : ''}}>
                        <label for="is_mandatory_no" class="form-label">NO</label>
	                    @error('is_mandatory')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                	<div class="col-sm-3">
                		<label class="form-label">Status</label><br>
	                    <input type="radio" name="is_active_requirement" id="is_active_requirement_yes" value="1" {{($ltprequirement->is_active_requirement==1) ? 'checked' : ''}}>
                        <label for="is_active_requirement_yes" class="form-label">Active</label>&nbsp;&nbsp;
                        <input type="radio" name="is_active_requirement" id="is_active_requirement_no" value="0" {{($ltprequirement->is_active_requirement==0) ? 'checked' : ''}}>
                        <label for="is_active_requirement_no" class="form-label">Inactive</label>
	                    @error('is_active_requirement')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                </div>
                <button type="submit" id="btn_update" class="btn btn-primary btn-block float-end">Save Changes</button>
                @else
                <center><h2 class="text-danger">No record found!</h2></center>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection