@extends('layouts.auth')

@section('title')
Account
@endsection


@section('content')
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input class="form-control mb-2" type="email" name="email" value="{{ request('email') }}" placeholder="Enter your email" readonly required>
        <input class="form-control mb-2" type="password" name="password" placeholder="New password" required>
        <input class="form-control mb-2" type="password" name="password_confirmation" placeholder="Confirm password" required>
        <button class="btn btn-primary w-100" type="submit">Reset Password</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection
