<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            $sanctumToken = auth()->user()->createToken('authToken')->plainTextToken;
            // Store token in session
            session(['sanctumToken' => $sanctumToken]);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // Show the register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle the registration request
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        $user = auth()->user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Remove token from session
        $sanctumToken = session('sanctumToken');
        session()->forget('sanctumToken');

        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }

        // Delete the specific token
        if ($sanctumToken) {
            \Laravel\Sanctum\PersonalAccessToken::where('token', hash('sha256', $sanctumToken))->delete();
        }

        // Directly delete tokens from the database
        if ($user) {
            \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)->delete();
        }

        return redirect('/');
    }

    // Handle activate account
    public function activateAcount(Request $request, $user)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired activation link.');
        }

        // Retrieve the user and log them in
        $user = User::findOrFail($user);

        Auth::login($user);

        // Optionally, update the user record to mark the account as activated
        $user->update(['is_user_active' => 1]);

        return redirect('/dashboard')->with('success', 'Your account has been activated!');
    }
}
