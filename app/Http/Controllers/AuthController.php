<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // CEK ROLE
            if (Auth::user()->role == 'admin') {
                return response()->json([
                    'success' => true,
                    'redirect' => route('products.index')
                ]);
            }

            if (Auth::user()->role == 'kasir') {
                return response()->json([
                    'success' => true,
                    'redirect' => route('transactions.index')
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
