<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Image;
use App\Models\Commande;
use App\Models\Categorie;
use App\Models\SiteStatistics;
use App\Models\LigneCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\BoutiqueOrderConfirmedNotification;
use App\Notifications\BoutiqueOrderDeclinedNotification;

class BoutiqueController extends Controller
{
    /**
     * Get the current activity percentage from site statistics.
     * Defaults to 10% if no record exists.
     */
    protected function getActivityPercentage(): float
    {
        $stats = SiteStatistics::first();
        return $stats ? (float) $stats->activity_percentage : 10.0;
    }
     public function dashboard()
    {
        $user = Auth::user();

        // Products count
        $produits_count = Produit::where('id_boutique', $user->id)->count();

        // Orders that contain at least one product from this boutique
        $commandes_count = Commande::whereHas('items.produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })->count();

        // Pending orders (status = pending) that contain this boutique's products
        $pending_orders = Commande::whereHas('items.produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })->where('status', 'pending')->count();

        // Revenue = sum of (price * quantity) for this boutique's items in confirmed orders
        $revenue = LigneCommande::whereHas('produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })->whereHas('commande', function($q) {
            $q->where('status', 'confirmed');
        })->sum(DB::raw('price * quantity'));

        // Recent confirmed orders (last 5) that contain this boutique's products
        $recent_orders = Commande::whereHas('items.produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })
        ->where('status', 'confirmed')
        ->with(['client', 'items']) 
        ->latest('date_cmd')
        ->take(5)
        ->get();

        return view('boutique.dashboard', compact(
            'produits_count', 'commandes_count', 'revenue',
            'pending_orders', 'recent_orders'
        ));
    }
    public function produits()
    {
        $user = Auth::user();
        $produits = Produit::where('id_boutique', $user->id)
            ->with(['categorie', 'images', 'imagePrincipale'])
            ->get();
        $siteFeePercent = $this->getActivityPercentage();
        return view('boutique.produits', [
            'produits' => $produits,
            'categories' => Categorie::all(),
            'siteFeePercent' => $siteFeePercent,
        ]);
    }

    public function categoryProducts($id_cat)
    {
        $user = Auth::user();
        $boutiqueId = $user->id;

        $categorie = Categorie::findOrFail($id_cat);

        $produits = Produit::where('id_boutique', $boutiqueId)
    ->where('id_cat', $id_cat)
    ->with(['categorie', 'images', 'imagePrincipale', 'reviews.user', 'ratings'])
    ->get();

        $total_items = $produits->count();
        $approved_count = $produits->where('status', 'approved')->count();
        $pending_count = $produits->where('status', 'pending')->count();
        // Revenue based on boutique's actual earnings
        $total_revenue = LigneCommande::whereHas('produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })->whereHas('commande', function($q) {
            $q->where('status', 'confirmed');
        })->sum(DB::raw('price * quantity'));

        $categories = Categorie::all();

        return view('boutique.cat_prod', [
            'categorie' => $categorie,
            'produits' => $produits,
            'total_items' => $total_items,
            'approved_count' => $approved_count,
            'pending_count' => $pending_count,
            'total_revenue' => $total_revenue,
            'categories' => $categories,
        ]);
    }

    public function addProductForm()
    {
        $categories = Categorie::all();
        $siteFeePercent = $this->getActivityPercentage();

        return view('boutique.add-product', compact('categories', 'siteFeePercent'));
    }

    public function addProduct(Request $request)
    {
        $activityPercentage = $this->getActivityPercentage();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'id_cat' => 'required|exists:categories,id_cat',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',           // Boutique's desired earning
            'prix_final' => 'required|numeric|min:0',     // Customer pays this
            'site_fee_amount' => 'required|numeric|min:0', // Site commission
            'stock' => 'required|integer|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'images.required' => 'Please add at least one image.',
            'images.min' => 'Please add at least one image.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Accepted formats: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Each image must not exceed 2MB.',
        ]);

