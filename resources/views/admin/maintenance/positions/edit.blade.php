@extends('layouts.master')

@section('title')
Edit Position
@endsection

@section('active-positions')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Position</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Positions</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('positions.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-edit me-1"></i>
            Edit Position here
        </div>
        <div class="card-body">
            @if(!empty($position))
            <form method="POST" action="{{ route('positions.update', [$position->id]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                <div class="row mb-3">
                	<div class="col-sm-6">
                		<label for="position" class="form-label">Position</label>
	                    <input type="text" class="form-control" name="position" id="position" value="{{ $position->position }}">
	                    @error('position')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                    <div class="col-sm-6">
                		<label for="description" class="form-label">Description <i>(optional)</i></label>
	                    <textarea class="form-control" name="description" id="description">{{ $position->description }}</textarea>
	                    @error('description')
	                    <small class="text-danger">{{ $message }}</small>
	                    @enderror
                	</div>
                </div>
                <button type="submit" id="btn_update" class="btn btn-primary btn-block float-end">Save Changes</button>
            </form>
            @else

            @endif
        </div>
    </div>
</div>
@endsection