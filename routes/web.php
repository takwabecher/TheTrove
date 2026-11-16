<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeliveryAuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\User;
use App\Services\GeocodingService;
use App\Models\Commande;
use App\Notifications\AccountDeletedNotification;
use App\Notifications\AccountRejectedNotification;
use App\Notifications\AccountReactivatedNotification;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\ResetPasswordNotification;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/categories', [PageController::class, 'allCategories'])->name('categories.all');
Route::get('/category/{id}', [PageController::class, 'categoryProducts'])->name('main.category.products');
Route::get('/category/{id}/items', [PageController::class, 'categoryItems'])->name('category.items');

Route::get('/choice', [PageController::class, 'choice'])->name('choice');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')->name('password.store');

Route::middleware('auth')->group(function () {
    Route::get('/waiting/boutique', [PageController::class, 'waitingBoutique'])->name('waiting.boutique');
    Route::get('/waiting/client', [PageController::class, 'waitingClient'])->name('waiting.client');
    Route::get('/suspended/boutique', [PageController::class, 'suspendedBoutique'])->name('suspended.boutique');
    Route::get('/suspended/client', [PageController::class, 'suspendedClient'])->name('suspended.client');
});

Route::get('/sorry', [PageController::class, 'sorry'])->name('sorry');

Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/clients', [AdminController::class, 'clients'])->name('admin.clients');
    Route::post('/admin/clients/{id}/approve', [AdminController::class, 'approveClient'])->name('admin.clients.approve');
    Route::post('/admin/clients/{id}/decline', [AdminController::class, 'declineClient'])->name('admin.clients.decline');
    Route::post('/admin/clients/{id}/suspend', [AdminController::class, 'suspendClient'])->name('admin.clients.suspend');
    Route::post('/admin/clients/{id}/reactivate', [AdminController::class, 'reactivateClient'])->name('admin.clients.reactivate');
    Route::post('/admin/clients/{id}/delete', [AdminController::class, 'deleteClient'])->name('admin.clients.delete');
});

Route::get('/boutiques', [ClientController::class, 'boutique'])->name('boutique');

