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
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">User Access</a></li>
        <li class="breadcrumb-item"><a href="{{ route('iam.roles.index') }}">Roles</a></li>
        <li class="breadcrumb-item"><a href="#">Edit Role</a></li>
    </ol>

    <form method="POST" action="{{ route('iam.roles.update', ['id' => Crypt::encryptString($role->id)]) }}" class="card mb-4">
        @csrf
    	<div class="card-header">
            <i class="fas fa-list me-1"></i>
            Create Role
        </div>
        <div class="card-body">
            <div class="mb-2">
                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('role_name') is-invalid @enderror" id="role_name" name="role_name" value="{{ old('role_name') ?? $role->role_name }}" placeholder="Role Name" required>
                @error('role_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="role_description" class="form-label">Role Description <span class="text-danger">*</span></label>
                <textarea class="form-control @error('role_description') is-invalid @enderror" id="role_description" name="role_description" placeholder="Role Description" required>{{ old('role_description') ?? $role->role_description }}</textarea>
                @error('role_description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="is_active" class="form-label">Role Status <span class="text-danger">*</span></label>
                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                    <option value="1" {{ old('is_active') ?? $role->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') ?? $role->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="permissionsAccordion" class="form-label">Select Permissions <span class="text-danger">*</span></label>
                <div class="accordion form-control @error('permissions') is-invalid @enderror p-0" id="permissionsAccordion">
                    @foreach ($permissions->unique('permission_group') as $pg)
                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="{{ '#'.$pg->permission_group }}" aria-expanded="true" aria-controls="collapseOne">
                                    {{ $pg->permission_group }}
                                </button>
                            </h2>
                            <div id="{{ $pg->permission_group }}" class="accordion-collapse collapse show" data-bs-parent="#permissionsAccordion">
                                <div class="accordion-body">
                                    @foreach ($permissions->where('permission_group', $pg->permission_group) as $permission)
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->permission_tag }}" id="permission{{ $permission->id }}" {{ in_array($permission->permission_tag, $role_permissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                                    {{ $permission->permission_tag }}
                                                </label>
                                                <p class="form-text">{{ $permission->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save</button>
            </div>
        </div>
    </form>
</div>

@include('components.returnApplication')
@include('components.confirm')
@endsection