        $user = Auth::user();
        $validated['id_boutique'] = $user->id;
        $validated['status'] = 'pending';

        // Price breakdown
        $boutiquePrice = (float) $validated['prix'];
        $customerPrice = (float) $validated['prix_final'];
        $siteFee = (float) $validated['site_fee_amount'];

        // Server-side validation: ensure math checks out
        $expectedCustomerPrice = $boutiquePrice / (1 - $activityPercentage / 100);
        $expectedSiteFee = $expectedCustomerPrice - $boutiquePrice;

        // Allow small floating point tolerance
        if (abs($customerPrice - $expectedCustomerPrice) > 0.01 || abs($siteFee - $expectedSiteFee) > 0.01) {
            return back()->withInput()->with('error', 'Price calculation mismatch. Please try again.');
        }

        // Store the prices correctly
        $validated['prix_boutique'] = $boutiquePrice;   // What boutique earns
        $validated['prix'] = $customerPrice;             // What customer pays (display price)
        $validated['site_fee'] = $siteFee;               // Site commission amount

        // Remove frontend-only fields
        unset($validated['prix_final']);
        unset($validated['site_fee_amount']);

        unset($validated['images']);

        DB::beginTransaction();

        try {
            $produit = Produit::create($validated);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('produits/' . $produit->id_product, 'public');
                    Image::create([
                        'path' => $path,
                        'alt' => $produit->nom . ' - Image ' . ($index + 1),
                        'is_primary' => $index === 0,
                        'id_product' => $produit->id_product,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('boutique.produits')->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    public function categories()
    {
        $user = Auth::user();
        $boutiqueId = $user->id;

        $categories = Categorie::with('produits')->get();
        $myProducts = Produit::where('id_boutique', $boutiqueId)->get();
        $revenue = LigneCommande::whereHas('produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })->whereHas('commande', function($q) {
            $q->where('status', 'confirmed');
        })->sum(DB::raw('price * quantity'));
        return view('boutique.categories', [
            'categories' => $categories,
            'total_products' => $myProducts->count(),
            'approved_count' => $myProducts->where('status', 'approved')->count(),
            'total_revenue' => $revenue,
        ]);
    }

    public function editProductForm($id)
    {
        $produit = Produit::with(['images', 'imagePrincipale'])->findOrFail($id);
        $categories = Categorie::all();
        $siteFeePercent = $this->getActivityPercentage();

        if ($produit->id_boutique !== Auth::id()) {
            return back()->with('error', 'Access denied!');
        }

        return view('boutique.edit-product', [
            'produit' => $produit,
            'categories' => $categories,
            'siteFeePercent' => $siteFeePercent,
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $produit = Produit::with('images')->findOrFail($id);
        $activityPercentage = $this->getActivityPercentage();

        if ($produit->id_boutique !== Auth::id()) {
            return back()->with('error', 'Access denied!');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'id_cat' => 'required|exists:categories,id_cat',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'prix_final' => 'required|numeric|min:0',
            'site_fee_amount' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Price breakdown validation (same as add)
        $boutiquePrice = (float) $validated['prix'];
        $customerPrice = (float) $validated['prix_final'];
        $siteFee = (float) $validated['site_fee_amount'];

        $expectedCustomerPrice = $boutiquePrice / (1 - $activityPercentage / 100);
        $expectedSiteFee = $expectedCustomerPrice - $boutiquePrice;

        if (abs($customerPrice - $expectedCustomerPrice) > 0.01 || abs($siteFee - $expectedSiteFee) > 0.01) {
            return back()->withInput()->with('error', 'Price calculation mismatch. Please try again.');
        }

        $validated['prix_boutique'] = $boutiquePrice;
        $validated['prix'] = $customerPrice;
        $validated['site_fee'] = $siteFee;

        unset($validated['prix_final']);
        unset($validated['site_fee_amount']);
        unset($validated['images']);

        DB::beginTransaction();

        try {
            $produit->update($validated);

            if ($request->hasFile('images')) {
                $isFirst = $produit->images()->count() === 0;
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('produits/' . $produit->id_product, 'public');
                    Image::create([
                        'path' => $path,
                        'alt' => $produit->nom . ' - Image ' . ($index + 1),
                        'is_primary' => $isFirst && $index === 0,
                        'id_product' => $produit->id_product,
                    ]);
                    $isFirst = false;
                }
            }

            DB::commit();

            return redirect()->route('boutique.produits')->with('success', 'Product updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        $produit = Produit::with('images')->findOrFail($id);

        if ($produit->id_boutique !== Auth::id()) {
            return back()->with('error', 'Access denied!');
        }

        foreach ($produit->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        Storage::disk('public')->deleteDirectory('produits/' . $produit->id_product);

        $produit->delete();

        return back()->with('success', 'Product deleted!');
    }

    // ── IMAGE MANAGEMENT ───────────────────────────────────

    public function deleteImage($id_product, $id_image)
    {
        $produit = Produit::findOrFail($id_product);

        if ($produit->id_boutique !== Auth::id()) {
            return back()->with('error', 'Access denied!');
        }

        $image = Image::where('id_image', $id_image)
                      ->where('id_product', $id_product)
                      ->firstOrFail();

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if ($image->is_primary) {
            $nextImage = Image::where('id_product', $id_product)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted!');
    }

    public function setPrimaryImage($id_product, $id_image)
    {
        $produit = Produit::findOrFail($id_product);

        if ($produit->id_boutique !== Auth::id()) {
            return back()->with('error', 'Access denied!');
        }

        $image = Image::where('id_image', $id_image)
                      ->where('id_product', $id_product)
                      ->firstOrFail();

        Image::where('id_product', $id_product)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated!');
    }

    public function commandes()
    {
        $user = Auth::user();

        $commandes = Commande::whereHas('items.produit', function($q) use ($user) {
            $q->where('id_boutique', $user->id);
        })
        ->with(['client', 'items.produit.boutique'])
        ->get();

        return view('boutique.commandes', ['commandes' => $commandes]);
    }

    public function confirmCommande($id)
{
    $commande = Commande::where('id_cmd', $id)
        ->whereHas('items.produit', function($q) {
            $q->where('id_boutique', Auth::id());
        })
        ->with('items.produit', 'client')
        ->first();

    if (!$commande) {
        return back()->with('error', 'Order not found.');
    }

    if ($commande->status !== 'pending') {
        return back()->with('error', 'This order has already been processed.');
    }

    $commande->update([
        'status' => 'confirmed',
        'updated_at' => now(),
    ]);

    if ($commande->client) {
        $commande->client->notify(new BoutiqueOrderConfirmedNotification($commande, Auth::user()));
    }

    return back()->with('success', 'Order confirmed successfully!');
}


public function declineCommande($id)
{
    $commande = Commande::where('id_cmd', $id)
        ->whereHas('items.produit', function($q) {
            $q->where('id_boutique', Auth::id());
        })
        ->with('items.produit')
        ->first();

    if (!$commande) {
        return back()->with('error', 'Order not found.');
    }

    if ($commande->status !== 'pending') {
        return back()->with('error', 'This order has already been processed.');
    }

    DB::beginTransaction();
    try {
        foreach ($commande->items as $item) {
            if ($item->produit && $item->produit->id_boutique == Auth::id()) {
                $item->produit->increaseStock($item->quantity);
            }
        }

        $commande->update([
            'status' => 'declined',
            'updated_at' => now(),
        ]);
        if ($commande->client) {
                $commande->client->notify(new BoutiqueOrderDeclinedNotification($commande, Auth::user()));
            }
        DB::commit();
        return back()->with('success', 'Order declined.Client notified.Stock restored.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong. Please try again.');
    }
}


    public function settings()
    {
        return view('boutique.settings');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('boutiques', 'public');
            $validated['image'] = $imagePath;
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);
        unset($validated['password_confirmation']);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}