@extends('layouts.master')

@section('title')
Edit Specie
@endsection

@section('species-show')
show
@endsection

@section('active-species')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Specie</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('species.index') }}">Species</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('species.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-edit me-1"></i>
            Edit Specie here
        </div>
        <div class="card-body">
            @if(!empty($specie))
            <form method="POST" action="{{ route('species.update', [Crypt::encrypt($specie_id)]) }}" onsubmit="disableSubmitButton('btn_update');">
                @csrf
                <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="specie_type_id" class="form-label">Wildlife Type</label>
                		<select class="form-select" name="specie_type_id" id="specie_type_id">
                			<option value="">- Please Select Type-</option>
                            @forelse($specie_types as $specie_type)
                            <option value="{{ $specie_type->id }}" {{ ($specie_type->id==$specie->specie_type_id) ? 'selected' : '' }}>{{ $specie_type->specie_type }}</option>
                            @empty
                            @endforelse
                		</select>
                        @error('specie_type_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="specie_class_id" class="form-label">Specie Class</label>
                		<select class="form-select" name="specie_class_id" id="specie_class_id">
                			<option value="">- Please Select Class -</option>
                            @forelse($specie_classes as $specie_class)
                            <option value="{{ $specie_class->id }}" {{ ($specie_class->id==$specie->specie_class_id) ? 'selected' : '' }}>{{ $specie_class->specie_class }}</option>
                            @empty
                            @endforelse
                		</select>
                        @error('specie_class_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="specie_family_id" class="form-label">Specie Family</label>
                		<select class="form-select" name="specie_family_id" id="specie_family_id">
                			<option value="">- Please Select Family -</option>
                            @forelse($specie_families as $specie_family)
                            <option value="{{ $specie_family->id }}" {{ ($specie_family->id==$specie->specie_family_id) ? 'selected' : '' }}>{{ $specie_family->family }}</option>
                            @empty
                            @endforelse
                		</select>
                        @error('specie_family_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-5">
                		<label for="specie_name" class="form-label">Scientific Name</label>
                		<input type="text" class="form-control" name="specie_name" id="specie_name" placeholder="Scientific Name" value="{{ $specie->specie_name }}">
                        @error('specie_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                	<div class="col-sm-2 pt-4">
                      	<input type="checkbox" id="present" name="present" {{ ($specie->is_present==1) ? 'checked' : '' }}>
                       	<label for="present" style="margin-bottom: 0px;">Present</label> 
                       	<span style="font-size: 10px;"><br>Check if this specie is present in this Province</span>
                	</div>
                	<div class="col-sm-5">
                		<label for="local_name" class="form-label">Common/Local Name</label>
                		<input type="text" class="form-control" name="local_name" id="local_name" placeholder="Common/Local Name" value="{{ $specie->local_name }}">
                        @error('local_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-4">
                        <label for="wing_span" class="form-label">Wing Span (ave.) - (If Applicable)</label>
                        <input type="text" class="form-control" name="wing_span" id="wing_span" placeholder="Wing Span (ave.)" value="{{ $specie->wing_span }}">
                    </div>
                	<div class="col-sm-4">
                        <label for="conservation_status" class="form-label">Convservation Status:</label>
                        <select class="form-select" name="conservation_status" id="conservation_status">
                            <option value="rare" {{ ($specie->conservation_status=='rare') ? 'selected' : '' }}>Rare</option>
                            <option value="threatened" {{ ($specie->conservation_status=='threatened') ? 'selected' : '' }}>Threatened</option>
                            <option value="vulnerable" {{ ($specie->conservation_status=='vulnerable') ? 'selected' : '' }}>Vulnerable</option>
                            <option value="endangered" {{ ($specie->conservation_status=='endangered') ? 'selected' : '' }}>Endangered</option>
                            <option value="least concerned" {{ ($specie->conservation_status=='least concerned') ? 'selected' : '' }}>Least Concerned</option>
                        </select>
                        @error('conservation_status')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                	<div class="col-sm-4"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="color_description" class="form-label">Color Description</label>
                        <textarea class="form-control" name="color_description" id="color_description">{{ $specie->color_description }}</textarea>
                        @error('color_description')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="food_plant" class="form-label">Food Plant</label>
                        <textarea class="form-control" name="food_plant" id="food_plant">{{ $specie->food_plant }}</textarea>
                        @error('food_plant')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button type="submit" id="btn_update" class="btn btn-primary btn-block float-end"><i class="fas fa-save"></i> Save Changes</button>
            </form>
            @else
            <center>
                <h5 class="text-danger">No record found!</h5>
            </center>
            @endif
        </div>
    </div>
</div>
@endsection