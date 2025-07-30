@extends('layouts.master')

@section('title')
Specie Classes
@endsection

@section('species-show')
show
@endsection

@section('active-classes')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Specie Classes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Specie Classes</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('specieclasses.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-feather-alt me-1"></i>
            List of Specie Classes
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
                        <th>Class</th>
                        <th>Is Active?</th>
                        <th><i class="fas fa-ellipsis-h" title="Action" alt="Action"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specie_classes as $specie_class)
                    <tr>
                        <td>{{ $specie_class->specie_class }}</td>
                        <td>{{ ($specie_class->is_active_class==1) ? 'YES' : 'NO' }}</td>
                        <td>
                            <a href="{{ route('specieclasses.edit', ['id'=>$specie_class->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No Data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection