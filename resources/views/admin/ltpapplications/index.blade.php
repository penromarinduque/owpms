@extends('layouts.master')

@section('title')
LTP Applications
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
        <li class="breadcrumb-item">Applications</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            {{-- <div class="float-end">
                <a href="{{ route('myapplication.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> Create New</a>
            </div> --}}
            <i class="fas fa-list me-1"></i>
            LTP Applications
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
                <a class="nav-link {{ $status == 'resubmitted' ? 'active' : '' }}" href="?status=resubmitted">Resubmitted</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'under-review' ? 'active' : '' }}" href="?status=under-review">Under Review</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'returned' ? 'active' : '' }}" href="?status=returned">Returned</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'accepted' ? 'active' : '' }}" href="?status=accepted">Accepted</a>
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
                <table class="table table-sm table-hover table-striped" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Permittee</th>
                            <th>Application No.</th>
                            <th>Date Created</th>
                            <th>Last Modified</th>
                            <th>Status</th>
                            <th width="200px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltp_applications as $key => $ltp_application)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $ltp_application->permittee->user->personalInfo->first_name . ' ' . $ltp_application->permittee->user->personalInfo->last_name }}</td>
                                <td class="align-middle">{{ $ltp_application->application_no }}</td>
                                <td class="align-middle">{{ $ltp_application->created_at->format('F d, Y') }}</td>
                                <td class="align-middle">{{ $ltp_application->updated_at->format('F d, Y') }}</td>
                                <td class="align-middle text-center">
                                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('myapplication.preview', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-info mb-2"  data-bs-toggle="tooltip" data-bs-title="Preview"><i class="fas fa-eye"></i></a>
                                    @if (in_array($status, ['submitted', 'resubmitted']))
                                        <a href="#" onclick="showConfirmModal('{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}', 'Viewing this application will mark it as Under Review. Are you sure you want to continue?', 'Confirm Review', 'GET')" class="btn btn-sm btn-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @if (in_array($status, ['under-review']))
                                        <a href="{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @if (in_array($status, ['accepted']))   
                                        <a href="{{ route('ltpapplication.generatePaymentOrder', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Generate Payment Order"><i class="fas fa-file-invoice-dollar"></i></a>
                                    @endif

                                    <a href="#" onclick="showViewApplicationLogsModal({{ $ltp_application->id }})" class="btn btn-sm btn-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Logs"><i class="fas fa-history"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('components.confirm')
@include('components.viewApplicationLogs')
@endsection