@extends('layouts.master')

@section('title')
Local Transport Permit
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">Local Transport Permit</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Local Transport Permits</a></li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-file me-1"></i>
            Local Transport Permit
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Permittee</th>
                            <th>Expiration/Transport Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ltps as $ltp)
                            <tr>
                                <td>{{ $ltp->permit_number }}</td>
                                <td>{{ $ltp->ltpApplication->permittee->user->personalInfo->fullName }}</td>
                                <td>{{ $ltp->ltpApplication->transport_date->format('F d, Y') }}</td>
                                <td class="text-center">{!! $ltp->status !!}</td>
                                <td class="text-center">
                                    @can('viewPermit', $ltp)
                                        <a href="{{ route('ltps.viewPermit', Crypt::encryptString($ltp->id))}}" class="btn btn-sm btn-outline-primary">View</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $ltps->links() }}
            </div>
        </div>
    </div>
</div>
@endsection