<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function loginSubmit(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Password salah');
        }

        Auth::login($user);
        return redirect()->route('dashboard.index');
    }

    public function logoutSubmit(Request $request)
    {
        Auth::logout();
        return redirect()->route('login.show');
    }
}
