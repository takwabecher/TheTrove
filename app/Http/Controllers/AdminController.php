<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\SiteStatistics;
use \App\Models\DeliveryCompany;
use App\Models\Rating;
use App\Notifications\AccountDeletedNotification;
use App\Notifications\AccountRejectedNotification;
use App\Notifications\AccountReactivatedNotification;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\ProductApprovedNotification;
use App\Notifications\ProductRejectedNotification;
use App\Notifications\ProductDeletedNotification;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = SiteStatistics::first();
        if (!$stats) {
            $stats = SiteStatistics::create([
                'activity_percentage' => 10,
                'total_orders' => 0,
                'total_revenue' => 0,
            ]);
        }

        $total_clients = User::where('role', 'client')->where('status', 'active')->count();
        $total_boutiques = User::where('role', 'boutique')->where('status', 'active')->count();
        $approved_items = Produit::where('status', 'approved')->count();
        $total_categories = Categorie::count();
        $total_delivery = DeliveryCompany::where('is_active','1')->count();
        $pending_boutiques = User::where('role', 'boutique')->where('status', 'waiting')->count();
        $pending_items = Produit::where('status', 'pending')->count();

        $confirmed_orders = Commande::where('status', 'confirmed')->count();
        $total_sales = Commande::where('status', 'confirmed')->sum('total_price');
        $site_fee_percent = $stats->activity_percentage;
        $total_revenue = $total_sales * ($site_fee_percent / 100);
        $avg_product_rating = Rating::avg('rating') ?? 0;

        $recent_orders = Commande::where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_clients',
            'total_boutiques',
            'approved_items',
            'total_categories',
            'total_delivery',
            'pending_boutiques',
            'pending_items',
            'confirmed_orders',
            'total_sales',
            'total_revenue',
            'site_fee_percent',
            'stats',
            'avg_product_rating',
            'recent_orders'
        ));
    }

    public function boutiques()
    {
        $pendingBoutiques = User::where('role', 'boutique')->where('status', 'waiting')->get();
        $approvedBoutiques = User::where('role', 'boutique')->where('status', 'active')->get();
        $suspendedBoutiques = User::where('role', 'boutique')->where('status', 'suspended')->get();

        return view('admin.boutiques', compact('pendingBoutiques', 'approvedBoutiques', 'suspendedBoutiques'));
    }

    public function approveBoutique($id)
    {
        $boutique = User::findOrFail($id);
        $boutique->update(['status' => 'active']);
        $boutique->notify(new AccountApprovedNotification());
        return back()->with('success', 'Vault approved successfully.');
    }

    public function rejectBoutique($id)
    {
        $boutique = User::findOrFail($id);
        $boutique->notify(new AccountRejectedNotification(
            'Your vault application did not meet our current platform requirements.'
        ));
        $boutique->delete();
        return back()->with('success', 'Vault rejected.');
    }

    public function suspendBoutique($id)
    {
        $boutique = User::findOrFail($id);
        $boutique->update(['status' => 'suspended']);
        $boutique->notify(new AccountSuspendedNotification(
            'Violation of platform terms and conditions.'
        ));
        return back()->with('success', 'Vault suspended.');
    }

    public function activateBoutique($id)
    {
        $boutique = User::findOrFail($id);
        $boutique->update(['status' => 'active']);
        $boutique->notify(new AccountReactivatedNotification());
        return back()->with('success', 'Vault reactivated.');
    }

    public function deleteBoutique($id)
    {
        $boutique = User::findOrFail($id);
    
        $boutique->notify(new AccountDeletedNotification(
            'Your vault was permanently deleted by administrator decision.'
        ));
    
        $boutique->delete();
    
        return back()->with('success', 'Vault deleted and notified.');
    }

    public function clients()
    {
        $pendingClients = User::where('role', 'client')->where('status', 'waiting')->get();
        $approvedClients = User::where('role', 'client')->where('status', 'active')->get();
        $suspendedClients = User::where('role', 'client')->where('status', 'suspended')->get();

        return view('admin.clients', compact('pendingClients', 'approvedClients', 'suspendedClients'));
    }

    public function approveClient($id)
    {
        $client = User::findOrFail($id);
        $client->update(['status' => 'active']);
        $client->notify(new AccountApprovedNotification());
        return back()->with('success', 'Client approved.');
    }

    public function declineClient($id)
    {
        $client = User::findOrFail($id);
        $client->notify(new AccountRejectedNotification(
            'Your account registration did not meet our verification requirements.'
        ));
        $client->delete();
        return back()->with('success', 'Client declined.');
    }

    public function suspendClient($id)
    {
        $client = User::findOrFail($id);
        $client->update(['status' => 'suspended']);
        $client->notify(new AccountSuspendedNotification(
            'Violation of platform terms and conditions.'
        ));
        return back()->with('success', 'Client suspended.');
    }

    public function reactivateClient($id)
    {
        $client = User::findOrFail($id);
        $client->update(['status' => 'active']);
        $client->notify(new AccountReactivatedNotification());
        return back()->with('success', 'Client reactivated.');
    }

    public function deleteClient($id)
    {
        $client = User::findOrFail($id);
        $client->notify(new AccountDeletedNotification(
            'Your account was deleted by administrator decision.'
        ));
        $client->delete();
        return back()->with('success', 'Client deleted.');
    }

    public function produits()
    {
        $produits = Produit::with('boutique', 'categorie')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.produits', ['produits' => $produits]);
    }

    public function approveProduit($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->update(['status' => 'approved']);
        if ($produit->boutique) {
            $produit->boutique->notify(new ProductApprovedNotification($produit));
        }
        return back()->with('success', 'Item approved.');
    }

    public function rejectProduit($id)
{
    $produit = Produit::findOrFail($id);
    if ($produit->boutique) {
        $produit->boutique->notify(new ProductRejectedNotification(
            $produit,
            'Your item did not meet our quality guidelines or platform policies.'
        ));
    }
    
    // Delete image from storage
    if ($produit->image) {
        Storage::disk('public')->delete($produit->image);
    }
    
    // Delete from database
    $produit->delete();
    
    return back()->with('success', 'Item rejected and removed.');
}

