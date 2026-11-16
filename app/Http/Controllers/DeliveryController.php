<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\DeliveryCall;
use App\Models\CommandeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────────
    public function dashboard()
    {
        $company = Auth::guard('delivery')->user();
        $today = now()->startOfDay();
        $weekAgo = now()->subDays(6)->startOfDay();

        // ── Core Stats ──
        $stats = [
            'active_orders'       => $company->activeCommandes()->count(),
            'pending_pickup'      => $company->commandes()->where('delivery_status', 'pending')->count(),
            'in_transit'          => $company->commandes()->where('delivery_status', 'in_transit')->count(),
            'completed_today'     => $company->completedCommandes()->whereDate('delivered_at', $today)->count(),
            'total_completed'     => $company->completedCommandes()->count(),
            'total_calls'         => $company->calls()->count(),
            'cancelled_this_week' => $company->commandes()->where('status', 'cancelled')->whereDate('updated_at', '>=', $weekAgo)->count(),
        ];

        // ── Performance Metrics ──
        $avgDeliveryTime = $company->completedCommandes()
            ->whereNotNull('picked_up_at')
            ->whereNotNull('delivered_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, picked_up_at, delivered_at)) as avg_minutes')
            ->value('avg_minutes') ?? 0;

        $onTimeRate = $this->calculateOnTimeRate($company);
        $successRate = $this->calculateSuccessRate($company);

        $performance = [
            'avg_delivery_minutes'   => round($avgDeliveryTime),
            'avg_delivery_formatted' => $this->formatDuration($avgDeliveryTime),
            'on_time_rate'           => $onTimeRate,
            'success_rate'           => $successRate,
        ];

        // ── Weekly Chart Data ──
        $weeklyData = collect(range(0, 6))->map(function ($daysBack) use ($company) {
            $date = now()->subDays($daysBack)->startOfDay();
            return [
                'label'     => $date->format('D'),
                'full_date' => $date->format('M d'),
                'delivered' => $company->completedCommandes()->whereDate('delivered_at', $date)->count(),
                'picked_up' => $company->commandes()->where('delivery_status', 'picked_up')->whereDate('picked_up_at', $date)->count(),
                'calls'     => $company->calls()->whereDate('called_at', $date)->count(),
            ];
        })->reverse()->values();

        // ── Priority Alerts ──
        $priorityOrders = $company->commandes()
            ->with(['client', 'items.produit.boutique'])
            ->whereIn('delivery_status', ['pending', 'in_transit'])
            ->where(function ($q) {
                $q->where(function ($sq) {
                    $sq->where('delivery_status', 'pending')
                       ->where('created_at', '<', now()->subHours(2));
                })->orWhere(function ($sq) {
                    $sq->where('delivery_status', 'in_transit')
                       ->where('picked_up_at', '<', now()->subHours(3));
                });
            })
            ->latest()
            ->take(5)
            ->get();

        // ── Today's Route Summary ──
        $todaysOrders = $company->commandes()
            ->with(['client', 'items.produit.boutique'])
            ->whereDate('date_cmd', $today)
            ->orWhere(function ($q) use ($today) {
                $q->whereDate('picked_up_at', $today)
                  ->orWhereDate('delivered_at', $today);
            })
            ->latest('date_cmd')
            ->take(8)
            ->get();

        // ── Recent Activity Feed ──
        $recentActivity = CommandeHistory::where('delivery_company_id', $company->id)
            ->with(['commande.client'])
            ->latest('acted_at')
            ->take(8)
            ->get();

        // ── Recent Orders (for table) ──
        $recentCommandes = $company->commandes()
            ->with(['client', 'items.produit.boutique'])
            ->latest('date_cmd')
            ->take(6)
            ->get();

        
        $mapOrders = Commande::where('delivery_company_id', $company->id)
    ->whereNotNull('lat')
    ->whereNotNull('lng')
    ->whereIn('delivery_status', ['pending', 'picked_up', 'in_transit'])
    ->with('client')
    ->get()
    ->map(fn ($o) => [
        'id'        => $o->formatted_id,
        'lat'       => $o->lat,
        'lng'       => $o->lng,
        'status'    => $o->delivery_status,
        'client'    => $o->client?->name ?? 'N/A',
        'address'   => $o->shipping_address,
        'url'       => route('delivery.order_detail', $o->id_cmd),
    ]);

        return view('delivery.dashboard', compact(
            'stats',
            'performance',
            'weeklyData',
            'priorityOrders',
            'todaysOrders',
            'recentActivity',
            'recentCommandes',
            'mapOrders'
        ));
    }

    // ─── ORDERS LIST ───────────────────────────────────────────────
    public function orders(Request $request)
    {
        $company = Auth::guard('delivery')->user();
        $status  = $request->get('status', 'all');

        $query = $company->commandes()->with(['client', 'items.produit']);
        if ($status !== 'all') {
            $query->where('delivery_status', $status);
        }
        $commandes = $query->latest()->paginate(15);

        return view('delivery.orders', compact('commandes', 'status'));
    }

    // ─── ORDER DETAIL ──────────────────────────────────────────────
    public function orderDetail($id)
{
    $commande = Commande::with([
        'client',
        'items.produit.boutique',  // loads produit + boutique (user)
        'calls'
    ])->findOrFail($id);
    
    $this->authorizeCommande($commande);
    return view('delivery.order_detail', compact('commande'));
}

    // ─── UPDATE STATUS ─────────────────────────────────────────────
    public function updateOrderStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        $this->authorizeCommande($commande);

        $validated = $request->validate([
            'delivery_status' => 'required|in:pending,picked_up,in_transit,delivered,cancelled',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $oldStatus = $commande->delivery_status;
        $newStatus = $validated['delivery_status'];

        $commande->delivery_status = $newStatus;
        if ($newStatus === 'picked_up' && $oldStatus === 'pending') {
            $commande->picked_up_at = now();
        }
        if ($newStatus === 'delivered') {
            $commande->delivered_at = now();
            $commande->status = 'completed';
        }
        if ($newStatus === 'cancelled') {
            $commande->status = 'cancelled';
        }
        $commande->delivery_notes = $validated['notes'] ?? $commande->delivery_notes;
        $commande->save();

        CommandeHistory::create([
            'commande_id'         => $commande->id_cmd,
            'delivery_company_id' => Auth::guard('delivery')->id(),
            'action'              => 'status_changed',
            'old_status'          => $oldStatus,
            'new_status'          => $newStatus,
            'notes'               => $validated['notes'] ?? null,
            'acted_at'            => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated to ' . str_replace('_', ' ', $newStatus));
    }

    // ─── LOG CALL ──────────────────────────────────────────────────
    public function logCall(Request $request, $id)
{
    $commande = Commande::with('client')->findOrFail($id);
    $this->authorizeCommande($commande);

    $validated = $request->validate([
        'call_type'        => 'required|in:outgoing,incoming',
        'duration_seconds' => 'nullable|integer|min:0',
        'notes'            => 'nullable|string|max:1000',
    ]);

    DeliveryCall::create([
        'delivery_company_id' => Auth::guard('delivery')->id(),
        'commande_id'         => $commande->id_cmd,  // <-- THIS IS THE ONLY FIX NEEDED
        'client_name'         => $commande->client?->name ?? 'Unknown',
        'client_phone'        => $commande->client?->phone ?? '',
        'call_type'           => $validated['call_type'],
        'duration_seconds'    => $validated['duration_seconds'] ?? 0,
        'notes'               => $validated['notes'] ?? null,
        'called_at'           => now(),
    ]);

    return response()->json(['success' => true]);
}
    // ─── ORDER HISTORY ────────────────────────────────────────────
    public function orderHistory()
{
    $company = Auth::guard('delivery')->user();
    $completedCommandes = $company->completedCommandes()
        ->with(['client', 'items.produit.boutique'])
        ->latest('delivered_at')
        ->paginate(20);

    return view('delivery.order_history', compact('completedCommandes'));
}

    // ─── CALL HISTORY ─────────────────────────────────────────────
    public function callHistory(Request $request)
    {
        $company = Auth::guard('delivery')->user();
        $query = $company->calls()->with('commande');

        if ($request->has('commande_id')) {
            $query->where('commande_id', $request->commande_id);
        }
        if ($request->has('date_from')) {
            $query->whereDate('called_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('called_at', '<=', $request->date_to);
        }

        $calls = $query->latest('called_at')->paginate(20);
        return view('delivery.call_history', compact('calls'));
    }

    // ─── HELPERS ──────────────────────────────────────────────────
    private function calculateOnTimeRate($company)
    {
        $total = $company->completedCommandes()->whereDate('delivered_at', '>=', now()->subDays(30))->count();
        if ($total === 0) return 100;
        $onTime = $company->completedCommandes()
            ->whereDate('delivered_at', '>=', now()->subDays(30))
            ->whereRaw('TIMESTAMPDIFF(MINUTE, picked_up_at, delivered_at) <= 120')
            ->count();
        return round(($onTime / $total) * 100);
    }

    private function calculateSuccessRate($company)
    {
        $total = $company->commandes()->whereDate('updated_at', '>=', now()->subDays(30))->count();
        if ($total === 0) return 100;
        $cancelled = $company->commandes()
            ->where('status', 'cancelled')
            ->whereDate('updated_at', '>=', now()->subDays(30))
            ->count();
        return round((($total - $cancelled) / $total) * 100);
    }

    private function formatDuration($minutes)
    {
        if ($minutes < 60) return round($minutes) . ' min';
        $h = floor($minutes / 60);
        $m = round($minutes % 60);
        return $h . 'h ' . ($m > 0 ? $m . 'm' : '');
    }

    private function authorizeCommande(Commande $commande)
    {
        if ($commande->delivery_company_id !== Auth::guard('delivery')->id()) {
            abort(403, 'Unauthorized access to this order.');
        }
    }

    // ─── SETTINGS ──────────────────────────────────────────────────
public function settings()
{
    $company = Auth::guard('delivery')->user();
    return view('delivery.settings', compact('company'));
}

public function updateSettings(Request $request)
{
    $company = Auth::guard('delivery')->user();

    $rules = [
        'email'         => 'required|email|max:255|unique:delivery_companies,email,' . $company->id,
        'phone'         => 'nullable|string|max:50',
        'address'       => 'nullable|string|max:500',
        'shipping_rate' => 'nullable|numeric|min:0',
        'logo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'password'      => 'nullable|string|min:6|confirmed',
    ];

    $validated = $request->validate($rules);

    // Handle logo upload
    if ($request->hasFile('logo')) {
        // Delete old logo if exists (optional)
        if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
            \Storage::disk('public')->delete($company->logo);
        }
        $validated['logo'] = $request->file('logo')->store('delivery_logos', 'public');
    } else {
        unset($validated['logo']);
    }

    // Handle password update
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $company->update($validated);

    return redirect()->route('delivery.settings')
        ->with('success', 'Settings updated successfully.');
}

public function logout()
{
    Auth::guard('delivery')->logout();
    return redirect()->route('delivery.login');
}
}