<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.posts');
        }
        
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $username = config('admin.username', 'admin');
        $password = config('admin.password');
        
        if (!$password) {
            return back()->with('error', 'Admin credentials not configured.');
        }
        
        if ($request->username === $username && Hash::check($request->password, $password)) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.posts');
        }
        
        return back()->with('error', 'Invalid credentials.');
    }
    
    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('blog.index');
    }
}
