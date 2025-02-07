@extends('layouts.master')

@section('title')
LTP Requirements
@endsection

@section('active-ltprequirements')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">LTP Requirements</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Maintenance</li>
        <li class="breadcrumb-item active">LTP Requirements</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('ltprequirements.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-list me-1"></i>
            List of LTP Requirements
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
                        <th>Requirement</th>
                        <th>Is Mandatory?</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                	@forelse($ltprequirements as $ltprequirement)
                    <tr>
                        <td>{{ $ltprequirement->requirement_name }}</td>
                        <td>{{ ($ltprequirement->is_mandatory==1) ? 'YES' : 'NO' }}</td>
                        <td>{{ ($ltprequirement->is_active_requirement==1) ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('ltprequirements.edit', ['id'=>$ltprequirement->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
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