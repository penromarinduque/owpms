@extends('layouts.auth')

@section('content')
<div class="card shadow-lg border-1 rounded-lg mt-1">
    <div class="card-header"><h3 class="text-center font-weight-light my-4"><i class="fas fa-lock"></i> Login</h3></div>
    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.store') }}" onsubmit="disableSubmitBtn('btn_login');">
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control" name="username" id="username" type="text" placeholder="Username" value="{{ old('username') }}" />
                <label for="username">Username</label>
                @error('username')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" name="password" id="password" type="password" placeholder="Password" />
                <label for="password">Password</label>
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" id="showpass" onclick="showPasswords('showpass', ['password']);">
                <label class="form-check-label" for="showpass">Show Password</label>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-1 mb-0">
                <a class="float-end" href="">Forgot Password?</a>
                {{--<a class="text-success" href="{{route('register')}}"><b>Create Account</b></a>--}}
                <button type="submit" name="btn_login" id="btn_login" class="btn btn-primary"><i class="fas fa-sign-in"></i> Login</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">Online Wildlife Permitting and Management System</div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    function disableSubmitBtn(btn) {
        document.getElementById(btn).disabled = true;
        document.getElementById(btn).innerHTML = '<i class="fas fa-sign-in"></i>  Logging in...';
    }
</script>
@endsection