Route::middleware(['auth', 'boutique'])->prefix('boutique')->name('boutique.')->group(function () {
    Route::get('/home', [PageController::class, 'home'])->name('home');
    Route::get('/dashboard', [BoutiqueController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories', [BoutiqueController::class, 'categories'])->name('categories');
    Route::get('/produits', [BoutiqueController::class, 'produits'])->name('produits');
    Route::get('/commandes', [BoutiqueController::class, 'commandes'])->name('commandes');

    Route::delete('/produit/{id_product}/image/{id_image}', [BoutiqueController::class, 'deleteImage'])
        ->name('deleteImage');
    Route::post('/produit/{id_product}/image/{id_image}/primary', [BoutiqueController::class, 'setPrimaryImage'])
        ->name('setPrimaryImage');

    Route::get('/add-product', [BoutiqueController::class, 'addProductForm'])->name('addProductForm');
    Route::post('/add-product', [BoutiqueController::class, 'addProduct'])->name('addProduct');
    Route::get('/edit-product/{id}', [BoutiqueController::class, 'editProductForm'])->name('editProductForm');
    Route::post('/update-product/{id}', [BoutiqueController::class, 'updateProduct'])->name('updateProduct');
    Route::post('/delete-product/{id}', [BoutiqueController::class, 'deleteProduct'])->name('deleteProduct');

    Route::get('/category/{id_cat}', [BoutiqueController::class, 'categoryProducts'])->name('categoryProducts');

    Route::get('/settings', [BoutiqueController::class, 'settings'])->name('settings');
    Route::put('/settings', [BoutiqueController::class, 'updateSettings'])->name('settings.update');

    Route::post('/confirm-commande/{id}', [BoutiqueController::class, 'confirmCommande'])->name('confirmCommande');
    Route::post('/decline-commande/{id}', [BoutiqueController::class, 'declineCommande'])->name('declineCommande');

});

Route::get('/boutique/{id}', [ClientController::class, 'boutiqueDetail'])->name('boutiqueDetail');

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/browse', [ClientController::class, 'browse'])->name('browse');
    Route::get('/boutique', [ClientController::class, 'boutique'])->name('boutique');
    Route::get('/boutique/{id}', [ClientController::class, 'boutiqueDetail'])->name('boutiqueDetail');
    Route::get('/categories', [ClientController::class, 'categories'])->name('categories');
    Route::get('/category/{id}', [ClientController::class, 'categoryProducts'])->name('category.products');
    Route::get('/panier', [ClientController::class, 'panier'])->name('panier');
    Route::get('/favoris', [ClientController::class, 'favoris'])->name('favoris');
    Route::get('/commandes', [ClientController::class, 'commandes'])->name('commandes');

Route::post('/produit/{id}/review', [ClientController::class, 'addReview'])->name('product.addReview');
    Route::post('/produit/{id}/rating', [ClientController::class, 'addRating'])->name('product.addRating');
    // routes/web.php (or your client route file)
    Route::get('/search', [ClientController::class, 'searchResults'])->name('search');
Route::get('/search/ajax', [ClientController::class, 'ajaxSearch'])->name('search.ajax');
Route::post('/search/ai', [ClientController::class, 'aiSearch'])->name('search.ai');

    Route::post('/add-to-panier/{id}', [ClientController::class, 'addToPanier'])->name('addToPanier');
    Route::post('/remove-from-panier/{id}', [ClientController::class, 'removeFromPanier'])->name('removeFromPanier');
    Route::post('/update-panier-quantity/{id}', [ClientController::class, 'updatePanierQuantity'])->name('updatePanierQuantity');
    Route::post('/add-to-favoris/{id}', [ClientController::class, 'addToFavoris'])->name('addToFavoris');
    Route::post('/remove-from-favoris/{id}', [ClientController::class, 'removeFromFavoris'])->name('removeFromFavoris');
    Route::post('/checkout', [ClientController::class, 'checkout'])->name('checkout');
    Route::post('/confirm-commande/{id}', [ClientController::class, 'confirmCommande'])->name('confirmCommande');
    Route::post('/commandes/{id}/cancel', [ClientController::class, 'cancelCommande'])->name('cancelCommande');

    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');
Route::post('/settings/profile', [ClientController::class, 'updateProfile'])->name('settings.profile');
Route::post('/settings/password', [ClientController::class, 'updatePassword'])->name('settings.password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/boutiques', [AdminController::class, 'boutiques'])->name('boutiques');
    Route::get('/produits', [AdminController::class, 'produits'])->name('produits');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    Route::post('/approve-boutique/{id}', [AdminController::class, 'approveBoutique'])->name('approveBoutique');
    Route::post('/reject-boutique/{id}', [AdminController::class, 'rejectBoutique'])->name('rejectBoutique');
    Route::post('/suspend-boutique/{id}', [AdminController::class, 'suspendBoutique'])->name('suspendBoutique');
    Route::post('/activate-boutique/{id}', [AdminController::class, 'activateBoutique'])->name('activateBoutique');
    Route::post('/delete-boutique/{id}', [AdminController::class, 'deleteBoutique'])->name('deleteBoutique');

    Route::post('/approve-produit/{id}', [AdminController::class, 'approveProduit'])->name('approveProduit');
    Route::post('/reject-produit/{id}', [AdminController::class, 'rejectProduit'])->name('rejectProduit');
    Route::post('/delete-produit/{id}', [AdminController::class, 'deleteProduit'])->name('deleteProduit');

    Route::post('/add-categorie', [AdminController::class, 'addCategorie'])->name('addCategorie');
    Route::post('/update-categorie/{id}', [AdminController::class, 'updateCategorie'])->name('updateCategorie');
    Route::post('/delete-categorie/{id}', [AdminController::class, 'deleteCategorie'])->name('deleteCategorie');

    Route::get('/category/{id_cat}/items', [AdminController::class, 'categoryItems'])
    ->name('category.items');

    Route::post('/update-activity', [AdminController::class, 'updateActivity'])->name('updateActivity');

    Route::get('/delivery-companies', [AdminController::class, 'deliveryCompanies'])->name('delivery_companies');
Route::get('/delivery-companies/create', [AdminController::class, 'createDeliveryCompany'])->name('delivery_companies.create');
Route::post('/delivery-companies', [AdminController::class, 'storeDeliveryCompany'])->name('delivery_companies.store');
Route::get('/delivery-companies/{id}/edit', [AdminController::class, 'editDeliveryCompany'])->name('delivery_companies.edit');
Route::patch('/delivery-companies/{id}', [AdminController::class, 'updateDeliveryCompany'])->name('delivery_companies.update');
Route::delete('/delivery-companies/{id}', [AdminController::class, 'deleteDeliveryCompany'])->name('delivery_companies.destroy');
});

