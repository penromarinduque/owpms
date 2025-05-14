@extends('layouts.master')

@section('title')
Edit User Role
@endsection


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">User Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">User Access</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Roles</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            Edir User Roles
        </div>
        <div class="card-body">
        </div>
    </div>
</div>
@endsection

