@extends('layouts.master')

@section('title')
Positions
@endsection

@section('active-positions')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Positions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Positions</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('positions.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-list-ol me-1"></i>
            List of Positions
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
                        <th>Position</th>
                        <th>Description</th>
                        <th><i class="fas fa-ellipsis-h" title="Action" alt="Action"></i></th>
                    </tr>
                </thead>
                <tbody>
                	@forelse($positions as $position)
                    <tr>
                        <td>{{ $position->position }}</td>
                        <td><small>{{ $position->description }}</small></td>
                        <td>
                            <a href="{{ route('positions.edit', ['id'=>$position->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
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