Route::prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/login', [DeliveryAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [DeliveryAuthController::class, 'login']);
    
    Route::middleware('delivery.auth')->group(function () {
        Route::post('/logout', [DeliveryAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [DeliveryController::class, 'orders'])->name('orders');
        Route::get('/orders/history', [DeliveryController::class, 'orderHistory'])->name('order_history');
        Route::get('/orders/{id}', [DeliveryController::class, 'orderDetail'])->name('order_detail');
        Route::patch('/orders/{id}/status', [DeliveryController::class, 'updateOrderStatus'])->name('orders.status');
        Route::post('/orders/{id}/call', [DeliveryController::class, 'logCall'])->name('orders.call');
        Route::get('/calls', [DeliveryController::class, 'callHistory'])->name('call_history');

        Route::get('/settings', [DeliveryController::class, 'settings'])->name('settings');
    Route::put('/settings', [DeliveryController::class, 'updateSettings'])->name('settings.update');

    Route::post('/logout', [DeliveryController::class, 'logout'])->name('logout');

    });
});

Route::get('/preview-reset-email', function () {
    $user = User::first();
    return (new ResetPasswordNotification('dummy-token'))->toMail($user);
});
Route::get('/test-approve/boutique/{id}', function ($id) {
    $boutique = User::where('role', 'boutique')->findOrFail($id);
    $boutique->notify(new AccountApprovedNotification());
    
    return ' Approval email sent to boutique: ' . $boutique->email;
});
Route::get('/test-approve/client/{id}', function ($id) {
    $client = User::where('role', 'client')->findOrFail($id);
    $client->notify(new AccountApprovedNotification());
    
    return 'Approval email sent to client: ' . $client->email;
});
Route::get('/test-suspend/boutique/{id}', function ($id) {
    $boutique = User::where('role', 'boutique')->findOrFail($id);
    $boutique->notify(new AccountSuspendedNotification(
        'Violation of platform terms and conditions.'
    ));
    
    return 'Suspension email sent to boutique: ' . $boutique->email;
});
Route::get('/test-suspend/client/{id}', function ($id) {
    $client = User::where('role', 'client')->findOrFail($id);
    $client->notify(new AccountSuspendedNotification(
        'Violation of platform terms and conditions.'
    ));
    
    return 'Suspension email sent to client: ' . $client->email;
});
Route::get('/test-reactivate/boutique/{id}', function ($id) {
    $boutique = User::where('role', 'boutique')->findOrFail($id);
    $boutique->notify(new AccountReactivatedNotification());
    
    return 'Reactivation email sent to boutique: ' . $boutique->email;
});
Route::get('/test-reactivate/client/{id}', function ($id) {
    $client = User::where('role', 'client')->findOrFail($id);
    $client->notify(new AccountReactivatedNotification());
    
    return 'Reactivation email sent to client: ' . $client->email;
});
Route::get('/test-reject/boutique/{id}', function ($id) {
    $boutique = User::where('role', 'boutique')->findOrFail($id);
    $email = $boutique->email;
    
    $boutique->notify(new AccountRejectedNotification(
        'Your vault application did not meet our current platform requirements.'
    ));
    
    return 'Rejection email sent to boutique: ' . $email . ' (user not deleted in test)';
});
Route::get('/test-reject/client/{id}', function ($id) {
    $client = User::where('role', 'client')->findOrFail($id);
    $email = $client->email;
    
    $client->notify(new AccountRejectedNotification(
        'Your account registration did not meet our verification requirements.'
    ));
    
    return 'Rejection email sent to client: ' . $email . ' (user not deleted in test)';
});
Route::get('/test-delete/boutique/{id}', function ($id) {
    $boutique = User::where('role', 'boutique')->findOrFail($id);
    $email = $boutique->email;
    
    $boutique->notify(new AccountDeletedNotification(
        'Your vault was permanently deleted by administrator decision.'
    ));
    
    return 'Deletion email sent to boutique: ' . $email . ' (user not deleted in test)';
});
Route::get('/test-delete/client/{id}', function ($id) {
    $client = User::where('role', 'client')->findOrFail($id);
    $email = $client->email;
    
    $client->notify(new AccountDeletedNotification(
        'Your account was permanently deleted by administrator decision.'
    ));
    
    return 'Deletion email sent to client: ' . $email . ' (user not deleted in test)';
});
Route::get('/test-approve-produit/{id}', function ($id) {
    $produit = \App\Models\Produit::with('boutique')->findOrFail($id);
    
    if (!$produit->boutique) {
        return 'No boutique found for this product.';
    }
    
    $produit->boutique->notify(new \App\Notifications\ProductApprovedNotification($produit));
    
    return 'Product approval email sent to: ' . $produit->boutique->email;
});
Route::get('/test-reject-produit/{id}', function ($id) {
    $produit = \App\Models\Produit::with('boutique')->findOrFail($id);
    
    if (!$produit->boutique) {
        return 'No boutique found for this product.';
    }
    
    $produit->boutique->notify(new \App\Notifications\ProductRejectedNotification(
        $produit,
        'Your item did not meet our quality guidelines or platform policies.'
    ));
    
    return 'Product rejection email sent to: ' . $produit->boutique->email;
});
Route::get('/test-delete-produit/{id}', function ($id) {
    $produit = \App\Models\Produit::with('boutique')->findOrFail($id);
    
    if (!$produit->boutique) {
        return 'No boutique found for this product.';
    }
    
    $produit->boutique->notify(new \App\Notifications\ProductDeletedNotification(
        $produit,
        'Your item violated our platform policies.'
    ));
    
    return 'Product deletion email sent to: ' . $produit->boutique->email;
});
Route::get('/test-order-confirm/{order_id}', function ($order_id) {
    $commande = \App\Models\Commande::with(['items.produit.boutique'])->find($order_id);
    
    if (!$commande) {
        return 'Order not found.';
    }
    
    $boutiqueItems = [];
    
    foreach ($commande->items as $ligne) {
        $produit = $ligne->produit;
        if (!$produit) continue;
        
        $boutique = $produit->boutique;
        if (!$boutique) continue;
        
        $boutiqueId = $boutique->id;
        
        if (!isset($boutiqueItems[$boutiqueId])) {
            $boutiqueItems[$boutiqueId] = [
                'boutique' => $boutique,
                'items' => collect(),
            ];
        }
        
        $boutiqueItems[$boutiqueId]['items']->push([
            'nom' => $produit->nom,
            'quantite' => $ligne->quantity,
            'prix' => $ligne->price,
            'total' => $ligne->quantity * $ligne->price,
        ]);
    }
    
    foreach ($boutiqueItems as $data) {
        $data['boutique']->notify(new \App\Notifications\OrderConfirmedNotification($commande, $data['items']));
    }
    
    return 'Order confirmation emails sent to ' . count($boutiqueItems) . ' boutique(s).';
});
// Test: Boutique CONFIRMS order → client gets email (preview only)
Route::get('/test-boutique-confirm/{order_id}', function ($order_id) {
    $commande = \App\Models\Commande::with(['items.produit', 'client'])->find($order_id);
    
    if (!$commande || !$commande->client) {
        return 'Order or client not found.';
    }
    
    $fakeBoutique = new \App\Models\User([
        'name' => 'Golden Vault Store',
        'email' => 'boutique@example.com',
        'role' => 'boutique',
    ]);
    
    return (new \App\Notifications\BoutiqueOrderConfirmedNotification(
        $commande,
        $fakeBoutique
    ))->toMail($commande->client);
});

// Test: Boutique DECLINES order → client gets email (preview only)
Route::get('/test-boutique-decline/{order_id}', function ($order_id) {
    $commande = \App\Models\Commande::with(['items.produit', 'client'])->find($order_id);
    
    if (!$commande || !$commande->client) {
        return 'Order or client not found.';
    }
    
    $fakeBoutique = new \App\Models\User([
        'name' => 'Golden Vault Store',
        'email' => 'boutique@example.com',
        'role' => 'boutique',
    ]);
    
    return (new \App\Notifications\BoutiqueOrderDeclinedNotification(
        $commande,
        $fakeBoutique
    ))->toMail($commande->client);
});

