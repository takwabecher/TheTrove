<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Your Password — TheTrove</title>
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
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.15);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(212, 175, 55, 0.06),
                0 0 40px rgba(212, 175, 55, 0.04);
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
        .logo-wrapper {
            position: relative;
            display: inline-block;
        }
        .logo-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(ellipse, rgba(212, 175, 55, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .header img {
            max-width: 100px;
            height: auto;
            position: relative;
            z-index: 1;
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
            margin: 0 0 32px;
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
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(184, 148, 31, 0.3);
        }
        .warning-box {
            background: rgba(212, 175, 55, 0.06);
            border: 1px solid rgba(184, 148, 31, 0.18);
            border-radius: 14px;
            padding: 20px 24px;
            margin: 24px 0 0;
        }
        .warning-box p {
            color: #6b7280;
            font-size: 0.85rem;
            line-height: 1.6;
            margin: 0 0 8px;
        }
        .warning-box p:last-child {
            margin-bottom: 0;
        }
        .warning-box .icon {
            color: #b8941f;
            margin-right: 6px;
        }
        .warning-box strong {
            color: #b8941f;
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
            line-height: 1.5;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></h2>
        </div>

        <div class="content">
            <h1 class="greeting">Hello, <em>{{ $user->name ?? 'Treasure Hunter' }}</em>!</h1>

            <p class="message">
                You are receiving this email because we received a password reset request for your account.
                @if($user->role === 'boutique')
                    No worries — we'll help you get back to guarding your vault in no time.
                @else
                    No worries — we'll help you get back to hunting treasures in no time.
                @endif
            </p>

            <div class="divider">
                <span></span>
                <span>◆</span>
                <span></span>
            </div>

            <div class="button-wrapper">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>

            <div class="warning-box">
                <p>This password reset link will expire in <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes</strong>.</p>
                <p>If you did not request a password reset, no further action is required.
                    @if($user->role === 'boutique')
                        Your vault remains secure.
                    @else
                        Your treasures remain safe.
                    @endif
                </p>
            </div>
        </div>

        <div class="footer">
            <p>Regards,<br><strong style="font-family: 'Cinzel', serif; letter-spacing: 1px;"><span style="color: #1a1a2e;">THE</span><span style="color: #b8941f;">TROVE</span></strong></p>
            <p class="tagline">"Find Your Treasure. Be the Treasure."</p>
        </div>
    </div>
</body>
</html>