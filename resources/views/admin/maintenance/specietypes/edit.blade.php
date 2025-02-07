@extends('layouts.master')

@section('title')
Edit Specie Type
@endsection

@section('species-show')
show
@endsection

@section('active-types')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Specie Type</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('specietypes.index') }}">Specie Types</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('specietypes.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Edit Specie Type here
        </div>
        <div class="card-body">
            @if(!empty($specie_type))
            <form method="POST" action="{{ route('specietypes.update', [$specie_type->id]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                <div class="mb-3">
                    <label for="specie_type" class="form-label">Specie Type</label>
                    <input type="text" class="form-control" name="specie_type" id="specie_type" required value="{{ $specie_type->specie_type }}">
                    @error('specie_type')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Is Active?</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_type" id="flexRadioDefault1" value="1" {{ ($specie_type->is_active_type==1) ? 'checked' : '' }}>
                      <label class="form-check-label" for="flexRadioDefault1">
                        YES
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_type" id="flexRadioDefault2" value="0" {{ ($specie_type->is_active_type==0) ? 'checked' : '' }}>
                      <label class="form-check-label" for="flexRadioDefault2">
                        NO
                      </label>
                    </div>
                </div>
                <button type="submit" id="btn_update" name="btn_update" class="btn btn-primary btn-block">Save Changes</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection