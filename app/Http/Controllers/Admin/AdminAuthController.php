<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{

    public function showLogin()
    {
        return view('admin.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

            if (!Auth::guard('admin')->attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    $request->session()->regenerate();

    $user = Auth::guard('admin')->user();

    if (!in_array($user->role, ['admin', 'hype'])) {
        Auth::guard('admin')->logout();

        return back()->withErrors([
            'email' => 'Unauthorized access.'
        ]);
    }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}