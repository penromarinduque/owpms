@extends('layouts.master')

@section('title')
Add New Specie Type
@endsection

@section('species-show')
show
@endsection

@section('active-types')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Wildlife Type</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('specietypes.index') }}">Specie Types</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('specietypes.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New Wildlife Type here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('specietypes.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <div class="mb-3">
                    <label for="specie_type" class="form-label">Specie Type</label>
                    <input type="text" class="form-control" name="specie_type" id="specie_type" required value="{{ old('specie_type') }}">
                    @error('specie_type')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="hidden" class="form-control" name="is_active_type" id="is_active_type" value="1">
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection