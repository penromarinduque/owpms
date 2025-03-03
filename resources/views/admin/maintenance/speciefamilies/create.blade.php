@extends('layouts.master')

@section('title')
Add New Specie Family
@endsection

@section('species-show')
show
@endsection

@section('active-families')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Specie Family</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('speciefamilies.index') }}">Specie Families</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('speciefamilies.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New Specie Family here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('speciefamilies.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <input type="hidden" class="form-control" name="is_active_family" id="is_active_family" value="1">
                <div class="mb-3">
                    <label class="form-label" for="specie_class">Specie Class</label>
                    <select class="form-select" name="specie_class" id="specie_class">
                        <option value="">-Select Class-</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="family" class="form-label">Specie Family</label>
                    <input type="text" class="form-control" name="family" id="family" required value="{{ old('family') }}">
                    @error('family')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary btn-block">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')    
<script>
    $(function(){
        $("#specie_class").select2({
            // theme: 'bootstrap-5',
            ajax : {
                url : '{{ route('specieclasses.apiSearch') }}',
                dataType : 'json',
                delay : 250,
                data : function(params) {
                    var query = {
                        keyword: params.term,
                        type: 'query'
                    }

                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        })
    })
</script>
@endsection