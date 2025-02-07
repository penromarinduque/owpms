@extends('layouts.master')

@section('title')
Add New LTP Requirement
@endsection

@section('active-ltprequirements')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New LTP Requirement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ltprequirements.index') }}">LTP Requirements</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('ltprequirements.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New LTP Requirement here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('ltprequirements.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <div class="row mb-3">
                	<div class="col-sm-6">
                		<label for="requirement_name" class="form-label">Requirement Name</label>
	                    <input type="text" class="form-control" name="requirement_name" id="requirement_name" value="{{ old('requirement_name') }}">
	                    @error('requirement_name')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                    <div class="col-sm-6">
                		<label class="form-label">Is mandatory?</label><br>
	                    <input type="radio" name="is_mandatory" id="is_mandatory_yes" value="1">
                        <label for="is_mandatory_yes" class="form-label">YES</label>&nbsp;&nbsp;
                        <input type="radio" name="is_mandatory" id="is_mandatory_no" value="0">
                        <label for="is_mandatory_no" class="form-label">NO</label>
	                    @error('is_mandatory')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block float-end">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection