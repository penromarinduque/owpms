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
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Update Payment">
                            <i class="fas fa-edit me-1"></i>Update Payment
                        </a>
                        <a href="{{ route('paymentorder.print', Crypt::encryptString($paymentOrder->id)) }}" target="_blank" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Print Order of Payment Template">
                            <i class="fas fa-print me-1"></i>Print Template
                        </a>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('paymentorder.download', Crypt::encryptString($paymentOrder->id)) }}" class="btn btn-sm btn-outline-primary {{ $paymentOrder->document ? '' : 'disabled' }}" data-bs-toggle="tooltip" data-bs-title="Download Signed Order of Payment">
                                <i class="fas fa-file-download me-1"></i>Download
                            </a>
                            <a href="#" onclick="showUploadDocumentModal('{{ Crypt::encryptString($paymentOrder->id) }}')" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Update Signed Order of Payment">
                                <i class="fas fa-upload me-1"></i>Upload
                            </a>
                        </div>
                    </div>
                </dd>
            </dl>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadDocumentModal">
    <form method="POST" action="{{ route('paymentorder.upload', Crypt::encryptString($paymentOrder->id)) }}" class="modal-dialog" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Document File</label>
                    <input type="file" accept="application/pdf" class="form-control @error('document_file', 'upload') is-invalid @enderror" name="document_file" placeholder="Document File" required>
                    @error('document_file', 'upload')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>  
                <button type="submit" class="btn btn-primary btn-submit">Upload</button>
            </div>
        </div>
    </form>
</div>
@endsection


@section('script-extra')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();


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

    function showUploadDocumentModal(encryptedId) {
        $('#uploadDocumentModal form').attr('action', "{{ route('paymentorder.upload', ':id') }}".replace(':id', encryptedId));
        $('#uploadDocumentModal').modal('show');
    }
</script>
@endsection


