@extends('layouts.master')

@section('title')
Add New Specie Class
@endsection

@section('species-show')
show
@endsection

@section('active-classes')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Specie Class</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('specieclasses.index') }}">Specie Classes</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('specieclasses.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Add New Specie Class here
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('specieclasses.store') }}" onsubmit="disableSubmitButton('btn_save');">
                @csrf
                <input type="hidden" class="form-control" name="is_active_class" id="is_active_class" value="1">
                <div class="mb-3">
                    <label class="form-label" for="specie_type_id">Specie Type</label>
                    <select class="form-select" name="specie_type" id="specie_type">
                        <option value="">-Select Specie Type-</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="specie_class" class="form-label">Specie Class</label>
                    <input type="text" class="form-control" name="specie_class" id="specie_class" required value="{{ old('specie_class') }}">
                    @error('specie_class')<small class="text-danger">{{ $message }}</small>@enderror
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
        $("#specie_type").select2({
            // theme: 'bootstrap-5',
            ajax : {
                url : '{{ route('specietypes.apiSearch') }}',
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