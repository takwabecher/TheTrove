@extends('layouts.app')

@section('title', 'Welcome — TheTrove')

@section('content')

<style>
.choice-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 140px 5% 60px;
  position: relative;
  background: var(--gradient-hero);
  overflow: hidden;
}
.choice-page::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: linear-gradient(var(--accent) 1px, transparent 1px), linear-gradient(90deg, var(--accent) 1px, transparent 1px);
  background-size: 60px 60px;
  opacity: 0.02;
  pointer-events: none;
}
.choice-glow {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  animation: glowPulse 6s ease-in-out infinite;
}
@keyframes glowPulse { 0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); } 50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); } }

.choice-wrapper {
  position: relative;
  z-index: 1;
  text-align: center;
  max-width: 800px;
  width: 100%;
  animation: fadeInUp 0.8s ease-out;
}
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

.choice-logo {
  width: 200px;
  height: 200px;
  margin: 0 auto 30px;
  animation: logoFloat 5s ease-in-out infinite;
}
.choice-logo img { width: 150%; filter: drop-shadow(0 40px 40px var(--accent-glow)); }
@keyframes logoFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }

.choice-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--accent);
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 4px;
  text-transform: uppercase;
  margin-bottom: 20px;
}
.choice-tag::before, .choice-tag::after { content: ''; width: 30px; height: 1px; background: var(--accent); opacity: 0.5; }

.choice-wrapper h1 {
  font-family: 'Cinzel', serif;
  font-size: 3rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 16px;
  letter-spacing: -0.5px;
  line-height: 1.1;
}
.choice-wrapper h1 em { color: var(--accent); font-style: normal; }
.choice-wrapper p {
  color: var(--text-muted);
  font-size: 1rem;
  font-weight: 300;
  margin-bottom: 50px;
  line-height: 1.7;
}

.choice-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  max-width: 600px;
  margin: 0 auto;
}
.choice-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 40px 30px;
  text-align: center;
  transition: all 0.4s ease;
  cursor: pointer;
  text-decoration: none;
  color: var(--text-primary);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  position: relative;
  overflow: hidden;
}
.choice-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, transparent, var(--accent), transparent);
  opacity: 0;
  transition: opacity 0.3s;
}
.choice-card:hover::before { opacity: 1; }
.choice-card:hover {
  transform: translateY(-8px);
  border-color: var(--border-hover);
  box-shadow: 0 20px 50px var(--shadow);
}

.choice-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background: var(--bg-tertiary);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: var(--accent);
  transition: all 0.3s;
}
.choice-card:hover .choice-icon {
  background: var(--accent);
  color: var(--bg-primary);
  border-color: var(--accent);
}

.choice-card h3 {
  font-family: 'Cinzel', serif;
  font-size: 1.3rem;
  font-weight: 600;
  color: var(--text-primary);
}
.choice-card p {
  color: var(--text-muted);
  font-size: 0.9rem;
  font-weight: 300;
  margin: 0;
  line-height: 1.6;
}
.choice-card .choice-btn {
  margin-top: 8px;
  padding: 12px 28px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  transition: all 0.3s;
  border: none;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
}

.choice-card .choice-btn {
  background: var(--accent);
  color: var(--bg-primary);
}
.choice-card:hover .choice-btn {
  box-shadow: 0 8px 24px var(--accent-glow);
}

.choice-divider {
  display: flex;
  align-items: center;
  gap: 16px;
  margin: 30px auto;
  max-width: 200px;
}
.choice-divider::before, .choice-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}
.choice-divider span {
  color: var(--text-muted);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.choice-back {
  margin-top: 30px;
}
.choice-back a {
  color: var(--text-muted);
  text-decoration: none;
  font-size: 0.85rem;
  transition: color 0.3s;
}
.choice-back a:hover { color: var(--accent); }
.choice-back a i { margin-right: 6px; }

@media (max-width: 768px) {
  .choice-cards { grid-template-columns: 1fr; }
  .choice-wrapper h1 { font-size: 2.2rem; }
  .choice-logo { width: 80px; height: 80px; }
}
</style>

<section class="choice-page">
  <div class="choice-glow"></div>

  <div class="choice-wrapper">
    <div class="choice-logo" align='center'>
      <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove">
    </div>
    <h1>Welcome to <em>TheTrove</em></h1>
    <p>Choose your path. Start your journey.<BR>Already a member? Sign in. New here? Join in seconds.</p>

    <div class="choice-cards">
      <!-- LOGIN CARD -->
      <a href="{{ route('login') }}" class="choice-card ">
        <div class="choice-icon" >
            <img src="{{ asset('images/login.png') }}" alt="login">
        </div>
        <h3>I Have an Account</h3>
        <p>Sign in and continue your treasure journey.</p>
        <span class="choice-btn">Sign In</span>
      </a>

      <!-- REGISTER CARD -->
      <a href="{{ route('register') }}" class="choice-card ">
        <div class="choice-icon">
          <img src="{{ asset('images/sign.png') }}" alt="sign">
        </div>
        <h3>I'm New Here</h3>
        <p>Sign up and begin your adventure journey.</p>
        <span class="choice-btn">Sign Up</span>
      </a>
    </div>

    <div class="choice-divider">
      <span>or</span>
    </div>

    <div class="choice-back">
      <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
  </div>
</section>

@endsection