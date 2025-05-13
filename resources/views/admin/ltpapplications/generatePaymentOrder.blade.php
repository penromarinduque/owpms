@extends('layouts.master')

@section('title')
Order of Payment
@endsection

@section('active-myapplication')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create Order of Payment Form</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('ltpapplication.index') }}">Applications</a></li>
        <li class="breadcrumb-item active">Create Order of Payment</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Order of Payment Form
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Bill No. <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="" name="bill_no" id="bill_no">
                            <button class="btn btn-sm btn-primary" type="button" onclick="generateBillNo();"><i class="fas fa-redo me-1"></i>Generate</button>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $_ltp_application->getWildlifeFarmLocation($ltp_application->permittee->user->id) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Nature of Application <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="LTP Application">
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Fees and Charges</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Legal Basis</th>
                                        <th style="width: 50%">Description</th>
                                        <th style="width: 20%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $ltp_fee->legal_basis }}
                                        </td>
                                        <td>
                                            {{ $ltp_fee->fee_name }}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" value="{{ $ltp_fee->amount }}" step="0.01">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total:</td>
                                        <td>₱ 0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Chief RPS Signature</h6>
                                <div class="border-bottom mb-2">SIMEON R. DIAZ</div>
                                <small class="text-muted">Revenue Collection Officer</small>
                                <button type="button" class="btn btn-outline-secondary btn-sm d-block mx-auto mt-3">
                                    <i class="fas fa-upload"></i> Upload Signature
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Chief TSD Signature</h6>
                                <div class="border-bottom mb-2">Engr. CYNTHIA U. LOZANO</div>
                                <small class="text-muted">Chief Technical Services Division</small>
                                <button type="button" class="btn btn-outline-secondary btn-sm d-block mx-auto mt-3">
                                    <i class="fas fa-upload"></i> Upload Signature
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <button type="submit" class="btn btn-success">
                        Create Order of Payment <i class="fas fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
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
</script>
@endsection


