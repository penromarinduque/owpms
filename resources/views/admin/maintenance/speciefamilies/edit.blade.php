@extends('layouts.master')

@section('title')
Edit Specie Family
@endsection

@section('species-show')
show
@endsection

@section('active-families')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Specie Family</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('speciefamilies.index') }}">Specie Family</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('speciefamilies.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Edit Specie Family here
        </div>
        <div class="card-body">
            @if(!empty($specie_family))
            <form method="POST" action="{{ route('speciefamilies.update', [$specie_family->id]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                <div class="mb-3">
                  <label for="specie_class" class="form-label">Specie Class</label>
                  <select class="form-select select2" name="specie_class" id="specie_class">
                    <option value="">-Select Class-</option>
                    @foreach($specie_classes as $specie_class)
                      <option value="{{ $specie_class->id }}" {{ ($specie_class->id==$specie_family->specie_class_id) ? 'selected' : '' }}>{{ $specie_class->specie_class }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                    <label for="family" class="form-label">Specie Type</label>
                    <input type="text" class="form-control" name="family" id="family" required value="{{ $specie_family->family }}">
                    @error('family')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Is Active?</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_family" id="flexRadioDefault1" value="1" {{ ($specie_family->is_active_family==1) ? 'checked' : '' }}>
                      <label class="form-check-label" for="flexRadioDefault1">
                        YES
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="is_active_family" id="flexRadioDefault2" value="0" {{ ($specie_family->is_active_family==0) ? 'checked' : '' }}>
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
