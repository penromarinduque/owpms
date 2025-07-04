@extends('layouts.master')

@section('title')
Dashboard
@endsection

@section('active-dashboard')
active
@endsection

@section('content')
<div class="container-fluid p-4">
    {{-- <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href=""></a></li>
        <!-- <li class="breadcrumb-item active">Sidenav Light</li> -->
    </ol> --}}
    {{-- <div class="p-4 bg-light rounded-3"> --}}
        @include('components.permitteeDashboard')
        @include('components.adminDashboard')
    {{-- </div> --}}
</div>
@endsection