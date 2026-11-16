<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'required|in:client,boutique',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['role'] === 'boutique' ? 'waiting' : 'active',
        ]);

        Auth::login($user);

        // After register - redirect based on role
        if ($user->role === 'boutique') {
            return redirect()->route('waiting')->with('success', 'Inscription réussie! Veuillez attendre l\'approbation de l\'admin.');
        } else {
            return redirect()->route('client.dashboard')->with('success', 'Inscription réussie!');
        }
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'email' => 'Il n\'y a pas de compte avec cet email.',
            ])->withInput($request->only('email'));
        }

        // Check if password is correct
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Votre email ou mot de passe est incorrect.',
            ])->withInput($request->only('email'));
        }

        // ✅ NOW CHECK ROLE AND STATUS

        // 🏪 BOUTIQUE
        if ($user->role === 'boutique') {
            if ($user->status === 'waiting') {
                return back()->withErrors([
                    'email' => 'Votre compte n\'est pas encore activé. Veuillez attendre l\'approbation de l\'admin.',
                ])->withInput($request->only('email'));
            } elseif ($user->status === 'suspended') {
                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu. Vous ne pouvez plus accéder à votre boutique.',
                ])->withInput($request->only('email'));
            } elseif ($user->status === 'active') {
                Auth::login($user, $request->remember);
                $request->session()->regenerate();
                return redirect()->route('boutique.dashboard')->with('success', 'Connexion réussie!');
            }
        }

        // 👤 CLIENT
        elseif ($user->role === 'client') {
            if ($user->status === 'active') {
                Auth::login($user, $request->remember);
                $request->session()->regenerate();
                return redirect()->route('client.dashboard')->with('success', 'Connexion réussie!');
            }
        }

        // 🛡️ ADMIN
        elseif ($user->role === 'admin') {
            Auth::login($user, $request->remember);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Connexion réussie!');
        }

        return back()->withErrors([
            'email' => 'Erreur lors de la connexion.',
        ])->withInput($request->only('email'));
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Déconnexion réussie!');
    }
}