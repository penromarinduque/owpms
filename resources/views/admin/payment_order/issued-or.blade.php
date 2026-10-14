@extends('layouts.master')

@section('title')
Issued OR
@endsection

@section('active-issuedor')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Issued OR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Issued OR</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            List of Issued Official Receipts
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <th>OR Number</th>
                        <th>Application No.</th>
                        <th>Permittee</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @forelse ($payment_orders as $po)
                            <tr>
                                <td>{{ $po->payment_reference }}</td>
                                <td>{{ $po->ltpApplication->application_no }}</td>
                                <td>{{ $po->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $po->ltpApplication->permittee->user->personalInfo->last_name }}</td>
                                <td>{{ $po->payment_date->format('F d, Y') }}</td>
                                <td><span class="badge rounded-pill bg-{{ $po->payment_method === 'cash' ? 'success' : 'info' }}">{{ strtoupper($po->payment_method) }}</span></td>
                                <td>
                                    <a href="{{ route('paymentorder.viewreceipt', Crypt::encryptString($po->id)) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $payment_orders->links() }}
            </div>
        </div>
    </div>
</div>


@endsection



