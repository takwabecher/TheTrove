<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Favoris;
use App\Models\Panier;
use App\Models\Commande;
use App\Models\categorie;
use App\Models\User;
use App\Models\LigneCommande; 
use App\Models\Review;
use App\Models\Rating;
use App\Models\DeliveryCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderConfirmedNotification;
use Illuminate\Support\Facades\Storage;
use App\Models\LoginHistory;

class ClientController extends Controller
{
    public function dashboard()
    {
        // Redirect to browse instead
        return redirect()->route('client.browse');
    }

    public function browse(Request $request)
    {
        $query = Produit::with(['images', 'categorie', 'boutique', 'reviews.user', 'ratings'])
                    ->where('status', 'approved')
                    ->where('stock', '>', 0);

        // Search by product name
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('nom', 'like', "%{$searchTerm}%");
        }

        // Filter by category
        if ($request->filled('categorie')) {
            $categories = $request->input('categorie');
            if (!is_array($categories)) {
                $categories = [$categories];
            }
            $query->whereIn('id_cat', $categories);
        }

        // Filter by boutique
        if ($request->filled('boutique')) {
            $boutiques = $request->input('boutique');
            if (!is_array($boutiques)) {
                $boutiques = [$boutiques];
            }
            $query->whereIn('id_boutique', $boutiques);
        }

        // Filter by price range

        // Paginate: 20 products per page
        $produits = $query->paginate(20);

        $purchasedProductIds = [];
    if (auth()->check()) {
        $purchasedProductIds = LigneCommande::whereHas('commande', function($q) {
            $q->where('id_client', auth()->id())
              ->where('status', 'confirmed');
        })->pluck('id_product')->toArray();
    }



        // Get filter options
        $categories = categorie::all();
        $vaults = User::where('role', 'boutique')
                      ->where('status', 'active')
                      ->get();

        $user = auth()->user();
        $favoris_products = $user->favoris->pluck('id_product')->toArray();
        $favoris_count = $user->favoris->count();
        $panier_count = $user->panier->count();
        $commandes_count = $user->commandes->count();

        $productsData = $produits->mapWithKeys(function($produit) {
    return [$produit->id_product => [
        'id_product' => $produit->id_product,
        'nom' => $produit->nom,
        'prix' => $produit->prix,
        'stock' => $produit->stock,
        'description' => $produit->description,
        'categorie' => $produit->categorie ? ['nom' => $produit->categorie->nom] : null,
        'boutique' => $produit->boutique ? ['name' => $produit->boutique->name] : null,
        'images' => $produit->images->map(function($img) {
            return ['path' => $img->path];
        })->values(),
    ]];
});

