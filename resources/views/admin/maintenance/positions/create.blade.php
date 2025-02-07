@extends('layouts.master')

@section('title')
Create Position
@endsection

@section('active-positions')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Position</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Positions</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('positions.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New Position here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('positions.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <div class="row mb-3">
                	<div class="col-sm-6">
                		<label for="position" class="form-label">Position</label>
	                    <input type="text" class="form-control" name="position" id="position" value="{{ old('position') }}">
	                    @error('position')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                    <div class="col-sm-6">
                		<label for="description" class="form-label">Description <i>(optional)</i></label>
	                    <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
	                    @error('description')
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