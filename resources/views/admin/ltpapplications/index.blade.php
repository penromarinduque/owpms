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
<div class="container-fluid px-0">
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
        <div class="card-body p-0">
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

            <div class="p-2">
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
            </div>

            <br>

            

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped" >
                    <thead>
                        <tr>
                            <td colspan="8">
                                <div class="d-flex gap-1 justify-content-between">
                                    <div class="d-flex gap-1">
                                        @if (Gate::allows('bulkAccept', App\Models\LtpApplication::class) && request('category') == 'reviewed')
                                            <button class="btn btn-sm btn-outline-primary" onclick="submitAcceptAll()">Accept All</button>
                                            <button class="btn btn-sm btn-outline-primary" onclick="submitAcceptSelected()">Accept Selected</button>
                                        @endif
                                    </div>
                                    <div class=""></div>
                                    <div class="d-flex Justify-content-end gap-1">
                                        <div>
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-target="#sortModal" data-bs-toggle="modal">
                                                <i class="fa-solid fa-sort mr-2"></i> Sort
                                            </button>
                                            <div class="modal fade" id="sortModal">
                                                <div class="modal-dialog">
                                                    <form class="modal-content" action="" method="get" >
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="sortModalLabel">Sort</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                                            <input type="hidden" name="application_no" value="{{ request('application_no') }}" >
                                                            <input type="hidden" name="status" value="{{ request('status') }}" >
                                                            <div class="mb-2">
                                                                <select name="sort_by" id="" placeholder="Sort By" class="form-select">
                                                                    <option value="">-Select Sort By-</option>
                                                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : ''}}>Date Created</option>
                                                                    <option value="application_no" {{ request('sort_by') == 'application_no' ? 'selected' : ''}}>Application No.</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <select class="form-select" name="sort_order" id="" placeholder="Sort Order">
                                                                    <option value="">-Select Sort Order-</option>
                                                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : ''}}>Ascending</option>
                                                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : ''}}>Descending</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary">Sort</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-target="#filterModal" data-bs-toggle="modal">
                                                <i class="fa-solid fa-filter mr-2"></i> Filter
                                            </button>
                                            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form class="modal-content" method="get" action="">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                                            <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                                            <div class="mb-2">
                                                                <input type="text" name="application_no" value="{{ request('application_no') }}" placeholder="Application No." class="form-control">
                                                            </div>
                                                            <div class="mb-2">
                                                                <select class="form-select" name="status" id="">
                                                                    <option value="all" {{ request('status') == 'all' ? 'selected' : ''}}>All</option>
                                                                    @foreach ($_helper->identifyApplicationStatusesByCategory(request('category')) as $status)
                                                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : ''}}>{{ $_ltp_application->getApplicationCountsByStatus($status, null) }} - {{ $_helper->formatApplicationStatus($status) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary ">Filter</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="toggleSelectAll(this)">
                            </th>
                            <th>Permittee</th>
                            <th>Application No.</th>
                            <th width="300px" class="text-center">Date of Application</th>
                            <th width="300px" class="text-center">Date of Transport</th>
                            <th class="text-center">Last Modified</th>
                            <th class="text-center">Status</th>
                            <th width="200px" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltp_applications as $key => $ltp_application)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input select-row" value="{{ $ltp_application->id }}" onchange="toggleSelectRow(this)">
                                </td>
                                <td class="align-middle">{{ $ltp_application->permittee->user->personalInfo->first_name . ' ' . $ltp_application->permittee->user->personalInfo->last_name }}</td>
                                <td class="align-middle">{{ $ltp_application->application_no }}</td>
                                <td class="align-middle text-center">
                                    {{ $ltp_application->application_date->format("F d, Y") }}
                                </td>
                                <td class="align-middle text-center">
                                    {{ $ltp_application->transport_date->format("F d, Y") }}
                                </td>
                                {{-- <td class="align-middle text-center text-secondary">{{ $ltp_application->created_at->diffForHumans() }}</td> --}}
                                <td class="align-middle text-center text-secondary">{{ $ltp_application->updated_at->diffForHumans() }}</td>
                                <td class="align-middle text-center">
                                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    @can('view', $ltp_application)
                                        <a href="{{ route('ltpapplication.preview', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-info mb-2"  data-bs-toggle="tooltip" data-bs-title="Details">
                                            <i class="fas fa-file-alt"></i>
                                            Details
                                        </a>
                                    @endcan
                                    @can('accept', $ltp_application)
                                        <a href="#" onclick="showAcceptApplicationModal('{{ route('ltpapplication.accept', Crypt::encryptString($ltp_application->id)) }}')"  class="btn btn-sm btn-outline-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Accept/Receive"><i class="fas fa-check"></i></a>
                                    @endcan
                                    @if (in_array($ltp_application->application_status, ['submitted', 'resubmitted', 'under-review']) && Gate::allows('review', $ltp_application))
                                        <a href="#" onclick="showConfirmModal('{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}', 'Viewing this application will mark it as Under Review. Are you sure you want to continue?', 'Confirm Review', 'GET')" class="btn btn-sm btn-outline-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @if (
                                        in_array($ltp_application->application_status, ['under-review']) 
                                        && (Gate::allows('accept', $ltp_application ) || Gate::allows('reject', $ltp_application))
                                    )
                                        <a href="{{ route('ltpapplication.review', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-outline-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Review"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    @endif
                                    @can('generatePaymentOrder', $ltp_application)
                                        <a href="{{ route('paymentorder.create', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Generate Payment Order and Assesment of Fees and Charges">
                                            {{-- <i class="fas fa-file-invoice-dollar"></i> --}}
                                            Create Payment
                                        </a>
                                    @endcan
                                    @if (in_array($ltp_application->application_status, ['payment-in-process']))   
                                        <a href="{{ route('paymentorder.show', Crypt::encryptString($ltp_application->paymentOrder->id)) }}" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="View Payment Order"><i class="fas fa-file-invoice-dollar"></i></a>
                                    @endif
                                    @can('inspect', $ltp_application) 
                                        @if ($ltp_application->application_status == 'paid')
                                        <a href="{{ route('inspection.index', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="View Inspection">
                                            {{-- <i class="fas fa-eye"></i> --}}
                                            Inspect
                                        </a>
                                        @endif
                                        @if (in_array($ltp_application->application_status, ['inspected']))   
                                            @can('generateLtp', $ltp_application)
                                                <a href="{{ route('ltpapplication.permit', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Local Transport Permit">
                                                    <i class="fas fa-file-pdf"></i> 
                                                    {{ $ltp_application->permit ? "Edit LTP" : "Create LTP" }}
                                                </a>
                                            @endcan
                                            @if (($ltp_application->inspectionReport && (!$ltp_application->inspectionReport->permittee_signed || !$ltp_application->inspectionReport->inspector_signed || !$ltp_application->inspectionReport->approver_signed)) || ($ltp_application->application_status == "inspected" && !$ltp_application->inspectionReport))
                                                <a href="{{ route('inspection.createReport', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="Inspection Report">
                                                    <i class="fas fa-file-pdf"></i>
                                                    Edit Inspection Report
                                                </a>
                                            @endif
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
                                <td colspan="8" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-center">
                                {{ $ltp_applications->appends(request()->query())->links() }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
                {{-- {{ $ltp_applications->appends(request()->query())->links() }} --}}
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

@section('script-extra')
<script>
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.select-row');
        checkboxes.forEach((rowCheckbox) => {
            rowCheckbox.checked = checkbox.checked;
        });
    }

    function toggleSelectRow(checkbox) {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.select-row');
        const allChecked = Array.from(checkboxes).every((rowCheckbox) => rowCheckbox.checked);
        selectAllCheckbox.checked = allChecked;
        const selectedButton = document.querySelector('button[onclick="submitAcceptSelected()"]');
    }

    function submitAcceptAll() {
        const baseUrl = "{{ route('ltpapplication.accept-all') }}";
        showConfirmModal(
            baseUrl,
            "Are you sure you want to accept all applications?",
            "Confirm Accept All",
            "POST"
        );
    }

    function submitAcceptSelected() {
        const ids = Array.from(document.querySelectorAll('.select-row:checked'))
                        .map(checkbox => checkbox.value);

        if (ids.length === 0) {
            alert("Please select at least one application to accept.");
            return;
        }

        const baseUrl = "{{ route('ltpapplication.bulk-accept') }}";

        const targetUrl = `${baseUrl}?ids=${ids.join(',')}`;

        showConfirmModal(
            targetUrl,
            `Are you sure you want to accept the ${ids.length} selected application(s)?`,
            "Confirm Accept Selected",
            "POST"
        );
    }

</script>
@endsection