@extends('layouts.app')

@section('title', 'Sign Up — TheTrove')

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
  max-width: 480px;
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
}

.auth-divider {
  display: flex;
  align-items: center;
  gap: 16px;
  margin: 24px 40px;
}
.auth-divider::before,
.auth-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}
.auth-divider span {
  font-size: 0.75rem;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 2px;
}

.auth-body {
  padding: 0 40px 40px;
}

.form-group {
  margin-bottom: 18px;
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
.form-input::placeholder { color: var(--text-muted); opacity: 0.6; }
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

.form-select {
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
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23a8a29e' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 18px center;
}
.form-select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-glow);
}
.form-select option {
  background: var(--bg-card);
  color: var(--text-primary);
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
  margin-top: 8px;
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

.role-hint {
  font-size: 0.75rem;
  color: var(--text-muted);
  margin-top: 4px;
  font-weight: 300;
}

@media (max-width: 768px) {
  .auth-card { margin: 0 16px; }
  .auth-header { padding: 30px 24px 0; }
  .auth-body { padding: 0 24px 30px; }
  .auth-divider { margin: 20px 24px; }
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
      <h2>Join TheTrove</h2>
      <p>Sign up and begin your adventure journey.</p>
    </div>

    <div class="auth-divider">
      <span>sign up</span>
    </div>

    <div class="auth-body">
      <form action="{{ route('register') }}" method="POST">
        @csrf

        <!-- Name -->
        <div class="form-group">
          <label for="name" class="form-label">Full Name *</label>
          <input type="text" class="form-input @error('name') is-invalid @enderror"
                 id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email" class="form-label">Email *</label>
          <input type="email" class="form-input @error('email') is-invalid @enderror"
                 id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Phone -->
        <div class="form-group">
          <label for="phone" class="form-label">Phone *</label>
          <input type="text" class="form-input @error('phone') is-invalid @enderror"
                 id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890" required>
          @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Address -->
        <div class="form-group">
          <label for="address" class="form-label">Address *</label>
          <input type="text" class="form-input @error('address') is-invalid @enderror"
                 id="address" name="address" value="{{ old('address') }}" placeholder="New York, USA" required>
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Role -->
        <div class="form-group">
          <label for="role" class="form-label">Role *</label>
          <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            <option value="">-- You are a treasure... --</option>
            <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Hunter (Buyer)</option>
            <option value="boutique" {{ old('role') === 'boutique' ? 'selected' : '' }}>Keeper (Seller)</option>
          </select>
          <p class="role-hint">Hunters buy treasures. Keepers sell them.</p>
          @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password" class="form-label">Password *</label>
          <input type="password" class="form-input @error('password') is-invalid @enderror"
                 id="password" name="password" placeholder="••••••••" required>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password Confirmation -->
        <div class="form-group">
          <label for="password_confirmation" class="form-label">Confirm Password *</label>
          <input type="password" class="form-input" id="password_confirmation"
                 name="password_confirmation" placeholder="••••••••" required>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">
          <img src="{{ asset('images/signmini.png') }}" alt="signmini"> Sign Up
        </button>
      </form>

      <div class="auth-footer">
        <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
      </div>
      <div class="auth-divider">
        <span>or</span>
      </div>
      <div class="choice-back">
        <a href="{{ route('choice') }}"><i class="fas fa-arrow-left"></i> Back to choices</a>
      </div>
    </div>
  </div>
</section>

@endsection