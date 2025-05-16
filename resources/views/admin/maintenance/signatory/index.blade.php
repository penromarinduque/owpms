@extends('layouts.master')

@section('title')
Signatory
@endsection

@section('active-signatories')
active
@endsection


@section('content')
<div class="container-fluid">
    <h2 class="mt-4">Signatory</h2>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item "><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Signatory</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user me-2"></i>Signatory List</div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <a class="btn btn-sm btn-primary" href="{{ route('signatories.create') }}"><i class="fas fa-plus"></i> Add Signatory</a>
            </div>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Document Type</th>
                        <th>Signatory Role</th>
                        <th>Signee</th>
                        <th class="text-center">Order</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($signatories as $signatory)
                        <tr>
                            <td>{{ $signatory->documentType->document_type }}</td>
                            <td>{{ $signatory->signatoryRole->signatory_role }}</td>
                            <td>{{ $signatory->user_id }}</td>
                            <td>{{ $signatory->order }}</td>
                            <td>
                                <a href="{{ route('signatories.edit', ['id'=>$signatory->id]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Signatories Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection