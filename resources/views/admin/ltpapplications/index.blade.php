@extends('layouts.master')

@section('title')
Add New LTP Requirement
@endsection

@section('active-applications')
active
@endsection

@php
    $status = request('status') ?? 'submitted';
@endphp

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Applications</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('myapplication.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> Create New</a>
            </div>
            <i class="fas fa-list me-1"></i>
            List of My Applications
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
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link {{ $status == 'submitted' ? 'active' : '' }}" href="?status=submitted">Submitted</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'under_review' ? 'active' : '' }}" href="?status=under_review">Under Review</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'approved' ? 'active' : '' }}" href="?status=approved">Approved</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'rejected' ? 'active' : '' }}" href="?status=rejected">Rejected</a>
              </li>
            </ul>
            <br>
            <div class="table-responsive">
                <table class="table table-sm table-hover" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No.</th>
                            <th>Date Created</th>
                            <th>Last Modified</th>
                            <th>Application Status</th>
                            <th width="200px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltp_applications as $key => $ltp_application)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $ltp_application->application_no }}</td>
                                <td class="align-middle">{{ $ltp_application->created_at->format('F d, Y') }}</td>
                                <td class="align-middle">{{ $ltp_application->updated_at->format('F d, Y') }}</td>
                                <td class="align-middle">{{ format_application_status($ltp_application->application_status) }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('myapplication.preview', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-info mb-2"  data-bs-toggle="tooltip" data-bs-title="Preview"><i class="fas fa-eye"></i></a>
                                    @if ($status == 'draft')                                        
                                        <a href="{{ route('myapplication.edit', Crypt::encryptString($ltp_application->id)) }}"  class="btn btn-sm btn-warning mb-2" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-pen"></i></a>
                                    @endif
                                    @if ($status == 'draft')
                                        <a href="#" onclick="showSubmitApplicationModal('{{ route('myapplication.submit', Crypt::encryptString($ltp_application->id)) }}')"  class="btn btn-sm btn-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Submit"><i class="fa-solid fa-cloud-arrow-up"></i></a>
                                    @endif
                                    @if ($status == 'draft')                                        
                                        <a href="#" class="btn btn-sm btn-danger mb-2" onclick="showConfirDeleteModal ('{{ route('myapplication.destroy', $ltp_application->id) }}' ,{{ $ltp_application->id }}, 'Are you sure you want to delete this application?', 'Delete Application')"  data-bs-toggle="tooltip" data-bs-title="Delete"><i class="fa-solid fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection