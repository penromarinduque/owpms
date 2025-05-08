@extends('layouts.auth')

@section('content')
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <input class="form-control mb-2" type="email" name="email" placeholder="Enter your email" required>
        <button class="btn btn-primary d-block w-100" type="submit">Send Reset Link</button>
    </form>

    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="text-danger">{{ session('error') }}</p>
    @endif
@endsection