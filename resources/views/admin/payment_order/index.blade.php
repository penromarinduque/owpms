@extends('layouts.master')

@section('title')
Order of Payments
@endsection

@section('active-paymentorder')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order of Payments</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">Applications</a></li>
        <li class="breadcrumb-item active">Order of Payments</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Order of Payments List
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <form action="" method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by Order Number or Permittee Name" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="250px">Order Number</th>
                            <th class="text-center" width="160px">Date Issued</th>
                            <th>Permittee</th>
                            <th width="170px" class="text-center">Payment Method</th>
                            <th>Payment Reference</th>
                            <th>Receipt</th>
                            <th class="text-center" width="80px">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentOrders as $paymentOrder)
                            <tr>
                                <td>{{ $paymentOrder->order_number }}</td>
                                <td class="text-center">{{ $paymentOrder->issued_date->format("F d, Y") }}</td>
                                <td>{{ $paymentOrder->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $paymentOrder->ltpApplication->permittee->user->personalInfo->last_name }}</td>
                                <td class="text-center">{{ strtoupper($paymentOrder->payment_method) }}</td>
                                <td>{{ $paymentOrder->payment_reference ? $paymentOrder->payment_reference : 'N/A' }}</td>
                                <td>{{ $paymentOrder->receipt ? $paymentOrder->receipt : 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-{{ $paymentOrder->status === 'pending' ? 'warning' : ($paymentOrder->status === 'paid' ? 'success' : 'danger') }}">{{ ucfirst($paymentOrder->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('paymentorder.show', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" data-bs-toggle="tooltip" data-bs-title="View Details"><i class="fas fa-eye"></i> Details</a>
                                    {{-- <a href="#" onclick="showUpdatePaymentModal('{{ route('paymentorder.update', Crypt::encryptString($paymentOrder->id)) }}')" class="btn btn-sm btn-outline-primary @if($paymentOrder->status !== 'pending') disabled @endif" data-bs-toggle="tooltip" data-bs-title="Update Payment"><i class="fas fa-edit me-1"></i>Update Payment</a>
                                    <a href="{{ route('paymentorder.print', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Print Order of Payment Template"><i class="fas fa-print "></i></a>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('paymentorder.download', Crypt::encryptString($paymentOrder->id)) }}" class="btn btn-sm btn-outline-primary {{ $paymentOrder->document ? '' : 'disabled' }}" data-bs-toggle="tooltip" data-bs-title="Download Signed Order of Payment"><i class="fas fa-file-download"></i></a>
                                        <a href="#" onclick="showUploadDocumentModal('{{ Crypt::encryptString($paymentOrder->id) }}')" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Update Signed Order of Payment"><i class="fas fa-upload "></i></a>
                                        <a href="{{ route('paymentorder.view', Crypt::encryptString($paymentOrder->id)) }}" target="_blank"  class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="View Signed Order of Payment"><i class="fas fa-eye "></i></a>
                                    </div> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $paymentOrders->links() }}
        </div>
    </div>
</div>

@include('components.uploadPaymentOrder')
@include('components.updatePayment')

@endsection


@section('script-extra')
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('.select2').select2();
    // });

    // $(function(){
    //     @if ($errors->updatePayment->any())
    //         $("#updatePaymentModal").modal("show");
    //     @endif
    // });


    // function generateBillNo() {
    //     const now = new Date();
    //     const year = now.getFullYear();
    //     const month = String(now.getMonth() + 1).padStart(2, '0');
    //     const day = String(now.getDate()).padStart(2, '0');
    //     const hour = String(now.getHours()).padStart(2, '0');
    //     const minute = String(now.getMinutes()).padStart(2, '0');
    //     const second = String(now.getSeconds()).padStart(2, '0');
    //     const milliseconds = String(now.getMilliseconds()).padStart(3, '0');
    //     const randomNumber = Math.floor(1000 + Math.random() * 9000); // 4-digit random number

    //     $('#bill_no').val(`${year}-${month}-${day}${hour}${minute}${second}${milliseconds}${randomNumber}`);
    // }

    
</script>
@endsection


