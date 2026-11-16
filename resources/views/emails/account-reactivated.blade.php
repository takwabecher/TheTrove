<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome Back — TheTrove</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@400;500;600&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f3ef;
            background-image: radial-gradient(ellipse at 50% 0%, rgba(34, 197, 94, 0.06) 0%, transparent 60%);
            margin: 0;
            padding: 40px 20px;
            color: #1a1a2e;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(34, 197, 94, 0.15);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(34, 197, 94, 0.06), 0 0 40px rgba(34, 197, 94, 0.04);
        }
        .header {
            position: relative;
            padding: 48px 40px 32px;
            text-align: center;
            background: linear-gradient(180deg, rgba(34, 197, 94, 0.06) 0%, transparent 100%);
            border-bottom: 1px solid rgba(34, 197, 94, 0.12);
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.4), transparent);
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
            color: #22c55e;
            font-style: normal;
        }
        .message {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0 0 24px;
        }
        .highlight-box {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 14px;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
        }
        .highlight-box h3 {
            font-family: 'Cinzel', serif;
            color: #16a34a;
            margin: 0 0 12px;
            font-size: 1.1rem;
        }
        .highlight-box p {
            color: #6b7280;
            margin: 0;
            font-size: 0.9rem;
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
            background: linear-gradient(135deg, #16a34a 0%, #16a34a 100%);
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
        .welcome-back {
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            font-style: italic;
            margin-bottom: 20px;
        }
        @media (max-width: 480px) {
            body { padding: 20px 12px; }
            .content, .header, .footer { padding-left: 24px; padding-right: 24px; }
            .greeting { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></h2>
        </div>

        <div class="content">
            <p class="welcome-back">"The best treasures are worth waiting for."</p>

            <h1 class="greeting">Welcome Back, <em>{{ $user->name ?? 'Explorer' }}</em>!</h1>

            @if($isBoutique)
                {{-- BOUTIQUE / KEEPER VERSION --}}
                <p class="message">
                    Great news! Your vault has been <strong style="color: #16a34a;">reactivated</strong> and is now open for business again.
                </p>

                <div class="highlight-box">
                    <h3>Vault Unlocked</h3>
                    <p>Your treasures are visible again. Hunters can now browse, wishlist, and purchase from your collection.</p>
                </div>

                <p class="message">
                    We appreciate your patience and cooperation. Thank you for being a valued Treasure Keeper on TheTrove.
                </p>

                <div class="button-wrapper">
                    <a href="{{ url('/boutique/dashboard') }}" class="button">Return to My Vault</a>
                </div>
            @else
                {{-- CLIENT / TREASURE HUNTER VERSION --}}
                <p class="message">
                    Great news! Your account has been <strong style="color: #16a34a;">reactivated</strong> and your hunt can resume.
                </p>

                <div class="highlight-box">
                    <h3>Hunt Resumed</h3>
                    <p>Your treasure hunting privileges are fully restored. Browse, wishlist, and claim your next discovery.</p>
                </div>

                <p class="message">
                    We appreciate your patience and cooperation. Welcome back to the hunt — your next treasure awaits!
                </p>

                <div class="button-wrapper">
                    <a href="{{ url('/dashboard') }}" class="button">Resume Hunting</a>
                </div>
            @endif

            <div class="divider">
                <span></span>
                <span></span>
            </div>

            <p class="message" style="font-size: 0.85rem; text-align: center;">
                Happy hunting, and thank you for being part of TheTrove community!
            </p>
        </div>

        <div class="footer">
            <p>Regards,<br><strong style="font-family: 'Cinzel', serif; letter-spacing: 1px;"><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></strong></p>
            <p class="tagline">"Find Your Treasure. Be the Treasure."</p>
        </div>
    </div>
</body>
</html>