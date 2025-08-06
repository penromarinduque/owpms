@extends('layouts.master')

@section('title')
Order of Payments
@endsection

@section('active-paymentorder')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order of Payment</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">Applications</a></li>
        <li class="breadcrumb-item "><a href="{{ route('paymentorder.index') }}">Order of Payments</a></li>
        <li class="breadcrumb-item active">Order of Payment</li>
    </ol>


    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Details
        </div>
        <div class="card-body">
            <dl class="row ">
                <dt class="col-sm-3">Order Number</dt>
                <dd class="col-sm-9">{{ $paymentOrder->order_number }}</dd>
                <dt class="col-sm-3">Issued Date</dt>
                <dd class="col-sm-9 ">{{ $paymentOrder->issued_date->format("F d, Y") }}</dd>
                <dt class="col-sm-3">Permittee</dt>
                <dd class="col-sm-9">{{ $paymentOrder->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $paymentOrder->ltpApplication->permittee->user->personalInfo->last_name }}</dd>
                <dt class="col-sm-3">Payment Method</dt>
                <dd class="col-sm-9 ">{{ strtoupper($paymentOrder->payment_method) }}</dd>
                <dt class="col-sm-3">Payment Reference</dt>
                <dd class="col-sm-9">{{ $paymentOrder->payment_reference }}</dd>
                <dt class="col-sm-3">Receipt</dt>
                <dd class="col-sm-9">{{ $paymentOrder->receipt }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9 ">
                    <span class="badge rounded-pill bg-{{ $paymentOrder->status === 'pending' ? 'warning' : ($paymentOrder->status === 'paid' ? 'success' : 'danger') }}">{{ ucfirst($paymentOrder->status) }}</span>
                </dd>
                <dt class="col-sm-3">Actions</dt>
                <dd class="col-sm-9">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            @if (auth()->user()->can('updatePayment', $paymentOrder) || auth()->user()->can('viewReceipt', $paymentOrder))
                                <li><a class="dropdown-item" href="#" onclick="showUpdatePaymentModal('{{ route('paymentorder.update', Crypt::encryptString($paymentOrder->id)) }}')" @if($paymentOrder->status !== 'pending') disabled @endif data-bs-toggle="tooltip" data-bs-title="Update Payment"><i class="fas fa-edit me-1"></i>Update Payment</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('paymentorder.printBillingStatementTemplate', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" data-bs-toggle="tooltip" data-bs-title="Print Assesment of Fees and Charges Template"><i class="fas fa-print me-1"></i>Print Assesment of Fees and Charges Template</a></li>
                            <li><a class="dropdown-item" href="{{ route('paymentorder.printOopTemplate', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" data-bs-toggle="tooltip" data-bs-title="Print Order of Payment Template"><i class="fas fa-print me-1"></i>Print Order of Payment Template</a></li>
                            @can('downloadSignedOrder', $paymentOrder)
                                <li><a class="dropdown-item" href="{{ route('paymentorder.download', ['id' => Crypt::encryptString($paymentOrder->id), 'type' => 'payment_order']) }}" {{ $paymentOrder->document ? '' : 'disabled' }} data-bs-toggle="tooltip" data-bs-title="Download Signed Order of Payment"><i class="fas fa-file-download me-1"></i>Download Order of Payment</a></li>
                                <li><a class="dropdown-item" href="{{ route('paymentorder.download', ['id' => Crypt::encryptString($paymentOrder->id), 'type' => 'billing_statement']) }}" {{ $paymentOrder->document ? '' : 'disabled' }} data-bs-toggle="tooltip" data-bs-title="Download Signed Billing Statement"><i class="fas fa-file-download me-1"></i>Download Billing Statement</a></li>
                            @endcan
                            @can('uploadSignedOrder', $paymentOrder)
                                <li><a class="dropdown-item" href="#" onclick="showUploadDocumentModal('{{ Crypt::encryptString($paymentOrder->id) }}')" data-bs-toggle="tooltip" data-bs-title="Update Signed Order of Payment"><i class="fas fa-upload me-1"></i>Upload</a></li>
                            @endcan
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="float-end">
                {{-- <a href="{{ route('ltpapplication.show', Crypt::encryptString($paymentOrder->ltpApplication->id)) }}" class="btn btn-sm btn-outline-primary">View Application</a> --}}
            </div>
            <i class="fas fa-folder me-1"></i>
            Application Details
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Application No.</dt>
                <dd class="col-sm-9">
                    {{-- <a href="" target="_blank">{{ $paymentOrder->ltpApplication->application_no }}</a> --}}
                    {{ $paymentOrder->ltpApplication->application_no }}
                </dd>
                <dt class="col-sm-3">Wildlife Farm</dt>
                <dd class="col-sm-9">
                    {{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->farm_name }}
                </dd>
                <dt class="col-sm-3">Permittee</dt>
                <dd class="col-sm-9">
                    {{ $paymentOrder->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $paymentOrder->ltpApplication->permittee->user->personalInfo->last_name }}
                </dd>
                <dt class="col-sm-3">Application Status</dt>
                <dd class="col-sm-9">
                    <span class="badge rounded-pill bg-{{ $_helper->setApplicationStatusBsColor($paymentOrder->ltpApplication->application_status) }}">{{ ucfirst($paymentOrder->ltpApplication->application_status) }}</span>
                </dd>
            </dl>
        </div>
    </div>
</div>


@endsection

@section('includes')
    @include('components.uploadPaymentOrder')
    @include('components.updatePayment')
@endsection


@section('script-extra')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(function(){
        @if ($errors->updatePayment->any())
            $("#updatePaymentModal").modal("show");
        @endif
    });

    function generateBillNo() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hour = String(now.getHours()).padStart(2, '0');
        const minute = String(now.getMinutes()).padStart(2, '0');
        const second = String(now.getSeconds()).padStart(2, '0');
        const milliseconds = String(now.getMilliseconds()).padStart(3, '0');
        const randomNumber = Math.floor(1000 + Math.random() * 9000); // 4-digit random number

        $('#bill_no').val(`${year}-${month}-${day}${hour}${minute}${second}${milliseconds}${randomNumber}`);
    }

</script>
@endsection


