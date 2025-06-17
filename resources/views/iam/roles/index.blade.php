@extends('layouts.master')

@section('title')
    Roles
@endsection

@section('active-roles')
active
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">User Access</a></li>
        <li class="breadcrumb-item">Roles</li>
    </ol>

    <div class="d-flex justify-content-end gap-2 mb-2">
        <a href="{{ route('iam.roles.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Create Role</a>
    </div>

    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-list me-1"></i>
            List of Roles
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-hovered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->role_name }}</td>
                            <td>{{ $role->role_description }}</td>
                            <td class="text-center"><span class="badge text-{{ $role->is_active == 1 ? 'bg-primary' : 'bg-secondary' }}">{{ $role->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('iam.roles.edit', ['id' => Crypt::encryptString($role->id)]) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $roles->links() }}
        </div>
    </div>
</div>

@include('components.returnApplication')
@include('components.confirm')
@endsection
