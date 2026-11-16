<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (auth()->check() && auth()->user()->status === 'active') {
            return redirect()->route('dashboard');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string',
            'role' => 'required|in:client,boutique',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'status' => 'waiting',
        ]);

        Auth::login($user);

        if ($user->role === 'boutique') {
            return redirect()->route('waiting.boutique');
        }
        
        return redirect()->route('waiting.client');
    }

    public function showLogin()
    {
        if (auth()->check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check remember me checkbox
        $remember = $request->boolean('remember');

        // Try delivery guard first with remember
        if (Auth::guard('delivery')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('delivery.dashboard');
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return redirect()->route('sorry');
        }

        // Attempt with remember me
        if (!Auth::attempt($credentials, $remember)) {
            return redirect()->route('sorry')->with('error', 'Invalid credentials');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        switch ($user->status) {
            case 'waiting':
                return $user->role === 'boutique' 
                    ? redirect()->route('waiting.boutique') 
                    : redirect()->route('waiting.client');
                    
            case 'suspended':
                return $user->role === 'boutique' 
                    ? redirect()->route('suspended.boutique') 
                    : redirect()->route('suspended.client');
                    
            case 'active':
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'boutique') {
                    return redirect()->route('boutique.dashboard');
                } elseif ($user->role === 'client') {
                    return redirect()->route('client.dashboard');
                }
                return redirect()->route('dashboard');
                
            default:
                Auth::logout();
                return redirect()->route('sorry')->with('error', 'Account status unknown');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}