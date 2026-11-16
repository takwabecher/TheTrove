@extends('layouts.app')

@section('title', 'Account Not Found — TheTrove')

@section('content')
<div class="vault-dashboard">

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">

        <div class="approval-wrapper reveal">
            <div class="approval-card">
                <div class="approval-icon">
                    <div class="hourglass-ring" style="border-color: #7f8c8d;">
                        <div class="orbit-dot" style="background: #7f8c8d; box-shadow: 0 0 10px rgba(127, 140, 141, 0.3);"></div>
                    </div>
                    <img src="{{ asset('images/trash.png') }}" alt="sorry">
                </div>
                
                <h2 class="approval-title">Account Not Found</h2>
                
                <p class="approval-text">
                    We couldn't find an account with those credentials. 
                    The account may have been <strong>removed by the administrator</strong> or the details you entered are incorrect.
                </p>
                
                <p class="approval-estimate">
                    <img src="{{ asset('images/trashmini.png') }}" alt="trashmini">
                    Status: <strong style="color: #7f8c8d;">NOT FOUND</strong>
                </p>
                
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('home') }}" class="approval-btn" style="background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%);">
                        <img src="{{ asset('images/backhomemini1.png') }}" alt="backhomemini">
                        Back to Home
                    </a>
                    <a href="{{ route('register') }}" class="approval-btn" style="background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);">
                        <img src="{{ asset('images/signmini.png') }}" alt="signmini">
                        Create Account
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.vault-dashboard {
    padding: 140px 0 80px;
    background: var(--bg-primary);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.approval-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
}

.approval-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 60px 48px;
    max-width: 560px;
    width: 100%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.4s ease;
}

.approval-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #7f8c8d, transparent);
    opacity: 0.6;
}

.approval-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
    transform: translateY(-4px);
}

.approval-icon {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.approval-icon i {
    font-size: 3rem;
    color: #7f8c8d;
    position: relative;
    z-index: 2;
    animation: hourglassPulse 3s ease-in-out infinite;
}

@keyframes hourglassPulse {
    0%, 100% { opacity: 0.7; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.1); }
}

.hourglass-ring {
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 1px solid var(--border);
    animation: orbitSpin 20s linear infinite;
}

.hourglass-ring .orbit-dot {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #7f8c8d;
    box-shadow: 0 0 10px rgba(127, 140, 141, 0.3);
    top: -4px;
    left: 50%;
    transform: translateX(-50%);
}

@keyframes orbitSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.approval-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    letter-spacing: -0.5px;
}

.approval-text {
    color: var(--text-secondary);
    font-size: 1rem;
    line-height: 1.8;
    margin-bottom: 32px;
    font-weight: 300;
}

.approval-timeline {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
    margin-bottom: 32px;
    position: relative;
}

.timeline-line {
    position: absolute;
    top: 20px;
    left: 20%;
    right: 20%;
    height: 2px;
    background: var(--border);
    z-index: 0;
}

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 1;
    flex: 1;
}

.timeline-step img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-tertiary);
    border: 2px solid var(--border);
    padding: 8px;
    object-fit: contain;
    transition: all 0.3s ease;
}

.timeline-step span {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

.approval-estimate {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.approval-estimate img {
    width: 20px;
    height: 20px;
}

.approval-estimate strong {
    color: var(--text-primary);
    font-weight: 600;
}

.approval-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%);
    color: var(--bg-primary);
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.approval-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(127, 140, 141, 0.3);
    text-decoration: none;
    color: var(--bg-primary);
}

.approval-btn img {
    width: 20px;
    height: 20px;
}

@media (max-width: 768px) {
    .approval-card {
        padding: 40px 24px;
        margin: 0 20px;
    }
    
    .approval-title {
        font-size: 1.5rem;
    }
    
    .timeline-step span {
        font-size: 0.65rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { 
            if (entry.isIntersecting) entry.target.classList.add('active'); 
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
});
</script>
@endsection