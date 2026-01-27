<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // If user is already authenticated, redirect to home
        if (Auth::guard('web')->check()) {
            return redirect()->route('customer.home.index');
        }

        // Handle GET request - show login form
        if ($request->isMethod('GET')) {
            return view('customer.login.index');
        }

        // Handle POST request - process login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check intended URL or default to home
            return redirect()->intended(route('customer.home.index'));
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.home.index');
    }

    public function signup(Request $request)
    {
        if ($request->method() == "POST") {

        } else {
            return view("customer.signup.index");
        }
    }
}
