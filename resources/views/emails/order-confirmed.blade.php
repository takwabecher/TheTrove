<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Order Received — TheTrove</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@400;500;600&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f3ef;
            background-image: radial-gradient(ellipse at 50% 0%, rgba(212, 175, 55, 0.06) 0%, transparent 60%);
            margin: 0;
            padding: 40px 20px;
            color: #1a1a2e;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.15);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(212, 175, 55, 0.06), 0 0 40px rgba(212, 175, 55, 0.04);
        }
        .header {
            position: relative;
            padding: 48px 40px 32px;
            text-align: center;
            background: linear-gradient(180deg, rgba(212, 175, 55, 0.06) 0%, transparent 100%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.12);
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(184, 148, 31, 0.4), transparent);
        }
        .header h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.3rem;
            margin: 16px 0 0;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .content {
            padding: 36px 40px 40px;
        }
        .greeting {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            color: #1a1a2e;
            margin: 0 0 20px;
            line-height: 1.3;
        }
        .greeting em {
            color: #b8941f;
            font-style: normal;
        }
        .message {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0 0 24px;
        }
        .order-box {
            background: rgba(212, 175, 55, 0.08);
            border: 1px solid rgba(184, 148, 31, 0.2);
            border-radius: 14px;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .order-box h3 {
            font-family: 'Cinzel', serif;
            color: #b8941f;
            margin: 0 0 12px;
            font-size: 1.1rem;
        }
        .order-box p {
            color: #6b7280;
            margin: 0;
            font-size: 0.9rem;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
        }
        .items-table th {
            background: rgba(212, 175, 55, 0.08);
            color: #b8941f;
            font-family: 'Cinzel', serif;
            font-size: 0.85rem;
            padding: 12px 16px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(184, 148, 31, 0.2);
        }
        .items-table td {
            padding: 14px 16px;
            color: #6b7280;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(212, 175, 55, 0.08);
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .item-name {
            font-weight: 600;
            color: #1a1a2e;
        }
        .item-price {
            font-family: 'Cinzel', serif;
            color: #b8941f;
            font-weight: 700;
        }
        .total-row {
            background: rgba(212, 175, 55, 0.06);
        }
        .total-row td {
            font-weight: 600;
            color: #1a1a2e;
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
            color: rgba(184, 148, 31, 0.4);
            font-size: 0.7rem;
        }
        .divider span {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(184, 148, 31, 0.3), transparent);
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #D4AF37 0%, #b8941f 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border: 1px solid rgba(184, 148, 31, 0.3);
            box-shadow: 0 8px 25px rgba(184, 148, 31, 0.2);
        }
        .footer {
            background: #faf8f5;
            padding: 28px 40px;
            text-align: center;
            border-top: 1px solid rgba(212, 175, 55, 0.08);
        }
        .footer p {
            color: #6b7280;
            font-size: 0.85rem;
            margin: 0 0 6px;
        }
        .footer strong {
            font-family: 'Cinzel', serif;
            color: #b8941f;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .footer .tagline {
            color: #9ca3af;
            font-size: 0.75rem;
            margin-top: 8px;
            font-style: italic;
        }
        @media (max-width: 480px) {
            body { padding: 20px 12px; }
            .content, .header, .footer { padding-left: 24px; padding-right: 24px; }
            .greeting { font-size: 1.25rem; }
            .items-table th, .items-table td { padding: 10px 12px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></h2>
        </div>

        <div class="content">
            <h1 class="greeting">New Order, <em>{{ $boutique->name ?? 'Keeper' }}</em>!</h1>

            <p class="message">
                A hunter has confirmed their order, and some treasures from your vault are included! Time to prepare the shipment.
            </p>

            <div class="order-box">
                <h3>Order: {{ $commande->id_cmd }}</h3>
                <p>Confirmed on {{ $commande->updated_at->format('M d, Y') }} at {{ $commande->updated_at->format('h:i A') }}</p>
            </div>

            <p class="message" style="font-weight: 600; color: #1a1a2e;">
                Items from your vault in this order:
            </p>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Treasure</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $boutiqueTotal = 0; @endphp
                    @foreach($items as $item)
                        @php $boutiqueTotal += $item['total']; @endphp
                        <tr>
                            <td class="item-name">{{ $item['nom'] }}</td>
                            <td>{{ $item['quantite'] }}</td>
                            <td class="item-price">{{ number_format($item['prix'], 2) }} DT</td>
                            <td class="item-price">{{ number_format($item['total'], 2) }} DT</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Your Earnings:</td>
                        <td class="item-price">{{ number_format($boutiqueTotal, 2) }} DT</td>
                    </tr>
                </tbody>
            </table>

            <p class="message">
                Please prepare these items for shipment. The hunter is waiting!
            </p>

            <div class="button-wrapper">
                <a href="{{ url('/boutique/commandes') }}" class="button">View Orders</a>
            </div>

            <div class="divider">
                <span></span>
                <span></span>
            </div>

            <p class="message" style="font-size: 0.85rem; text-align: center;">
                Thank you for being a trusted Treasure Keeper on TheTrove!
            </p>
        </div>

        <div class="footer">
            <p>Regards,<br><strong><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></strong></p>
            <p class="tagline">"Find Your Treasure. Be the Treasure."</p>
        </div>
    </div>
</body>
</html>