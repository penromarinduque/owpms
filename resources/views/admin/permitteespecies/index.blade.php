@extends('layouts.master')

@section('title')
Permittee Species
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Permittee Species</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('permittees.index') }}">Permittees</a></li>
        <li class="breadcrumb-item active">Species</li>
    </ol>
</div>
@endsection