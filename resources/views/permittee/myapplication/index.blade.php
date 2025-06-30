
@extends('layouts.master')

@section('title')
Permittees
@endsection

@section('active-myapplications')
active
@endsection

@php
    $status = request('status') ?? 'draft';
@endphp

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{$title}}</li>
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
                    <a class="nav-link {{ request('category') == 'draft' ? 'active' : '' }}" aria-current="page" href="?status=draft&category=draft">
                        <i class="fas fa-file-alt me-1"></i>
                        Draft 
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?status=all&category=submitted" class="nav-link {{ request('category') == 'submitted' ? 'active' : ''}}"><i class="fas fa-file-import me-1"></i>Submitted</a>
                </li>
                <li class="nav-item">
                    <a href="?status=all&category=reviewed" class="nav-link {{ request('category') == 'reviewed' ? 'active' : ''}}"><i class="fas fa-file-export me-1"></i>Reviewed</a>
                </li>
                <li class="nav-item">
                    <a href="?status=returned&category=returned" class="nav-link {{ request('category') == 'returned' ? 'active' : ''}}"><i class="fas fa-undo me-1"></i>Returned</a>
                </li>
                <li class="nav-item">
                    <a href="?status=all&category=accepted" class="nav-link {{ request('category') == 'accepted' ? 'active' : ''}}"><i class="fas fa-check-circle me-1"></i>Accepted</a>
                </li>
                <li class="nav-item">
                    <a href="?status=all&category=rejected" class="nav-link {{ request('category') == 'rejected' ? 'active' : ''}}"><i class="fas fa-times-circle me-1"></i>Rejected</a>
                </li>
                <li class="nav-item">
                    <a href="?status=approved&category=approved" class="nav-link {{ request('category') == 'approved' ? 'active' : ''}}"><i class="fas fa-check-circle me-1"></i>Approved</a>
                </li>
                <li class="nav-item">
                    <a href="?status=expired&category=expired" class="nav-link {{ request('category') == 'expired' ? 'active' : ''}}"><i class="fas fa-calendar-times me-1"></i>Expired</a>
                </li>
            </ul>

            {{-- <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link {{ $status == 'draft' ? 'active' : '' }}" aria-current="page" href="?status=draft">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('draft', $permittee->id); @endphp
                    Draft 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'submitted' ? 'active' : '' }}" href="?status=submitted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('submitted', $permittee->id); @endphp
                    Submitted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'resubmitted' ? 'active' : '' }}" href="?status=resubmitted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('resubmitted', $permittee->id); @endphp
                    Resubmitted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span> 
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'under-review' ? 'active' : '' }}" href="?status=under-review">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('under-review', $permittee->id); @endphp
                    Under Review 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'returned' ? 'active' : '' }}" href="?status=returned">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('returned', $permittee->id); @endphp
                    Returned 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'accepted' ? 'active' : '' }}" href="?status=accepted">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('accepted', $permittee->id); @endphp
                    Accepted 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'payment-in-process' ? 'active' : '' }}" href="?status=payment-in-process">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('payment-in-process', $permittee->id); @endphp
                    Payment In Processs 
                     <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'paid' ? 'active' : '' }}" href="?status=paid">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('paid', $permittee->id); @endphp
                    For Inspection 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'approved' ? 'active' : '' }}" href="?status=approved">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('approved', $permittee->id); @endphp
                    Approved 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status == 'expired' ? 'active' : '' }}" href="?status=expired">
                    @php $count = $_ltp_application->getApplicationCountsByStatus('expired', $permittee->id); @endphp
                    Expired 
                    <span class="badge rounded-pill bg-primary">{{ $count > 0 ? ($count > 99 ? '99+' : $count): '' }}</span>
                </a>
              </li>
            </ul> --}}

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
                <table class="table table-hover table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No.</th>
                            <th>Date Created</th>
                            <th>Last Modified</th>
                            <th class="text-center">Status</th>
                            <th width="200px" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltp_applications as $key => $ltp_application)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $ltp_application->application_no }}</td>
                                <td class="align-middle">{{ $ltp_application->created_at->format('F d, Y') }}</td>
                                <td class="align-middle">{{ $ltp_application->updated_at->format('F d, Y') }}</td>
                                <td class="align-middle text-center">
                                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('myapplication.preview', Crypt::encryptString($ltp_application->id)) }}" class="btn btn-sm btn-info mb-2"  data-bs-toggle="tooltip" data-bs-title="Details">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    @if ($status == 'draft')                                        
                                        <a href="{{ route('myapplication.edit', Crypt::encryptString($ltp_application->id)) }}"  class="btn btn-sm btn-warning mb-2" data-bs-toggle="tooltip" data-bs-title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endif
                                    @if ($status == 'draft')
                                        <a href="#" onclick="showSubmitApplicationModal('{{ route('myapplication.submit', Crypt::encryptString($ltp_application->id)) }}')"  class="btn btn-sm btn-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Submit">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </a>
                                    @endif
                                    @if ($status == 'draft')                                        
                                        <a href="#" class="btn btn-sm btn-danger mb-2" onclick="showConfirDeleteModal ('{{ route('myapplication.destroy', $ltp_application->id) }}' ,{{ $ltp_application->id }}, 'Are you sure you want to delete this application?', 'Delete Application')"  data-bs-toggle="tooltip" data-bs-title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('myapplication.requirements', ['id'=>Crypt::encryptString($ltp_application->id)]) }}" class="btn btn-sm btn-warning mb-2" data-bs-toggle="tooltip" data-bs-title="Requirements">
                                        <i class="fa-solid fa-file"></i>
                                    </a>
                                    @if ($status == 'returned')
                                        <a href="#" onclick="showResubmitApplicationModal('{{ route('myapplication.resubmit', Crypt::encryptString($ltp_application->id)) }}')" class="btn btn-sm btn-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Resubmit">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </a>
                                    @endif
                                    @if (in_array($status, ['payment-in-process']))
                                        <a href="{{ route('paymentorder.view', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Order of Payment">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                    @endif
                                    @if (in_array($status, ['paid', 'approved']))
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="#" onclick="showUploadReceiptModal('{{ route('myapplication.uploadreceipt', Crypt::encryptString($ltp_application->id)) }}')"class="btn btn-sm btn-outline-primary mb-2"  data-bs-toggle="tooltip" data-bs-title="Upload Receipt">
                                                <i class="fa-solid fa-receipt"></i>
                                            </a>
                                            @php
                                                $paymentOrder = $ltp_application->paymentOrder;
                                            @endphp
                                            <a href="{{ route('paymentorder.viewreceipt', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2 @if(!$paymentOrder->receipt_url) disabled @endif"  data-bs-toggle="tooltip" data-bs-title="View Receipt">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    @endif
                                    @if (in_array($status, ['paid', 'inspection-rejected']))   
                                        <a href="{{ route('inspection.index', Crypt::encryptString($ltp_application->id)) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2"  data-bs-toggle="tooltip" data-bs-title="View Inspection"><i class="fas fa-eye"></i></a>
                                    @endif
                                    @if ($status != 'draft')
                                        <a href="#" onclick="showViewApplicationLogsModal({{ $ltp_application->id }})" class="btn btn-sm btn-success mb-2"  data-bs-toggle="tooltip" data-bs-title="Logs">
                                            <i class="fas fa-history"></i>
                                        </a>
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



<div class="modal fade" id="resubmitApplicationModal">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Resubmit Application</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to resubmit this application? This action cannot be undone</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Resubmit</button>
            </div>
        </form >
    </div>
</div>

@endsection

@section('script-extra')
<script type="text/javascript">
    function ajaxUpdateStatus(chkbox_id, permittee_id) {
        var chkd = $('#'+chkbox_id).is(':checked');
        var stat = 0;
        if (chkd) {
            stat = 1;
        }
        // console.log(stat);
        $.ajax({
            type: 'POST',
            url: "{{ route('permittees.ajaxupdatestatus') }}",
            data: {permittee_id:permittee_id, is_active_permittee:stat},
            success: function (result){
                // console.log(result);
            },
            error: function (result){
                // console.log(result);
                alert('Oops! Something went wrong. Please reload the page and try again.');
            }
        });
    }

    
    function showResubmitApplicationModal(action){
        $('#resubmitApplicationModal form').attr('action', action);
        $('#resubmitApplicationModal').modal('show');
    }
</script>
@endsection

@section('includes')
    @include('components.confirmDelete')
    @include('components.viewApplicationLogs')
    @include('components.permitteeUploadReceipt')
    @include('components.submitApplication')
@endsection


