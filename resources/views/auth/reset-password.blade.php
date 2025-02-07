<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="New password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm password" required>
    <button type="submit">Reset Password</button>
</form>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
