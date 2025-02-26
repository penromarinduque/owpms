@extends('layouts.master')

@section('title')
Edit Specie Class
@endsection

@section('species-show')
show
@endsection

@section('active-classes')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Specie Class</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('specieclasses.index') }}">Specie Classes</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('specieclasses.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Edit Specie Class here
        </div>
        <div class="card-body">
            @if(!empty($specie_class))
            <form method="POST" action="{{ route('specieclasses.update', [$specie_class->id]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                <div class="mb-3">
                  <label for="specie_type" class="form-label">Specie Class</label>
                  <select class="form-select select2" name="specie_type" id="specie_type">
                    <option value="">-Select Type-</option>
                    @foreach($specie_types as $specie_type)
                      <option value="{{ $specie_type->id }}" {{ ($specie_type->id==$specie_class->specie_type_id) ? 'selected' : '' }}>{{ $specie_type->specie_type }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                    <label for="specie_class" class="form-label">Specie Type</label>
                    <input type="text" class="form-control" name="specie_class" id="specie_class" required value="{{ $specie_class->specie_class }}">
                    @error('specie_class')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Is Active?</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_class" id="flexRadioDefault1" value="1" {{ ($specie_class->is_active_class==1) ? 'checked' : '' }}>
                      <label class="form-check-label" for="flexRadioDefault1">
                        YES
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_class" id="flexRadioDefault2" value="0" {{ ($specie_class->is_active_class==0) ? 'checked' : '' }}>
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