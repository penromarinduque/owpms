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

            {{-- <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link {{ $status == 'submitted' ? 'active' : '' }}" href="?status=submitted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('submitted', null); @endphp
                    Submitted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'resubmitted' ? 'active' : '' }}" href="?status=resubmitted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('resubmitted', null); @endphp
                    Resubmitted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'under-review' ? 'active' : '' }}" href="?status=under-review">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('under-review', null); @endphp
                    Under Review 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'returned' ? 'active' : '' }}" href="?status=returned">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('returned', null); @endphp
                    Returned 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'accepted' ? 'active' : '' }}" href="?status=accepted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('accepted', null); @endphp
                    Accepted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'payment-in-process' ? 'active' : '' }}" href="?status=payment-in-process">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('payment-in-process', null); @endphp
                    Payment In Process 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'paid' ? 'active' : '' }}" href="?status=paid">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('paid', null); @endphp
                    For Inspection 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'approved' ? 'active' : '' }}" href="?status=approved">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('approved', null); @endphp
                    Approved 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'rejected' ? 'active' : '' }}" href="?status=rejected">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('rejected', null); @endphp
                    Rejected
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'expired' ? 'active' : '' }}" href="?status=expired">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('expired', null); @endphp
                    Expired 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
            </ul> --}}

            <ul class="nav nav-tabs">
                @can('viewSubmittedTab', App\Models\LtpApplication::class)
                    <li class="nav-item">
                        <a href="?status=all&category=submitted" class="nav-link {{ request('category') == 'submitted' ? 'active' : ''}}"><i class="fas fa-file-import me-1"></i>Submitted</a>
                    </li>
                @endcan
                @can('viewReviewedTab', App\Models\LtpApplication::class)
                    <li class="nav-item">
                        <a href="?status=all&category=reviewed" class="nav-link {{ request('category') == 'reviewed' ? 'active' : ''}}"><i class="fas fa-eye me-1"></i>Reviewed</a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="?status=returned&category=returned" class="nav-link {{ request('category') == 'returned' ? 'active' : ''}}"><i class="fas fa-undo me-1"></i>Returned</a>
                </li>
                @can('viewAcceptedTab', App\Models\LtpApplication::class)
                    <li class="nav-item">
                        <a href="?status=all&category=accepted" class="nav-link {{ request('category') == 'accepted' ? 'active' : ''}}"><i class="fas fa-check-circle me-1"></i>Accepted</a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="?status=all&category=rejected" class="nav-link {{ request('category') == 'rejected' ? 'active' : ''}}"><i class="fas fa-times-circle me-1"></i>Rejected</a>
                </li>
                @can('viewApprovedTab', App\Models\LtpApplication::class)
                    <li class="nav-item">
                        <a href="?status=approved&category=approved" class="nav-link {{ request('category') == 'approved' ? 'active' : ''}}"><i class="fas fa-check-circle me-1"></i>Approved</a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="?status=expired&category=expired" class="nav-link {{ request('category') == 'expired' ? 'active' : ''}}"><i class="fas fa-calendar-times me-1"></i>Expired</a>
                </li>
            </ul>

            <br>

            <div class="d-flex justify-content-end mb-3">
                <form class="row gap-0" action="" method="get">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <div class="col-auto">
                        <input type="text" name="application_no" value="{{ request('application_no') }}" placeholder="Application No." class="form-control">
                    </div>
                    <div class="col-auto pe-0">
                        <select class="form-select" name="status" id="">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : ''}}>All</option>
                            @foreach ($_helper->identifyApplicationStatusesByCategory(request('category')) as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : ''}}>{{ $_ltp_application->getApplicationCountsByStatus($status, null) }} - {{ $_helper->formatApplicationStatus($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary ">Filter</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th>Permittee</th>
                            <th>Application No.</th>
                            <th class="text-center">Date Created</th>
                            <th class="text-center">Last Modified</th>
                            <th width="300px" class="text-center">Transport Date</th>
                            <th class="text-center">Status</th>
                            <th width="200px" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltp_applications as $key => $ltp_application)
                            <tr>
                                <td class="align-middle">{{ $ltp_application->permittee->user->personalInfo->first_name . ' ' . $ltp_application->permittee->user->personalInfo->last_name }}</td>
                                <td class="align-middle">{{ $ltp_application->application_no }}</td>
                                <td class="align-middle text-center text-secondary">{{ $ltp_application->application_date->diffForHumans() }}</td>
                                <td class="align-middle text-center text-secondary">{{ $ltp_application->updated_at->diffForHumans() }}</td>
                                <td class="align-middle text-center">{{ $ltp_application->transport_date->format("F d, Y") }} <span class="text-secondary">({{ $ltp_application->transport_date->diffForHumans() }})</span></td>
                                <td class="align-middle text-center">
                                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    @can('view', $ltp_application)
                                        <a href="{{ route('ltpapplication.preview', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-info mb-2"  data-bs-toggle="tooltip" data-bs-title="Details"><i class="fas fa-file-alt"></i></a>
                                    @endcan
                                    @can('accept', $ltp_application)
                                        <a href="#" onclick="showAcceptApplicationModal('{{ route('ltpapplication.accept', Crypt::encryptString($ltp_application->id)) }}')"  class="btn btn-sm btn-outline-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Accept/Receive"><i class="fas fa-check"></i></a>
                                    @endcan
                                    @if (in_array($ltp_application->application_status, ['submitted', 'resubmitted']) && Gate::allows('review', $ltp_application))
                                        <a href="#" onclick="showConfirmModal('{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}', 'Viewing this application will mark it as Under Review. Are you sure you want to continue?', 'Confirm Review', 'GET')" class="btn btn-sm btn-outline-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @if (
                                        in_array($ltp_application->application_status, ['under-review']) 
                                        && (Gate::allows('accept', $ltp_application ) || Gate::allows('reject', $ltp_application))
                                    )
                                        <a href="{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-outline-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @can('generatePaymentOrder', $ltp_application)
                                        <a href="{{ route('paymentorder.create', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Generate Payment Order"><i class="fas fa-file-invoice-dollar"></i></a>
                                    @endcan
                                    @if (in_array($ltp_application->application_status, ['payment-in-process']))   
                                        <a href="{{ route('paymentorder.show', Crypt::encryptString($ltp_application->paymentOrder->id)) }}" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="View Payment Order"><i class="fas fa-file-invoice-dollar"></i></a>
                                    @endif
                                    @can('inspect', $ltp_application)  
                                            <a href="{{ route('inspection.index', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="View Inspection"><i class="fas fa-eye"></i></a>
                                            @if (in_array($ltp_application->application_status, ['inspected']))   
                                                @can('generateLtp', $ltp_application)
                                                    <a href="{{ route('ltpapplication.permit', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Local Transport Permit"><i class="fas fa-file-pdf"></i></a>
                                                @endcan
                                                <a href="{{ route('inspection.createReport', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Inspection Report"><i class="fas fa-file-pdf"></i></a>
                                            @endif
                                    @endcan

                                    @can('releaseLtp', $ltp_application)
                                        <a href="#" onclick="showReleaseLtpModal('{{ route('ltpapplication.release', Crypt::encryptString($ltp_application->id)) }}')" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Release LTP"><i class="fa-solid fa-arrow-right-from-bracket"></i> Release LTP</a>
                                    @endcan

                                    <a href="#" onclick="showViewApplicationLogsModal({{ $ltp_application->id }})" class="btn btn-sm btn-outline-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Logs"><i class="fas fa-history"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $ltp_applications->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('includes')
    @include('components.confirm')
    @include('components.viewApplicationLogs')
    @include('components.releaseLtp')
    @include('components.acceptApplicationModal')
@endsection