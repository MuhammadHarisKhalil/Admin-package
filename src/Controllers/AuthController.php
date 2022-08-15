<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Admin Login Page
     *
     * @return void
     */
    public function admin_Login_Page()
    {
        if (Auth::guard('admin')->user()) {
            return redirect()->route('admin-dashboard');
        }
        return view('backend_web.auth.login');
    }

    /**
     * Admin Login 
     *
     * @param Request $request
     * @return void
     */
    public function admin_Login(Request $request)
    {
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            if (Auth()->guard('admin')->check()) {
                return redirect()->route('admin-dashboard');
            } else {
                return redirect()->back()->withErrors(['alert-danger' => "Permission Denied"]);
            }
        }
        return back()->withErrors(['alert-danger' => "Invalid Credentials"]);
    }

    /**
     * Logout Admin
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('AdminLogin');
    }
}