        return view('client.browse', compact(
            'produits', 'categories', 'vaults',
            'favoris_products', 'favoris_count',
            'panier_count', 'commandes_count','purchasedProductIds', 'productsData' 
        ));
    }

    public function boutique()
    {
        $boutiques = \App\Models\User::where('role', 'boutique')
            ->where('status', 'active')
            ->withCount(['produits' => function($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        $total_products = \App\Models\Produit::where('status', 'approved')
            ->whereHas('boutique', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        return view('client.boutique', [
            'boutiques' => $boutiques,
            'total_products' => $total_products,
        ]);
    }

    public function boutiqueDetail($id)
    {
        $boutique = \App\Models\User::where('role', 'boutique')
            ->where('status', 'active')
            ->where('id', $id)
            ->firstOrFail();

        $produits = \App\Models\Produit::where('id_boutique', $id)
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->with(['images','categorie'])
            ->get();

        $favoris_products = \DB::table('favoris')
            ->where('id_client', auth()->id())
            ->pluck('id_product')
            ->toArray();

        return view('client.boutique-detail', [
            'boutique' => $boutique,
            'produits' => $produits,
            'favoris_products' => $favoris_products,
        ]);
    }

    public function categories()
    {
        $categories = \App\Models\Categorie::withCount(['produits' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        $totalProducts = \App\Models\Produit::where('status', 'approved')->count();

        return view('client.categories', compact('categories', 'totalProducts'));
    }

    public function categoryProducts(int $id)
    {
        $category = \App\Models\Categorie::findOrFail($id);

        $products = \App\Models\Produit::where('id_cat', $id)
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->with(['images', 'boutique'])
            ->paginate(12);

        $favoris_products = \DB::table('favoris')
            ->where('id_client', auth()->id())
            ->pluck('id_product')
            ->toArray();

        return view('client.category-products', compact('category', 'products', 'favoris_products'));
    }

    public function productDetail($id)
    {
        $product = Produit::with(['images', 'categorie', 'boutique', 'reviews.user'])
                          ->where('status', 'approved')
                          ->findOrFail($id);

        $reviews = $product->reviews()->with('user')->get();
        $average_rating = $product->ratings()->avg('rating') ?? 0;
        $total_ratings = $product->ratings()->count();

        // Check if user has purchased this product
        $user_has_purchased = LigneCommande::where('id_product', $id)
            ->whereHas('commande', function($q) {
                $q->where('id_client', auth()->id())
                  ->where('status', 'confirmed');
            })
            ->exists();

        // Get user's review if exists
        $user_review = $product->reviews()
                               ->where('id_client', auth()->id())
                               ->first();

        $user_rating = $product->ratings()
                               ->where('id_client', auth()->id())
                               ->first();

        $is_favorited = Favoris::where('id_client', auth()->id())
                               ->where('id_product', $id)
                               ->exists();

        return view('client.product-detail', compact(
            'product', 'reviews', 'average_rating', 'total_ratings',
            'user_has_purchased', 'user_review', 'user_rating', 'is_favorited'
        ));
    }

    public function addReview(Request $request, $product_id)
    {
        $request->validate([
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();

        // Check if user has purchased this product
        $hasPurchased = LigneCommande::where('id_product', $product_id)
            ->whereHas('commande', function($q) use ($user) {
                $q->where('id_client', $user->id)
                  ->where('status', 'confirmed');
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased.');
        }

        // Update or create review
        Review::updateOrCreate(
            [
                'id_product' => $product_id,
                'id_client' => $user->id,
            ],
            [
                'comment' => $request->comment,
                'created_at' => now(),
            ]
        );

        return back()->with('success', 'Review added successfully!');
    }

    public function addRating(Request $request, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        // Check if user has purchased this product
        $hasPurchased = LigneCommande::where('id_product', $product_id)
            ->whereHas('commande', function($q) use ($user) {
                $q->where('id_client', $user->id)
                  ->where('status', 'confirmed');
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You can only rate products you have purchased.');
        }

        // Update or create rating
        Rating::updateOrCreate(
            [
                'id_product' => $product_id,
                'id_client' => $user->id,
            ],
            [
                'rating' => $request->rating,
                'created_at' => now(),
            ]
        );

        return back()->with('success', 'Rating saved!');
    }

    public function aiImageSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        try {
            $image = $request->file('image');
            $imagePath = $image->store('temp', 'local');

            // Call your AI service to get image embeddings
            // This is a placeholder - integrate with your chosen AI service
            // (e.g., Claude Vision, OpenAI CLIP, TensorFlow, etc.)
            
            $similarities = $this->findSimilarProducts($imagePath);

            Storage::delete($imagePath);

            return response()->json([
                'products' => $similarities,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function findSimilarProducts($imagePath)
    {
        // Placeholder implementation
        // In production, use a proper AI vision service
        
        // For now, return random products with similarity score
        $products = Produit::where('status', 'approved')
                           ->where('stock', '>', 0)
                           ->limit(12)
                           ->get();

        return $products->map(function($product) {
            return [
                'id_product' => $product->id_product,
                'nom' => $product->nom,
                'prix' => $product->prix,
                'similarity' => rand(70, 100) / 100, // Placeholder
            ];
        });
    }
//
    public function favoris()
{
    $user = Auth::user();
    $favoris_items = Favoris::where('id_client', $user->id)
        ->with(['produit.images', 'produit.categorie', 'produit.boutique', 'produit.reviews.user', 'produit.ratings'])
        ->get();

    $purchasedProductIds = LigneCommande::whereHas('commande', function($q) use ($user) {
        $q->where('id_client', $user->id)
          ->where('status', 'confirmed');
    })->pluck('id_product')->toArray();

    return view('client.favoris', [
        'favoris_items' => $favoris_items,
        'purchasedProductIds' => $purchasedProductIds,
    ]);
}
   public function addToFavoris($product_id)
{
    $user = Auth::user();
    Favoris::firstOrCreate([
        'id_client' => $user->id,
        'id_product' => $product_id,
    ]);

    return back()->with('success', 'Added to wishlist.');
}
//
public function removeFromFavoris($product_id)
{
    $user = Auth::user();
    Favoris::where('id_client', $user->id)
            ->where('id_product', $product_id)
            ->delete();

    return back()->with('success', 'Removed from wishlist.');
}

public function addToPanier(Request $request, $product_id)
{
    $request->validate([
        'quantity' => 'nullable|integer|min:1',
    ]);

    $user = Auth::user();
    $quantity = $request->quantity ?? 1;

    $product = Produit::findOrFail($product_id);

    if (!$product->hasEnoughStock($quantity)) {
        return back()->with('error', 'Not enough stock. Only ' . $product->stock . ' available.');
    }

    $panier_item = Panier::where('id_client', $user->id)
                          ->where('id_product', $product_id)
                          ->first();

    if ($panier_item) {
        $newQuantity = $panier_item->quantity + $quantity;

        if (!$product->hasEnoughStock($newQuantity)) {
            return back()->with('error', 'Not enough stock. Only ' . $product->stock . ' available.');
        }

        $panier_item->update(['quantity' => $newQuantity]);
    } else {
        Panier::create([
            'id_client' => $user->id,
            'id_product' => $product_id,
            'quantity' => $quantity,
            'dateCreation' => now(),
        ]);
    }

    return back()->with('success', 'Added to basket.');
}
//


    public function toggleFavoris($product_id)
    {
        $user = Auth::user();
        $exists = Favoris::where('id_client', $user->id)
                         ->where('id_product', $product_id)
                         ->exists();

        if ($exists) {
            Favoris::where('id_client', $user->id)
                   ->where('id_product', $product_id)
                   ->delete();
            $isFavorited = false;
        } else {
            Favoris::create([
                'id_client' => $user->id,
                'id_product' => $product_id,
            ]);
            $isFavorited = true;
        }

        return response()->json([
            'isFavorited' => $isFavorited,
            'count' => $user->favoris->count(),
        ]);
    }



    public function updatePanierQuantity(Request $request, $product_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $panier_item = Panier::where('id_client', $user->id)
            ->where('id_product', $product_id)
            ->first();

        if (!$panier_item) {
            return back()->with('error', 'Item not found in basket.');
        }

        $product = Produit::findOrFail($product_id);

        $availableStock = $product->stock + $panier_item->quantity;

        if ($request->quantity > $availableStock) {
            return back()->with('error', 'Not enough stock. Only ' . $product->stock . ' available.');
        }

        $stockDifference = $panier_item->quantity - $request->quantity;
        $product->stock += $stockDifference;
        $product->save();

        $panier_item->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Quantity updated.');
    }

    public function removeFromPanier($product_id)
    {
        $user = Auth::user();
        Panier::where('id_client', $user->id)
               ->where('id_product', $product_id)
               ->delete();

        return back()->with('success', 'Produit supprimé du panier!');
    }

public function panier()
{
    $user = Auth::user();
    $panier_items = Panier::where('id_client', $user->id)
        ->with(['produit.boutique', 'produit.images'])
        ->get();

    $grand_total = 0;
    $shop_count = $panier_items->pluck('produit.id_boutique')->unique()->count();

    foreach ($panier_items as $item) {
        $grand_total += $item->produit->prix * $item->quantity;
    }

    $delivery_companies = DeliveryCompany::where('is_active', true)->get();

    return view('client.panier', compact(
        'panier_items', 'grand_total', 'shop_count',
        'delivery_companies', 'user'
    ));
}
    public function checkout(Request $request)
{
    $request->validate([
        'delivery_company_id' => 'required|exists:delivery_companies,id',
        'shipping_address'    => 'nullable|string|max:1000',
        'phone'               => 'nullable|string|max:20',
    ]);

    $user = Auth::user();
    $panier_items = Panier::where('id_client', $user->id)->with('produit')->get();

    if ($panier_items->count() === 0) {
        return back()->with('error', 'Votre panier est vide!');
    }

    foreach ($panier_items as $item) {
        if (!$item->produit->hasEnoughStock($item->quantity)) {
            return back()->with('error', 'Not enough stock for ' . $item->produit->nom);
        }
    }

    $delivery_company = DeliveryCompany::findOrFail($request->delivery_company_id);

    // Calculate grand total for ALL items (not per boutique)
    $grand_total = $panier_items->sum(fn($item) => $item->produit->prix * $item->quantity);

    DB::beginTransaction();
    try {
        // 1. Create ONE order for the client
        $commande = Commande::create([
            'id_client'           => $user->id,
            'total_price'         => $grand_total,
            'status'              => 'pending',
            'delivery_status'     => 'pending',
            'date_cmd'            => now(),
            'delivery_company_id' => $delivery_company->id,
            'shipping_address'    => $request->shipping_address ?? $user->address,
            'phone'               => $request->phone ?? $user->phone,
        ]);

        // 2. Create ligne_commandes for ALL items & deduct stock
        foreach ($panier_items as $item) {
            LigneCommande::create([
                'id_cmd'      => $commande->id_cmd,
                'id_product'  => $item->id_product,
                'quantity'    => $item->quantity,
                'price'       => $item->produit->prix, // snapshot price
            ]);

            // Deduct stock
            $item->produit->decreaseStock($item->quantity);
            // If decreaseStock() doesn't exist, use:
            // $item->produit->stock -= $item->quantity;
            // $item->produit->save();
        }

        Panier::where('id_client', $user->id)->delete();
        DB::commit();

        return redirect()->route('client.commandes')
            ->with('success', 'Commande créée avec succès!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong. Please try again.');
    }
}

    public function commandes()
    {
        $user = Auth::user();
        $commandes = Commande::where('id_client', $user->id)
            ->with(['items.produit.boutique','deliveryCompany'])
            ->get();

        return view('client.commandes', ['commandes' => $commandes]);
    }
  
    public function cancelCommande($order_id)
    {
        $commande = Commande::where('id_cmd', $order_id)
            ->with('items.produit')
            ->first();

        if (!$commande || $commande->id_client !== Auth::id()) {
            return back()->with('error', 'Commande non trouvée.');
        }

        if ($commande->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::beginTransaction();
        try {
            foreach ($commande->items as $item) {
                $item->produit->increaseStock($item->quantity);
            }

            $commande->update(['status' => 'cancelled']);

            DB::commit();
            return back()->with('success', 'Commande annulée! Stock returned.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }

    // ============================================
    // SEARCH METHODS (Text + AI Visual Search)
    // ============================================

    /**
     * Live AJAX text search (navbar dropdown)
     */
    public function ajaxSearch(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        $results = [
            'products' => [],
            'boutiques' => [],
            'categories' => []
        ];

        if (in_array($type, ['all', 'products'])) {
            $results['products'] = Produit::where(function($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->where('status', 'approved')
                ->where('stock', '>', 0)
                ->with(['boutique', 'images'])
                ->limit(5)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id_product,
                    'name' => $p->nom,
                    'price' => $p->prix,
                    'image' => $p->images->first() 
                        ? asset('storage/' . $p->images->first()->path) 
                        : asset('images/placeholder.png'),
                    'boutique_name' => $p->boutique?->nom
                ]);
        }

        if (in_array($type, ['all', 'boutiques'])) {
            $results['boutiques'] = User::where('role', 'boutique')
                ->where('status', 'active')
                ->where('name', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(fn($b) => [
                    'id' => $b->id,
                    'name' => $b->name,
                    'image' => $b->logo ? asset('storage/' . $b->logo) : asset('images/placeholder.png'),
                    'url' => route('client.boutiqueDetail', $b->id)
                ]
            );
        }

        if (in_array($type, ['all', 'categories'])) {
        $results['categories'] = categorie::where('nom', 'like', "%{$query}%")
        ->limit(5)
        ->get()
        ->map(fn($c) => [
            'id' => $c->id_cat,
            'name' => $c->nom,
            'image' => $c->image ? asset('storage/' . $c->image) : asset('images/placeholder.png'),
            'product_count' => $c->produits()->where('status', 'approved')->count(),
            'url' => route('client.category.products', $c->id_cat)
        ]);
}

        return response()->json($results);
    }

    /**
     * AI Visual Search (upload image -> find similar products like SHEIN)
     */
    public function aiSearch(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:5120',
    ]);

    try {
        $image = $request->file('image');
        $imagePath = $image->store('temp/ai-search', 'local');

        $uploadedFeatures = $this->extractImageFeatures($imagePath);

        \Log::info('AI Search uploaded features', $uploadedFeatures);

        $matches = Produit::where('status', 'approved')
            ->where('stock', '>', 0)
            ->with(['boutique', 'images'])
            ->get()
            ->map(function($product) use ($uploadedFeatures) {
                $similarity = $this->calculateSimilarity($uploadedFeatures, $product);
                $firstImg = $product->images->first();

                \Log::info("AI Search product #{$product->id_product} similarity: {$similarity}");

                return [
                    'id' => $product->id_product,
                    'name' => $product->nom,
                    'price' => $product->prix,
                    'image' => $firstImg ? asset('storage/' . $firstImg->path) : asset('images/placeholder.png'),
                    'boutique_name' => $product->boutique?->name,
                    'similarity' => round($similarity, 2)
                ];
            })
            ->filter(fn($m) => $m['similarity'] > 0.15)
            ->sortByDesc('similarity')
            ->take(10)
            ->values();

        Storage::delete($imagePath);

        return response()->json(['matches' => $matches]);

    } catch (\Exception $e) {
        \Log::error('AI Search error: ' . $e->getMessage());
        return response()->json(['error' => 'AI search failed: ' . $e->getMessage()], 500);
    }
}
    /**
     * Full search results page
     */
    public function searchResults(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        $products = collect();
        $boutiques = collect();
        $categories = collect();

        if (in_array($type, ['all', 'products'])) {
            $products = Produit::where(function($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->where('status', 'approved')
                ->where('stock', '>', 0)
                ->with(['images', 'boutique', 'categorie'])
                ->paginate(20, ['*'], 'products_page');
        }

        if (in_array($type, ['all', 'boutiques'])) {
            $boutiques = User::where('role', 'boutique')
                ->where('status', 'active')
                ->where('name', 'like', "%{$query}%")
                ->withCount(['produits' => fn($q) => $q->where('status', 'approved')])
                ->paginate(12, ['*'], 'boutiques_page');
        }

        if (in_array($type, ['all', 'categories'])) {
            $categories = categorie::where('nom', 'like', "%{$query}%")
                ->withCount(['produits' => fn($q) => $q->where('status', 'approved')])
                ->paginate(12, ['*'], 'categories_page');
        }

        return view('client.search-results', compact('query', 'type', 'products', 'boutiques', 'categories'));
    }

    // ============================================
    // AI HELPERS (Feature Extraction + Similarity)
    // ============================================

    /**
     * Extract visual features from uploaded image using color histogram
     * FREE method - no external API needed
     */

    private function extractImageFeatures($imagePath)
{
    // FIX: normalize path separators for Windows
    $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, storage_path('app/' . $imagePath));

    if (!file_exists($fullPath)) {
        \Log::warning("AI Search file not found: {$fullPath}");
        return ['colors' => [], 'brightness' => 0, 'aspect_ratio' => 1, 'has_gd' => false, 'file_size' => 0, 'width' => 0, 'height' => 0];
    }

    $imageData = @getimagesize($fullPath);

    if ($imageData === false) {
        \Log::warning("AI Search getimagesize failed for: {$fullPath}");
        return ['colors' => [], 'brightness' => 0, 'aspect_ratio' => 1, 'has_gd' => false, 'file_size' => 0, 'width' => 0, 'height' => 0];
    }

    $width = $imageData[0];
    $height = $imageData[1];
    $fileSize = @filesize($fullPath) ?: 0;

    if (!function_exists('imagecreatefromjpeg')) {
        return [
            'colors' => [],
            'brightness' => 0,
            'aspect_ratio' => $width / max($height, 1),
            'has_gd' => false,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
        ];
    }

    $img = match($imageData[2] ?? 0) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($fullPath),
        IMAGETYPE_PNG => imagecreatefrompng($fullPath),
        IMAGETYPE_WEBP => imagecreatefromwebp($fullPath),
        default => null,
    };

    if (!$img) {
        return ['colors' => [], 'brightness' => 0, 'aspect_ratio' => 1, 'has_gd' => true, 'file_size' => $fileSize, 'width' => $width, 'height' => $height];
    }

    $imgWidth = imagesx($img);
    $imgHeight = imagesy($img);
    $sampleSize = 50;

    $colorBins = [
        'red' => 0, 'green' => 0, 'blue' => 0,
        'yellow' => 0, 'purple' => 0, 'orange' => 0,
        'black' => 0, 'white' => 0, 'gray' => 0,
    ];
    $totalBrightness = 0;
    $pixelCount = 0;

    for ($x = 0; $x < $imgWidth; $x += $sampleSize) {
        for ($y = 0; $y < $imgHeight; $y += $sampleSize) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $totalBrightness += ($r + $g + $b) / 3;
            $pixelCount++;

            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $diff = $max - $min;

            if ($diff < 30) {
                if (($r + $g + $b) / 3 < 80) $colorBins['black']++;
                elseif (($r + $g + $b) / 3 > 200) $colorBins['white']++;
                else $colorBins['gray']++;
            } else {
                if ($r > 150 && $g < 100 && $b < 100) $colorBins['red']++;
                elseif ($r < 100 && $g > 150 && $b < 100) $colorBins['green']++;
                elseif ($r < 100 && $g < 100 && $b > 150) $colorBins['blue']++;
                elseif ($r > 150 && $g > 150 && $b < 100) $colorBins['yellow']++;
                elseif ($r > 150 && $g < 100 && $b > 150) $colorBins['purple']++;
                elseif ($r > 150 && $g > 100 && $b < 100) $colorBins['orange']++;
            }
        }
    }

    imagedestroy($img);

    $total = array_sum($colorBins) ?: 1;
    foreach ($colorBins as $key => $val) {
        $colorBins[$key] = $val / $total;
    }

    return [
        'colors' => $colorBins,
        'brightness' => $pixelCount > 0 ? $totalBrightness / $pixelCount : 0,
        'aspect_ratio' => $imgWidth / max($imgHeight, 1),
        'has_gd' => true,
        'file_size' => $fileSize,
        'width' => $imgWidth,
        'height' => $imgHeight,
    ];
}

    /**
     * Calculate similarity between uploaded image and product
     */

    private function calculateSimilarity($uploadedFeatures, $product)
{
    $productPath = $product->images->first()?->path;
    if (!$productPath) {
        return $this->fallbackSimilarity($product);
    }

    // FIX: normalize path separators
    $productFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, storage_path('app/public/' . $productPath));
    if (!file_exists($productFullPath)) {
        \Log::warning("AI Search product image not found: {$productFullPath}");
        return $this->fallbackSimilarity($product);
    }

    $productFeatures = $this->extractImageFeatures('public/' . $productPath);

    if (empty($uploadedFeatures['has_gd']) || empty($productFeatures['has_gd'])) {
        return $this->metadataSimilarity($uploadedFeatures, $productFeatures, $product);
    }

    $uploadedColors = $uploadedFeatures['colors'] ?? [];
    $productColors = $productFeatures['colors'] ?? [];

    if (empty($uploadedColors) || empty($productColors)) {
        return $this->fallbackSimilarity($product);
    }

    $dotProduct = 0;
    $uploadedNorm = 0;
    $productNorm = 0;

    foreach ($uploadedColors as $key => $val) {
        $pVal = $productColors[$key] ?? 0;
        $dotProduct += $val * $pVal;
        $uploadedNorm += $val * $val;
        $productNorm += $pVal * $pVal;
    }

    $colorSimilarity = ($uploadedNorm * $productNorm) > 0 
        ? $dotProduct / (sqrt($uploadedNorm) * sqrt($productNorm)) 
        : 0;

    $uploadedBrightness = $uploadedFeatures['brightness'] ?? 128;
    $productBrightness = $productFeatures['brightness'] ?? 128;
    $brightnessDiff = abs($uploadedBrightness - $productBrightness);
    $brightnessSimilarity = max(0, 1 - ($brightnessDiff / 255));

    $aspectDiff = abs(($uploadedFeatures['aspect_ratio'] ?? 1) - ($productFeatures['aspect_ratio'] ?? 1));
    $aspectSimilarity = max(0, 1 - $aspectDiff);

    $similarity = ($colorSimilarity * 0.6) + ($brightnessSimilarity * 0.2) + ($aspectSimilarity * 0.2);

    return min(1, max(0, $similarity));
}
    /**
     * Fallback similarity when image analysis fails
     */
 private function fallbackSimilarity($product)
{
    $score = 0.2;
    if ($product->stock > 0) $score += 0.1;
    return min(0.5, $score);
}

private function metadataSimilarity($uploaded, $product, $productModel)
{
    $aspectDiff = abs(($uploaded['aspect_ratio'] ?? 1) - ($product['aspect_ratio'] ?? 1));
    $aspectScore = max(0, 1 - $aspectDiff);

    $sizeDiff = abs(($uploaded['file_size'] ?? 0) - ($product['file_size'] ?? 0));
    $maxSize = max($uploaded['file_size'] ?? 1, $product['file_size'] ?? 1);
    $sizeScore = $maxSize > 0 ? max(0, 1 - ($sizeDiff / $maxSize)) : 0;

    // EXACT RE-UPLOAD: same dimensions + size within 10%
    if ($aspectDiff < 0.01 && $sizeScore > 0.9) {
        return 0.98;
    }

    // CLOSE MATCH: same dimensions + size within 50%
    if ($aspectDiff < 0.05 && $sizeScore > 0.5) {
        return 0.85;
    }

    $base = ($aspectScore * 0.5) + ($sizeScore * 0.3) + 0.15;

    if ($productModel->stock > 0) $base += 0.05;

    return min(0.95, max(0.2, $base));
}

    protected function authenticated(Request $request, $user)
{
    LoginHistory::create([
        'user_id' => $user->id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->intended($this->redirectPath());
}
public function settings()
{
    $user = Auth::user();
    
    $commandes = Commande::where('id_client', $user->id)
    ->with(['items.produit.boutique'])
    ->latest('date_cmd')
    ->get();

    $loginHistory = \App\Models\LoginHistory::where('user_id', $user->id)
        ->latest()
        ->take(20)
        ->get();

    $favoris_count = $user->favoris->count();
    $panier_count = $user->panier->count();
    $commandes_count = $commandes->count();
    $delivered_count = $commandes->where('status', 'confirmed')->count();

    return view('client.setting', compact(
        'user', 'commandes', 'loginHistory',
        'favoris_count', 'panier_count', 'commandes_count', 'delivered_count'
    ));
}

public function updateProfile(Request $request)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|unique:users,email,' . Auth::id(),
        'phone'   => 'nullable|string|max:20',
        'address' => 'nullable|string|max:1000',
        'avatar'  => 'nullable|image|max:2048',
    ]);

    $user = Auth::user();
    $data = $request->only('name', 'email', 'phone', 'address');

    if ($request->hasFile('avatar')) {
        $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    $user->update($data);

    return back()->with('success', 'Profile updated successfully.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|current_password',
        'password' => 'required|string|min:8|confirmed',
    ]);

    Auth::user()->update([
        'password' => bcrypt($request->password)
    ]);

    return back()->with('success', 'Password changed successfully.');
}
}