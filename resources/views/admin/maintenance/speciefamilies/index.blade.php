@extends('layouts.master')

@section('title')
Specie Families
@endsection

@section('species-show')
show
@endsection

@section('active-families')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Specie Families</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Specie Families</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <div class="float-end">
                
                <a href="{{ route('speciefamilies.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-feather-alt me-1"></i>
            List of Specie Families
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
                        <th>Specie Family</th>
                        <th>Specie Class</th>
                        <th>Is Active?</th>
                        <th><i class="fas fa-ellipsis-h" title="Action" alt="Action"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specie_families as $specie_family)
                    <tr>
                        <td>{{ $specie_family->family }}</td>
                        <td>{{ ($specie_family->specieClass ? $specie_family->specieClass->specie_class : 'N/A') }}</td>
                        <td>{{ ($specie_family->is_active_family==1) ? 'YES' : 'NO' }}</td>
                        <td>
                            <a href="{{ route('speciefamilies.edit', ['id'=>$specie_family->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
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