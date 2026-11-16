<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\User;

class PageController extends Controller
{
    public function home()
    {
        $featuredProducts = Produit::where('status', 'approved')
            ->where('stock', '>', 0)
            ->with(['boutique', 'categorie'])
            ->latest()
            ->take(8)
            ->get();


        $vaults = User::where('role', 'boutique')
            ->where('status', 'active')
            ->withCount(['produits' => function($query) {
                $query->where('status', 'approved');
            }])
            ->take(6)
            ->get();

        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->status === 'active') {
                return redirect()->route('dashboard');
            }
            
            if ($user->status === 'waiting') {
                return $user->role === 'boutique' 
                    ? redirect()->route('waiting.boutique') 
                    : redirect()->route('waiting.client');
            }
            
            if ($user->status === 'suspended') {
                return $user->role === 'boutique' 
                    ? redirect()->route('suspended.boutique') 
                    : redirect()->route('suspended.client');
            }
        }
        
        return view('home',compact('featuredProducts','vaults'));
    }

    public function choice()
    {
        return view('choice');
    }

    public function dashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        if ($user->status === 'waiting') {
            return $user->role === 'boutique' 
                ? redirect()->route('waiting.boutique') 
                : redirect()->route('waiting.client');
        }
        
        if ($user->status === 'suspended') {
            return $user->role === 'boutique' 
                ? redirect()->route('suspended.boutique') 
                : redirect()->route('suspended.client');
        }
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'boutique') {
            return redirect()->route('boutique.dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        
        return view('dashboard');
    }

    public function waitingBoutique()
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }
        
        $user = auth()->user();
        
        if ($user->status === 'active') {
            return redirect()->route('dashboard');
        }
        
        if ($user->status === 'suspended') {
            return redirect()->route('suspended.boutique');
        }
        
        if ($user->role !== 'boutique') {
            return redirect()->route('waiting.client');
        }
        
        return view('boutique.waiting');
    }

    public function waitingClient()
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }
        
        $user = auth()->user();
        
        if ($user->status === 'active') {
            return redirect()->route('dashboard');
        }
        
        if ($user->status === 'suspended') {
            return redirect()->route('suspended.client');
        }
        
        if ($user->role !== 'client') {
            return redirect()->route('waiting.boutique');
        }
        
        return view('client.waiting');
    }

    public function suspendedBoutique()
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }
        
        $user = auth()->user();
        
        if ($user->status === 'active') {
            return redirect()->route('dashboard');
        }
        
        if ($user->status === 'waiting') {
            return redirect()->route('waiting.boutique');
        }
        
        if ($user->role !== 'boutique') {
            return redirect()->route('suspended.client');
        }
        
        return view('boutique.suspended');
    }

    public function suspendedClient()
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }
        
        $user = auth()->user();
        
        if ($user->status === 'active') {
            return redirect()->route('dashboard');
        }
        
        if ($user->status === 'waiting') {
            return redirect()->route('waiting.client');
        }
        
        if ($user->role !== 'client') {
            return redirect()->route('suspended.boutique');
        }
        
        return view('client.suspended');
    }

    public function sorry()
    {
        return view('sorry');
    }

    public function allCategories()
    {
        $categories = \App\Models\Categorie::all();
        return view('categories.all', compact('categories'));
    }

    public function categoryProducts($id)
    {
        $category = \App\Models\Categorie::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function categoryItems($id)
    {
        $category = \App\Models\Categorie::findOrFail($id);
        $produits = \App\Models\Produit::where('id_cat', $id)
            ->where('status', 'active')
            ->get();
        
        return view('categories.items', compact('category', 'produits'));
    }
}