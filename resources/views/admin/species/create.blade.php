@extends('layouts.master')

@section('title')
Add Specie
@endsection

@section('species-show')
show
@endsection

@section('active-species')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Specie</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('species.index') }}">Species</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('species.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New Specie here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('species.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <div class="row mb-3">
                	<div class="col-sm-4">
                		<label for="specie_type_id" class="form-label">Wildlife Type</label>
                		<select class="form-select" name="specie_type_id" id="specie_type_id" onchange="onSelectType(event)" required>
                			<option value="">- Please Select Type-</option>
                            @foreach($specie_types as $specie_type)
                                <option value="{{ $specie_type->id }}">{{ $specie_type->specie_type }}</option>
                            @endforeach
                		</select>
                        @error('specie_type_id')<small class="text-danger">{{ $message }}</small>@enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="specie_class_id" class="form-label">Specie Class</label>
                		<select class="form-select" name="specie_class_id" id="specie_class_id" onchange="onSelectClass(event)" required>
                			<option value="">- Please Select Class -</option>
                		</select>
                        @error('specie_class_id')<small class="text-danger">{{ $message }}</small>@enderror
                	</div>
                	<div class="col-sm-4">
                		<label for="specie_family_id" class="form-label">Specie Family</label>
                		<select class="form-select" name="specie_family_id" id="specie_family_id" required>
                			<option value="">- Please Select Family -</option>
                		</select>
                        @error('specie_family_id')<small class="text-danger">{{ $message }}</small>@enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-5">
                		<label for="specie_name" class="form-label">Scientific Name</label>
                		<input type="text" class="form-control" name="specie_name" id="specie_name" placeholder="Scientific Name" value="{{ old('specie_name') }}" required>
                        @error('specie_name')<small class="text-danger">{{ $message }}</small>@enderror
                	</div>
                	<div class="col-sm-2 pt-4">
                      	<input type="checkbox" id="present" name="present">
                       	<label for="present" style="margin-bottom: 0px;">Present</label> 
                       	<span style="font-size: 10px;"><br>Check if this specie is present in this Province</span>
                	</div>
                	<div class="col-sm-5">
                		<label for="local_name" class="form-label">Common/Local Name</label>
                		<input type="text" class="form-control" name="local_name" id="local_name" placeholder="Common/Local Name" value="{{ old('local_name') }}" >
                        @error('local_name')<small class="text-danger">{{ $message }}</small>@enderror
                	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-sm-4">
                        <label for="wing_span" class="form-label">Wing Span (ave.) - (If Applicable)</label>
                        <input type="text" class="form-control" name="wing_span" id="wing_span" placeholder="Wing Span (ave.)" value="{{ old('wing_span') }}" >
                    </div>
                	<div class="col-sm-4">
                        <label for="wing_span" class="form-label">Convservation Status:</label> <br>
                        <input type="radio" value="rare" id="rare" name="conservation_status" checked="checked">
                        <label for="rare">Rare</label> &nbsp;
                        <input type="radio" value="threatened" id="threatened" name="conservation_status">
                        <label for="threatened">Threatened</label> &nbsp;
                        <input type="radio" value="vulnerable" id="vulnerable" name="conservation_status">
                        <label for="vulnerable">Vulnerable</label> &nbsp;
                        @error('conservation_status')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                	<div class="col-sm-4"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="color_description" class="form-label">Color Description</label>
                        <textarea class="form-control" name="color_description" id="color_description" required>{{ old('color_description') }}</textarea>
                        @error('color_description')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="food_plant" class="form-label">Food Plant</label>
                        <textarea class="form-control" name="food_plant" id="food_plant" required>{{ old('food_plant') }}</textarea>
                        @error('food_plant')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block float-end"><i class="fas fa-save"></i> Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script>
    function onSelectType(e){
        const value = e.target.value;
        if(!value){
            return;
        }

        $.get(
            "{{ route('specieclasses.apiGetByType') }}",
            {
                specie_type_id : value
            },
            function(data){
                console.log(data);
                $('#specie_class_id').empty();
                $('#specie_class_id').append('<option value="">-Select Specie Class-</option>');
                $.each(data, function(index, specie_class){
                    $('#specie_class_id').append('<option value="'+specie_class.id+'">'+specie_class.specie_class+'</option>');
                });
            }
        );
    }

    function onSelectClass(e){
        const value = e.target.value;
        if(!value){
            return;
        }

        $.get(
            "{{ route('speciefamilies.apiGetByClass') }}",
            {
                specie_class_id : value
            },
            function(data){
                console.log(data);
                $('#specie_family_id').empty();
                $('#specie_family_id').append('<option value="">-Select Specie Family-</option>');
                $.each(data, function(index, specie_family){
                    $('#specie_family_id').append('<option value="'+specie_family.id+'">'+specie_family.family+'</option>');
                });
            }
        );
    }

</script>
@endsection