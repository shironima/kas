<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // dd([
            //     'user' => Auth::user(),
            //     'id' => Auth::user()->id,
            //     'name' => Auth::user()->name,
            //     'email' => Auth::user()->email,
            //     'rts_id' => Auth::user()->rts_id,
            //     'roles' => Auth::user()->getRoleNames(),
            // ]);            

            // Redirect berdasarkan role
            if ($user->hasRole('super_admin')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole('admin_rt')) {
                return redirect()->route('dashboardRT');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