public function deleteProduit($id)
{
    $produit = Produit::findOrFail($id);

    if ($produit->boutique) {
        $produit->boutique->notify(new ProductDeletedNotification(
            $produit,
            'Your item was removed for violating platform policies or quality standards.'
        ));
    }
    
    // Delete image from storage
    if ($produit->image) {
        Storage::disk('public')->delete($produit->image);
    }
    
    // Delete from database
    $produit->delete();
    
    return back()->with('success', 'Item deleted successfully.');
}

    public function categories()
    {
        $categories = Categorie::all();
        return view('admin.categories', compact('categories'));
    }

    public function addCategorie(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image',
        ]);

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('categories', $imageName, 'public');

        Categorie::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'image' => $path,
        ]);

        return back()->with('success', 'Category created.');
    }

    public function updateCategorie(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);

        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $data = [
            'nom' => $request->nom,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('categories', $imageName, 'public');
            $data['image'] = $path;
        }

        $categorie->update($data);

        return back()->with('success', 'Category updated.');
    }

    public function deleteCategorie($id)
{
    $categorie = Categorie::findOrFail($id);
    
    // Delete all products in this category
    $produits = Produit::where('id_cat', $id)->get();
    foreach ($produits as $produit) {
        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }
        $produit->delete();
    }
    
    // Delete category image
    if ($categorie->image) {
        Storage::disk('public')->delete($categorie->image);
    }
    
    $categorie->delete();
    
    return back()->with('success', 'Category and all its products deleted.');
}

    public function statistics()
    {
        $stats = SiteStatistics::first();
        if (!$stats) {
            $stats = SiteStatistics::create([
                'activity_percentage' => 10,
                'total_orders' => 0,
                'total_revenue' => 0,
            ]);
        }

        $total_clients = User::where('role', 'client')->where('status', 'active')->count();
        $total_boutiques = User::where('role', 'boutique')->where('status', 'active')->count();
        $approved_items = Produit::where('status', 'approved')->count();
        $total_categories = Categorie::count();
        $total_delivery = DeliveryCompany::where('is_active','1')->count();

        $confirmed_orders = Commande::where('status', 'confirmed')->count();
        $total_sales = Commande::where('status', 'confirmed')->sum('total_price');
        $site_fee_percent = $stats->activity_percentage;
        $total_revenue = $total_sales * ($site_fee_percent / 100);

        return view('admin.statistics', compact(
            'total_clients',
            'total_boutiques',
            'total_delivery',
            'approved_items',
            'total_categories',
            'confirmed_orders',
            'total_sales',
            'total_revenue',
            'site_fee_percent',
            'stats'
        ));
    }

    public function updateActivity(Request $request)
    {
        $request->validate([
            'activity_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $stats = SiteStatistics::first();
        if (!$stats) {
            $stats = SiteStatistics::create([
                'activity_percentage' => $request->activity_percentage,
                'total_orders' => 0,
                'total_revenue' => 0,
            ]);
        } else {
            $stats->update([
                'activity_percentage' => $request->activity_percentage,
            ]);
        }

        return back()->with('success', 'Site fee percentage updated successfully.');
    }
    public function categoryItems($id_cat)
{
    $category = Categorie::findOrFail($id_cat);

    // Load ALL products for this category (admin sees everything)
    $produits = Produit::where('id_cat', $id_cat)
        ->with(['images', 'boutique', 'categorie'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('categories.items', compact('category', 'produits'));
}
    // ═══════════════════════════════════════════════════════════════
    // DELIVERY COMPANIES
    // ═══════════════════════════════════════════════════════════════

    public function deliveryCompanies()
    {
        $companies = \App\Models\DeliveryCompany::latest()->paginate(20);
        return view('admin.delivery_companies', compact('companies'));
    }

    public function createDeliveryCompany()
    {
        return view('admin.delivery_companies_create');
    }

    public function storeDeliveryCompany(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:delivery_companies',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'logo'     => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('delivery_logos', 'public');
        }
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        \App\Models\DeliveryCompany::create($validated);

        return redirect()->route('admin.delivery_companies')
            ->with('success', 'Delivery company created successfully.');
    }

    public function editDeliveryCompany($id)
    {
        $company = \App\Models\DeliveryCompany::findOrFail($id);
        return view('admin.delivery_companies_edit', compact('company'));
    }

    public function updateDeliveryCompany(Request $request, $id)
    {
        $company = \App\Models\DeliveryCompany::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:delivery_companies,email,' . $company->id,
            'password' => 'nullable|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'logo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('delivery_logos', 'public');
        }
        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['is_active'] = $request->boolean('is_active', false);

        $company->update($validated);

        return redirect()->route('admin.delivery_companies')
            ->with('success', 'Delivery company updated successfully.');
    }

    public function deleteDeliveryCompany($id)
    {
        $company = \App\Models\DeliveryCompany::findOrFail($id);
        if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
            \Storage::disk('public')->delete($company->logo);
        }
       Commande::where('delivery_company_id', $id)
        ->whereIn('status', ['confirmed', 'pending'])
        ->update([
            'delivery_company_id' => null,
            'delivery_status' => 'pending',
        ]);
        $company->delete();
        return redirect()->route('admin.delivery_companies')
            ->with('success', 'Delivery company deleted successfully.');
    }
}