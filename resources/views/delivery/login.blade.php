<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Login — TheTrove</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --bg-primary: #0f0f12; --bg-secondary: #16161a; --bg-card: #1a1a1f; --border: rgba(255,255,255,0.08); --text-primary: #f0f0f5; --text-secondary: #a0a0b0; --text-muted: #6a6a7a; --accent: #c9a96e; --accent-dim: #a08050; --accent-glow: rgba(201,169,110,0.15); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-container { width: 100%; max-width: 420px; }
        .login-brand { text-align: center; margin-bottom: 40px; }
        .login-brand i { font-size: 3rem; color: var(--accent); margin-bottom: 16px; }
        .login-brand h1 { font-family: 'Cinzel', serif; font-size: 1.8rem; font-weight: 700; margin-bottom: 8px; }
        .login-brand p { color: var(--text-muted); font-size: 0.95rem; }
        .login-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; padding: 40px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: var(--text-secondary); font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; }
        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem; }
        .form-input { width: 100%; padding: 14px 16px 14px 44px; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; color: var(--text-primary); font-family: 'DM Sans', sans-serif; font-size: 0.95rem; transition: all 0.3s ease; }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .remember-check { display: flex; align-items: center; gap: 8px; color: var(--text-secondary); font-size: 0.85rem; cursor: pointer; }
        .remember-check input { accent-color: var(--accent); width: 16px; height: 16px; }
        .btn-login { width: 100%; padding: 16px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%); color: var(--bg-primary); border: none; border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 12px 40px var(--accent-glow); }
        .error-msg { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px 16px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <i class="fas fa-shipping-fast"></i>
            <h1>DeliveryHub</h1>
            <p>Sign in to manage your deliveries</p>
        </div>
        <div class="login-card">
            @if($errors->any())
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif
            <form action="{{ route('delivery.login') }}" method="POST">@csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-input" placeholder="company@example.com" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="remember-row">
                    <label class="remember-check">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
                <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>