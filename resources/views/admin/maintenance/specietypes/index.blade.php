@extends('layouts.master')

@section('title')
Wildlife Types
@endsection

@section('species-show')
show
@endsection

@section('active-types')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Wildlife Types</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Wildlife Types</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('specietypes.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-feather-alt me-1"></i>
            List of Wildlife Types
        </div>
        <div class="card-body">
            @if(session('failed'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('failed') }}</strong>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
            <table id="datatablesSimple" class="table table-md table-striped table-hover" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Wildlife Type</th>
                        <th>Is Active?</th>
                        <th><i class="fas fa-ellipsis-h" title="Action" alt="Action"></i></th>
                    </tr>
                </thead>
                <tbody>
                	@forelse($specie_types as $specie_type)
                    <tr>
                        <td>{{ $specie_type->specie_type }}</td>
                        <td>{{ ($specie_type->is_active_type==1) ? 'YES' : 'NO' }}</td>
                        <td>
                            <a href="{{ route('specietypes.edit', ['id'=>$specie_type->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
                        </td>
                    </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection