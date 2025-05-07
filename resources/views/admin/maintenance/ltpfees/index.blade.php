@extends('layouts.master')

@section('title')
LTP Fees
@endsection

@section('active-ltpfees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">LTP Fees</h2>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">LTP Fees</li>
    </ol>

    <div class="d-flex justify-content-end mb-2">
        <a class="btn btn-sm btn-primary" href="{{ route('ltpfees.create') }}"><i class="fas fa-plus"></i> Add Fee</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-money-check-alt me-2"></i>List of LTP Fees</div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Fee Name</th>
                        <th>Amount (Php)</th>
                        <th>Legal Basis</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr> <!-- Missing <tr> tag for each row -->
                            <td>{{ $fee->fee_name }}</td>
                            <td>{{ number_format($fee->amount, 2, '.', ',') }}</td>
                            <td>{{ $fee->legal_basis }}</td>
                            <td class="text-center">{{ $fee->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary" href="{{ route('ltpfees.edit', Crypt::encryptString($fee->id)) }}"><i class="fas fa-edit me-1"></i>Edit</a>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $fees->links() }}
        </div>
    </div>
</div>
@endsection
