@extends('delivery.layout')

@section('title', 'Command Center — TheTrove Delivery')

@section('delivery_content')
<div class="command-center">

    <!-- LIVE HEADER -->
    <div class="cc-header reveal">
        <div class="cc-header-left">
            <div class="live-badge">
                <span class="live-pulse"></span>
                LIVE
            </div>
            <h1 class="cc-title">
                <img src="{{ asset('images/deliverymini.png') }}" alt="delivery">
                Welcome back, <span style="color: #c9a84c;">{{ auth('delivery')->user()->name }}</span>
            </h1>
            <p class="cc-subtitle">
                - {{ now()->format('l, F j, Y') }} -
            </p>
        </div>
        <div class="cc-header-right">
            <div class="weather-clock">
                <div class="clock-time" id="liveClock">--:--</div>
                <div class="clock-date">{{ now()->format('M d') }}</div>
            </div>
        </div>
    </div>
    <!-- PERFORMANCE ROW -->
    <div class="perf-row reveal">
        <div class="perf-card">
            <div class="perf-ring">
                <svg viewBox="0 0 36 36">
                    <path class="ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="ring-fill" stroke-dasharray="{{ $performance['on_time_rate'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="ring-value">{{ $performance['on_time_rate'] }}%</div>
            </div>
            <div class="perf-info">
                <div class="perf-label">On-Time Rate</div>
                <div class="perf-sublabel">Last 30 days</div>
            </div>
        </div>

        <div class="perf-card">
            <div class="perf-ring">
                <svg viewBox="0 0 36 36">
                    <path class="ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="ring-fill success" stroke-dasharray="{{ $performance['success_rate'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="ring-value">{{ $performance['success_rate'] }}%</div>
            </div>
            <div class="perf-info">
                <div class="perf-label">Success Rate</div>
                <div class="perf-sublabel">Last 30 days</div>
            </div>
        </div>

        <div class="perf-card wide">
            <div class="perf-icon"><img src="{{ asset('images/timemini1.png') }}" alt="time"></div>
            <div class="perf-info">
                <div class="perf-value">{{ $performance['avg_delivery_formatted'] }}</div>
                <div class="perf-label">Avg. Delivery Time</div>
                <div class="perf-sublabel">Pickup to drop-off</div>
            </div>
        </div>

        <div class="perf-card wide">
            <div class="perf-icon"><img src="{{ asset('images/ordermini1.png') }}" alt="order"></div>
            <div class="perf-info">
                <div class="perf-value">{{ $stats['completed_today'] }}</div>
                <div class="perf-label">Completed Today</div>
                <div class="perf-sublabel">{{ now()->format('D, M j') }}</div>
            </div>
        </div>
    </div>

    <!-- CHARTS & PRIORITY ROW -->
    <div class="split-row reveal">
        <!-- WEEKLY CHART -->
        <div class="panel chart-panel">
            <div class="panel-header">
                <h3> Weekly Performance</h3>
                <div class="chart-legend">
                    <span class="legend-dot delivered"></span> Delivered
                    <span class="legend-dot picked"></span> Picked Up
                </div>
            </div>
            <div class="chart-container">
                <div class="bar-chart" id="weeklyChart">
                    @foreach($weeklyData as $day)
                    <div class="bar-group">
                        <div class="bar-stack">
                            <div class="bar delivered" style="height: {{ min($day['delivered'] * 12, 140) }}px" title="{{ $day['delivered'] }} delivered">
                                <span class="bar-value">{{ $day['delivered'] > 0 ? $day['delivered'] : '' }}</span>
                            </div>
                            <div class="bar picked" style="height: {{ min($day['picked_up'] * 12, 140) }}px" title="{{ $day['picked_up'] }} picked up">
                                <span class="bar-value">{{ $day['picked_up'] > 0 ? $day['picked_up'] : '' }}</span>
                            </div>
                        </div>
                        <div class="bar-label">{{ $day['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- PRIORITY ALERTS -->
        <div class="panel alerts-panel">
            <div class="panel-header">
                <h3>Priority Alerts</h3>
                <span class="alert-count">{{ $priorityOrders->count() }}</span>
            </div>
            <div class="alerts-list">
                @forelse($priorityOrders as $order)
                @php
                    $isLate = $order->delivery_status === 'pending' && $order->created_at->diffInHours(now()) > 2;
                    $alertClass = $isLate ? 'critical' : 'warning';
                    $timeAgo = $order->delivery_status === 'pending'
                        ? $order->created_at->diffForHumans()
                        : $order->picked_up_at->diffForHumans();
                @endphp
                <div class="alert-item {{ $alertClass }}">
                    <div class="alert-dot"></div>
                    <div class="alert-body">
                        <div class="alert-title">
                            Order {{ $order->formatted_id }}
                            <span class="alert-badge {{ $order->delivery_status }}">{{ str_replace('_', ' ', $order->delivery_status) }}</span>
                        </div>
                        <div class="alert-meta">
                            {{ $order->client?->name ?? 'N/A' }}
                            <span class="alert-time">{{ $timeAgo }}</span>
                        </div>
                    </div>
                    <a href="{{ route('delivery.order_detail', $order->getKey()) }}" class="alert-action">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                @empty
                <div class="alerts-empty">
                    <i class="fas fa-check-circle"></i>
                    <p>All orders on track. No priority alerts.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TODAY'S ROUTE & ACTIVITY -->
    <div class="split-row reveal">
        <!-- TODAY'S ORDERS -->
        <div class="panel route-panel">
            <div class="panel-header">
                <h3> Today's Route</h3>
                <a href="{{ route('delivery.orders') }}" class="panel-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="route-list">
                @forelse($todaysOrders as $order)
                <div class="route-item">
                    <div class="route-status {{ $order->delivery_status }}">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="route-body">
                        <div class="route-title">{{ $order->formatted_id }} — {{ $order->client?->name ?? 'N/A' }}</div>
                        <div class="route-meta">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $order->client?->address ?? 'No address' }}</span>
                            <span class="route-time">{{ $order->created_at?->format('H:i') ?? '--:--' }}</span>
                        </div>
                    </div>
                    <div class="route-action">
                        <a href="{{ route('delivery.order_detail', $order->getKey()) }}" class="btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="route-empty">
                    <i class="fas fa-mug-hot"></i>
                    <p>No orders on today's route yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ACTIVITY FEED -->
        <div class="panel activity-panel">
            <div class="panel-header">
                <h3> Live Activity</h3>
            </div>
            <div class="activity-feed">
                @forelse($recentActivity as $activity)
                @php
                    $iconMap = [
                        'status_changed' => 'fa-exchange-alt',
                        'call_logged'    => 'fa-phone',
                        'note_added'     => 'fa-sticky-note',
                    ];
                    $icon = $iconMap[$activity->action] ?? 'fa-circle';
                    $actionLabel = str_replace('_', ' ', ucfirst($activity->action));
                @endphp
                <div class="activity-item">
                    <div class="activity-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="activity-body">
                        <div class="activity-text">
                            <strong>{{ $actionLabel }}</strong> — Order {{ $activity->commande->formatted_id ?? ' ' . $activity->commande_id }}
                            @if($activity->old_status && $activity->new_status)
                            <span class="status-change">
                                <span class="old">{{ $activity->old_status }}</span>
                                <i class="fas fa-arrow-right"></i>
                                <span class="new">{{ $activity->new_status }}</span>
                            </span>
                            @endif
                        </div>
                        <div class="activity-time">{{ $activity->acted_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="activity-empty">
                    <p>No recent activity.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TUNISIA DELIVERY MAP -->
<div class="map-section reveal">
    <div class="panel-header">
        <h3>Delivery Map</h3>
        <span class="map-badge">{{ count($mapOrders) }} active</span>
    </div>
    <div id="deliveryMap" style="height: 420px; width: 100%; border-radius: 0 0 16px 16px;"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- QUICK ACTIONS -->
    <div class="quick-actions reveal">
        <div class="panel-header">
            
        </div>
        <div class="action-grid">
            <a href="{{ route('delivery.orders', ['status' => 'pending']) }}" class="action-card">
                <div class="action-icon"><img src="{{ asset('images/waitmini.png') }}" alt="wait"></div>
                <span>Pending</span>
                @if($stats['pending_pickup'] > 0)
                <div class="action-badge">{{ $stats['pending_pickup'] }}</div>
                @endif
            </a>
            <a href="{{ route('delivery.orders', ['status' => 'picked_up']) }}" class="action-card">
                <div class="action-icon"><img src="{{ asset('images/prepareM.png') }}" alt="prepare"></div>
                <span>Picked Up</span>
            </a>
            <a href="{{ route('delivery.orders', ['status' => 'in_transit']) }}" class="action-card">
                <div class="action-icon"><img src="{{ asset('images/deliveryM.png') }}" alt="delivery"></div>
                <span>In Transit</span>
            </a>
            <a href="{{ route('delivery.order_history') }}" class="action-card">
                <div class="action-icon"><img src="{{ asset('images/timeM.png') }}" alt="png"></div>
                <span>History</span>
            </a>
            <a href="{{ route('delivery.call_history') }}" class="action-card">
                <div class="action-icon"><img src="{{ asset('images/phonem.png') }}" alt="call"></div>
                <span>Calls</span>
            </a>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="recent-orders reveal">
        <div class="panel-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('delivery.orders') }}" class="panel-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Client</th>
                        <th>Boutique(s)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCommandes as $commande)
                    @php
                        $orderKey = $commande->getKey();
                        $boutiqueNames = $commande->items
        ->map(fn($item) => $item->produit?->boutique?->name ?? $item->produit?->boutique?->nom)
        ->filter()
        ->unique()
        ->values();
                    @endphp
                    <tr>
                        <td>
                            <div class="company-cell">
                                <span class="company-name">{{ $commande->formatted_id ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="client-cell">
                                <span class="client-name">{{ $commande->client?->name ?? 'N/A' }}</span>
                                <span class="client-phone">{{ $commande->client?->phone ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($boutiqueNames->isEmpty())
                                <span class="text-muted">N/A</span>
                            @else
                                <span class="boutique-tags">
                                    @foreach($boutiqueNames as $name)
                                    <span class="tag">{{ $name }}</span>
                                    @endforeach
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="status-pill {{ $commande->delivery_status }}">
                                <i class="fas fa-circle"></i> {{ ucfirst(str_replace('_', ' ', $commande->delivery_status)) }}
                            </span>
                        </td>
                        <td>{{ $commande->created_at?->format('M d, H:i') ?? '-' }}</td>
                        <td>
                            @if($orderKey)
                                <a href="{{ route('delivery.order_detail', $orderKey) }}" class="btn-icon edit" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @else
                                <span class="btn-icon disabled"><i class="fas fa-eye"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-cell">
                            <i class="fas fa-inbox"></i>
                            <p>No orders assigned yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════
   COMMAND CENTER — TheTrove Palette (Gold/Amber)
   Uses existing CSS variables for full dark/light compatibility
   ═══════════════════════════════════════════════════════════════ */

.command-center {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 16px;
}

/* ─── HEADER ─── */
.cc-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
    padding: 24px 0;
    border-bottom: 1px solid var(--border);
}

.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(239, 68, 68, 0.08);
    color: #ef4444;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 2px;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid rgba(239, 68, 68, 0.2);
    margin-bottom: 12px;
}

.live-pulse {
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    animation: pulseLive 2s infinite;
}

@keyframes pulseLive {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

.cc-title {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 8px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.cc-title img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.cc-subtitle {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin: 0;
}

.cc-subtitle strong {
    color: var(--text-primary);
}

.weather-clock {
    text-align: right;
}

.clock-time {
    font-family: 'Cinzel', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--accent);
    line-height: 1;
}

.clock-date {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-top: 4px;
}

/* ─── KPI GRID ─── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.kpi-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.kpi-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-2px);
    box-shadow: 0 10px 40px var(--shadow);
}

.kpi-card.urgent { border-top: 3px solid #f59e0b; }
.kpi-card.active { border-top: 3px solid var(--accent); }
.kpi-card.success { border-top: 3px solid #10b981; }
.kpi-card.info { border-top: 3px solid #8b5cf6; }

.kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.kpi-card.urgent .kpi-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.kpi-card.active .kpi-icon { background: var(--accent-glow); color: var(--accent); }
.kpi-card.success .kpi-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.kpi-card.info .kpi-icon { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

.kpi-trend {
    font-size: 0.8rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
}

.kpi-trend.up { color: #10b981; background: rgba(16, 185, 129, 0.1); }
.kpi-trend.down { color: #ef4444; background: rgba(239, 68, 68, 0.1); }

.kpi-number {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 6px;
}

.kpi-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
}

.kpi-bar {
    height: 4px;
    background: rgba(255,255,255,0.05);
    border-radius: 2px;
    overflow: hidden;
}

[data-theme="light"] .kpi-bar {
    background: rgba(0,0,0,0.05);
}

.kpi-bar-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 1s ease;
}

.kpi-card.urgent .kpi-bar-fill { background: #f59e0b; }
.kpi-card.active .kpi-bar-fill { background: var(--accent); }
.kpi-card.success .kpi-bar-fill { background: #10b981; }
.kpi-card.info .kpi-bar-fill { background: #8b5cf6; }

/* ─── PERFORMANCE ROW ─── */
.perf-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr) repeat(2, 1.5fr);
    gap: 20px;
    margin-bottom: 24px;
}

.perf-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.perf-card:hover {
    border-color: var(--border-hover);
}

.perf-ring {
    position: relative;
    width: 64px;
    height: 64px;
    flex-shrink: 0;
}

.perf-ring svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.ring-bg {
    fill: none;
    stroke: var(--border);
    stroke-width: 3;
}

.ring-fill {
    fill: none;
    stroke: var(--accent);
    stroke-width: 3;
    stroke-linecap: round;
    transition: stroke-dasharray 1.5s ease;
}

.ring-fill.success { stroke: #10b981; }

.ring-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text-primary);
}

.perf-info { flex: 1; }

.perf-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.perf-sublabel {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.perf-card.wide {
    justify-content: flex-start;
    gap: 20px;
}

.perf-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--accent-glow);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.perf-value {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* ─── SPLIT ROWS ─── */
.split-row {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 20px;
    margin-bottom: 24px;
}

.panel {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.panel-header h3 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.panel-header h3 i {
    color: var(--accent);
    font-size: 0.9rem;
}

.panel-link {
    color: var(--accent);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: gap 0.3s;
}

.panel-link:hover {
    gap: 10px;
}

/* ─── CHART ─── */
.chart-container {
    padding: 24px;
    height: 220px;
}

.bar-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    height: 100%;
    gap: 12px;
}

.bar-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.bar-stack {
    display: flex;
    align-items: flex-end;
    gap: 4px;
    height: 160px;
    width: 100%;
    justify-content: center;
}

.bar {
    width: 16px;
    border-radius: 4px 4px 0 0;
    position: relative;
    transition: all 0.5s ease;
    min-height: 4px;
}

.bar.delivered { background: #10b981; }
.bar.picked { background: var(--accent); opacity: 0.6; }

.bar-value {
    position: absolute;
    top: -18px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-secondary);
    white-space: nowrap;
}

.bar-label {
    font-size: 0.7rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.chart-legend {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 2px;
    display: inline-block;
}

.legend-dot.delivered { background: #10b981; }
.legend-dot.picked { background: var(--accent); opacity: 0.6; }

/* ─── ALERTS ─── */
.alert-count {
    background: #ef4444;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.alerts-list {
    padding: 8px;
    max-height: 280px;
    overflow-y: auto;
}

.alert-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 6px;
    transition: all 0.3s;
    cursor: pointer;
}

.alert-item:hover {
    background: rgba(255,255,255,0.03);
}

[data-theme="light"] .alert-item:hover {
    background: rgba(0,0,0,0.02);
}

.alert-item.critical {
    background: rgba(239, 68, 68, 0.04);
    border: 1px solid rgba(239, 68, 68, 0.1);
}

.alert-item.warning {
    background: rgba(245, 158, 11, 0.04);
    border: 1px solid rgba(245, 158, 11, 0.1);
}

.alert-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.alert-item.critical .alert-dot { background: #ef4444; animation: pulseLive 2s infinite; }
.alert-item.warning .alert-dot { background: #f59e0b; }

.alert-body { flex: 1; min-width: 0; }

.alert-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.alert-badge {
    font-size: 0.65rem;
    padding: 2px 8px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.alert-badge.picked_up { background: var(--accent-glow); color: var(--accent); }
.alert-badge.in_transit { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

.alert-meta {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: flex;
    gap: 12px;
}

.alert-time { color: #ef4444; }

.alert-action {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--bg-tertiary);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.alert-action:hover {
    background: var(--accent);
    color: var(--bg-primary);
}

.alerts-empty {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-muted);
}

.alerts-empty i {
    font-size: 2rem;
    color: #10b981;
    margin-bottom: 12px;
    display: block;
}

/* ─── ROUTE LIST ─── */
.route-list {
    padding: 8px;
    max-height: 320px;
    overflow-y: auto;
}

.route-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 4px;
    transition: all 0.3s;
}

.route-item:hover {
    background: rgba(255,255,255,0.03);
}

[data-theme="light"] .route-item:hover {
    background: rgba(0,0,0,0.02);
}

.route-status {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.route-status.pending { color: #f59e0b; }
.route-status.picked_up { color: var(--accent); }
.route-status.in_transit { color: #8b5cf6; }
.route-status.delivered { color: #10b981; }
.route-status.cancelled { color: #ef4444; }

.route-status i { font-size: 0.5rem; }

.route-body { flex: 1; min-width: 0; }

.route-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.route-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.route-meta span:first-child {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.route-time {
    font-family: 'Cinzel', serif;
    color: var(--accent);
    font-weight: 600;
}

.route-action { flex-shrink: 0; }

.btn-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-sm:hover {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--accent);
}

.route-empty {
    text-align: center;
    padding: 48px;
    color: var(--text-muted);
}

.route-empty i {
    font-size: 2rem;
    margin-bottom: 12px;
    display: block;
    color: var(--accent);
}

/* ─── ACTIVITY FEED ─── */
.activity-feed {
    padding: 8px 16px;
    max-height: 320px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--accent-glow);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.activity-body { flex: 1; }

.activity-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 4px;
}

.activity-text strong {
    color: var(--text-primary);
}

.status-change {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-left: 6px;
    font-size: 0.75rem;
}

.status-change .old {
    color: var(--text-muted);
    text-decoration: line-through;
}

.status-change .new {
    color: #10b981;
    font-weight: 600;
}

.status-change i {
    font-size: 0.6rem;
    color: var(--text-muted);
}

.activity-time {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.activity-empty {
    text-align: center;
    padding: 48px;
    color: var(--text-muted);
}

/* ─── QUICK ACTIONS ─── */
.quick-actions {
    margin-bottom: 24px;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-top: 16px;
}

.action-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px 16px;
    text-align: center;
    text-decoration: none;
    color: var(--text-secondary);
    position: relative;
    transition: all 0.3s ease;
}

.action-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px var(--accent-glow);
    color: var(--text-primary);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 1.3rem;
    color: var(--accent);
    transition: all 0.3s;
}

.action-card:hover .action-icon {
    background: var(--accent-glow);
    transform: scale(1.1);
}

.action-card span {
    font-size: 0.85rem;
    font-weight: 600;
    display: block;
}

.action-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--bg-primary);
}

/* ─── TABLE ─── */
.recent-orders {
    margin-bottom: 40px;
}

.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    margin-top: 16px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 16px 20px;
    color: var(--text-muted);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-tertiary);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr:hover td {
    background: rgba(255,255,255,0.02);
}

[data-theme="light"] .data-table tr:hover td {
    background: rgba(0,0,0,0.01);
}

.company-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.company-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--accent-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-weight: 700;
    font-size: 0.7rem;
    font-family: 'Cinzel', serif;
}

.company-name {
    font-weight: 600;
    color: var(--text-primary);
    font-family: 'Cinzel', serif;
    font-size: 0.9rem;
}

.client-cell {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.client-name {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.85rem;
}

.client-phone {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.boutique-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.tag {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    font-size: 0.75rem;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid var(--border);
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    border: 1px solid transparent;
}

.status-pill.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2); }
.status-pill.picked_up { background: var(--accent-glow); color: var(--accent); border-color: var(--border-hover); }
.status-pill.in_transit { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.2); }
.status-pill.delivered { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }
.status-pill.cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2); }

.status-pill i { font-size: 0.4rem; }

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-icon.edit:hover {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--accent);
}

.btn-icon.disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.empty-cell {
    text-align: center;
    padding: 48px !important;
    color: var(--text-muted);
}

.empty-cell i {
    font-size: 2rem;
    margin-bottom: 12px;
    display: block;
    color: var(--accent);
}

.empty-cell p {
    margin: 0;
    font-weight: 600;
    color: var(--text-primary);
}

/* ─── REVEAL ANIMATION ─── */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

/* ─── MAP SECTION ─── */
.map-section {
    margin-bottom: 24px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.map-section .panel-header {
    border-bottom: 1px solid var(--border);
}

.map-badge {
    background: var(--accent-glow);
    color: var(--accent);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}

[data-theme="dark"] .leaflet-popup-content-wrapper,
[data-theme="dark"] .leaflet-popup-tip {
    background: var(--bg-card);
    color: var(--text-primary);
}
[data-theme="dark"] .leaflet-container a {
    color: var(--accent);
}

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--border-hover);
}

/* ─── MOBILE ─── */
@media (max-width: 1200px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .perf-row { grid-template-columns: repeat(2, 1fr); }
    .split-row { grid-template-columns: 1fr; }
    .action-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 768px) {
    .cc-header { flex-direction: column; gap: 20px; }
    .weather-clock { text-align: left; }
    .kpi-grid { grid-template-columns: 1fr; }
    .perf-row { grid-template-columns: 1fr; }
    .action-grid { grid-template-columns: repeat(2, 1fr); }
    .bar-stack { height: 100px; }
    .table-card { overflow-x: auto; }
    .data-table { min-width: 800px; }
}
</style>

<script>

// ─── TUNISIA MAP ───
(function() {
    const orders = @json($mapOrders ?? []);

    const map = L.map('deliveryMap').setView([34.0, 9.0], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const statusColors = {
        pending:    '#f59e0b',
        picked_up:  '#d97706',
        in_transit: '#8b5cf6',
        delivered:  '#10b981',
    };

    const bounds = L.latLngBounds();

    orders.forEach(order => {
        const color = statusColors[order.status] || '#6b7280';

        const marker = L.circleMarker([order.lat, order.lng], {
            radius: 10,
            fillColor: color,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.9
        }).addTo(map);

        const popupHtml = `
            <div style="min-width: 200px; font-family: system-ui;">
                <strong style="color: ${color}; font-size: 0.9rem;">
                    ${order.id}
                </strong><br>
                <span style="font-size: 0.8rem; color: #666;">
                    <i class="fas fa-user"></i> ${order.client}
                </span><br>
                <span style="font-size: 0.75rem; color: #888; display: block; margin: 4px 0;">
                    ${order.address}
                </span>
                <span class="status-pill ${order.status}" style="font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; display: inline-block; margin-top: 4px;">
                    ${order.status.replace('_', ' ')}
                </span><br>
                <a href="${order.url}" style="display: inline-block; margin-top: 8px; font-size: 0.8rem; color: #d97706; text-decoration: none; font-weight: 600;">
                    View Order →
                </a>
            </div>
        `;

        marker.bindPopup(popupHtml);
        bounds.extend([order.lat, order.lng]);
    });

    if (orders.length > 0) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }
})();
// Live clock
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('liveClock').textContent = hours + ':' + minutes;
}
updateClock();
setInterval(updateClock, 1000);

// Scroll reveal
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));

    // Animated counters
    const statNumbers = document.querySelectorAll('.kpi-number');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-target')) || 0;
                let current = 0;
                const increment = target / 40;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        entry.target.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        entry.target.textContent = Math.floor(current).toLocaleString();
                    }
                }, 25);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    statNumbers.forEach(stat => counterObserver.observe(stat));
});
</script>
@endsection