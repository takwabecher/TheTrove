@extends('layouts.app')

@section('title', 'Reset Password — TheTrove')

@section('content')

<style>
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 140px 5% 60px;
  position: relative;
  background: var(--bg-primary);
  overflow: hidden;
}
.auth-page::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: linear-gradient(var(--accent) 1px, transparent 1px), linear-gradient(90deg, var(--accent) 1px, transparent 1px);
  background-size: 60px 60px;
  opacity: 0.02;
  pointer-events: none;
}
.auth-glow {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  animation: glowPulse 6s ease-in-out infinite;
}
@keyframes glowPulse { 0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); } 50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); } }

.auth-card {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 420px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px var(--shadow);
  animation: fadeInUp 0.8s ease-out;
}
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

.auth-header {
  padding: 40px 40px 0;
  text-align: center;
}
.auth-logo {
  width: 70px;
  height: 70px;
  margin: 0 auto 20px;
  animation: logoFloat 5s ease-in-out infinite;
}
.auth-logo img { width: 100%; filter: drop-shadow(0 10px 30px var(--accent-glow)); }
@keyframes logoFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

.auth-header h2 {
  font-family: 'Cinzel', serif;
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
  letter-spacing: 1px;
}
.auth-header p {
  color: var(--text-muted);
  font-size: 0.9rem;
  font-weight: 300;
  line-height: 1.6;
}

.auth-body {
  padding: 0 40px 40px;
}

.form-group {
  margin-bottom: 20px;
}
.form-label {
  display: block;
  font-size: 0.8rem;
  font-weight: 500;
  color: var(--text-secondary);
  margin-bottom: 8px;
  letter-spacing: 0.5px;
}
.form-input {
  width: 100%;
  padding: 14px 18px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border);
  border-radius: 12px;
  color: var(--text-primary);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  outline: none;
}
.form-input::placeholder { color: var(--text-muted); }
.form-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-glow);
}
.form-input.is-invalid {
  border-color: #e74c3c;
  box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}
.invalid-feedback {
  color: #e74c3c;
  font-size: 0.8rem;
  margin-top: 6px;
}

/* Session Status */
.session-status {
  background: var(--accent-glow);
  border: 1px solid var(--border-hover);
  border-radius: 12px;
  padding: 14px 18px;
  margin-bottom: 20px;
  color: var(--accent);
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  gap: 10px;
}
.session-status i {
  font-size: 1rem;
}

.btn-submit {
  width: 100%;
  padding: 16px;
  background: var(--accent);
  color: var(--bg-primary);
  border: none;
  border-radius: 12px;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}
.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px var(--accent-glow);
}

.auth-footer {
  text-align: center;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid var(--border);
}
.auth-footer p {
  color: var(--text-muted);
  font-size: 0.85rem;
  font-weight: 300;
}
.auth-footer a {
  color: var(--accent);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}
.auth-footer a:hover {
  color: var(--text-primary);
}

.choice-back {
  text-align: center;
  margin-top: 20px;
}
.choice-back a {
  color: var(--text-muted);
  text-decoration: none;
  font-size: 0.85rem;
  transition: color 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.choice-back a:hover {
  color: var(--accent);
}
.choice-back a i {
  font-size: 0.8rem;
}
</style>

<section class="auth-page">
  <div class="auth-glow"></div>

  <div class="auth-card">
    <div class="auth-header">
      <div class="auth-logo">
        <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove">
      </div>
      <h2>Forgot Password?</h2>
      <p>No problem. Just let us know your email address and we will email you a password reset link.</p>
    </div>

    <div class="auth-body">
      <!-- Session Status -->
      @if (session('status'))
        <div class="session-status">
          <i class="fas fa-check-circle"></i>
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-input @error('email') is-invalid @enderror"
                 id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane"></i> Email Password Reset Link
        </button>
      </form>

      <div class="auth-footer">
        <p>Remember your password? <a href="{{ route('login') }}">Sign in</a></p>
      </div>
      <div class="choice-back">
        <a href="{{ route('choice') }}"><i class="fas fa-arrow-left"></i> Back to choices</a>
      </div>
    </div>
  </div>
</section>

@endsection