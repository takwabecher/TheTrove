<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'TheTrove')</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --bg-primary: #0c0e12;
  --bg-secondary: #14171f;
  --bg-tertiary: #1a1e28;
  --bg-card: #1e2330;
  --bg-hover: #252b3a;
  --text-primary: #f0ebe3;
  --text-secondary: #a8a29e;
  --text-muted: #6b6560;
  --accent: #c9a84c;
  --accent-glow: rgba(201,168,76,0.15);
  --accent-dim: #8a7328;
  --border: rgba(255,255,255,0.06);
  --border-hover: rgba(201,168,76,0.2);
  --shadow: rgba(0,0,0,0.5);
  --gradient-hero: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 50%),
                     radial-gradient(ellipse at 80% 20%, rgba(201,168,76,0.04) 0%, transparent 40%),
                     linear-gradient(180deg, #0c0e12 0%, #14171f 100%);
  --mesh-opacity: 0.03;
  --toggle-bg: #1a1e28;
  --toggle-icon: #c9a84c;
}
[data-theme="light"] {
  --bg-primary: #faf8f5;
  --bg-secondary: #f5f0ea;
  --bg-tertiary: #ede8e0;
  --bg-card: #ffffff;
  --bg-hover: #f0ebe3;
  --text-primary: #1a1612;
  --text-secondary: #5c554d;
  --text-muted: #9a9288;
  --accent: #b8941f;
  --accent-glow: rgba(184,148,31,0.12);
  --accent-dim: #8a7328;
  --border: rgba(0,0,0,0.06);
  --border-hover: rgba(184,148,31,0.3);
  --shadow: rgba(0,0,0,0.08);
  --gradient-hero: radial-gradient(ellipse at 20% 50%, rgba(184,148,31,0.06) 0%, transparent 50%),
                     radial-gradient(ellipse at 80% 20%, rgba(184,148,31,0.03) 0%, transparent 40%),
                     linear-gradient(180deg, #faf8f5 0%, #f5f0ea 100%);
  --mesh-opacity: 0.04;
  --toggle-bg: #ede8e0;
  --toggle-icon: #b8941f;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg-primary); color: var(--text-primary); overflow-x: hidden; transition: background 0.5s ease, color 0.5s ease; }

/* NAVBAR */
.navbar { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: calc(100% - 60px); max-width: 1200px; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; z-index: 1000; background: rgba(255,255,255,0.03); backdrop-filter: blur(40px) saturate(1.5); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 8px 32px var(--shadow); transition: all 0.4s ease; }
[data-theme="light"] .navbar { background: rgba(255,255,255,0.7); box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
.logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.logo-icon { width: 40px; height: 40px; }
.logo-icon img { width: 100%; height: 100%; object-fit: contain; }
.logo-text { font-family: 'Cinzel', serif; font-size: 1.4rem; font-weight: 700; color: var(--text-primary); letter-spacing: 2px; }
.logo-text span { color: var(--accent); }
.nav-center { display: flex; gap: 8px; list-style: none; align-items: center; }
.nav-center a { text-decoration: none; color: var(--text-secondary); font-weight: 400; font-size: 0.85rem; padding: 8px 16px; border-radius: 10px; transition: all 0.3s; position: relative; }
.nav-center a:hover { color: var(--accent); background: var(--accent-glow); }
.nav-right { display: flex; align-items: center; gap: 16px; }
.theme-toggle { width: 44px; height: 44px; border-radius: 12px; border: 1px solid var(--border); background: var(--toggle-bg); color: var(--toggle-icon); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; transition: all 0.3s ease; }
.theme-toggle:hover { border-color: var(--border-hover); transform: rotate(15deg); }
.nav-cta { background: var(--accent); color: var(--bg-primary) !important; padding: 10px 24px; border-radius: 10px; font-weight: 600; font-size: 0.8rem; transition: all 0.3s; text-decoration: none; letter-spacing: 0.5px; }
.nav-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px var(--accent-glow); }

/* ===== CLIENT SEARCH BAR ===== */
.nav-search-wrapper {
    flex: 1;
    max-width: 600px;
    margin: 0 20px;
}

.search-container {
    position: relative;
}

.search-form {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 0 12px;
    transition: all 0.3s ease;
}

.search-input-wrapper:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.search-icon {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-right: 10px;
}

.search-input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 10px 0;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    outline: none;
}

.search-input::placeholder {
    color: var(--text-muted);
}

/* AI Image Search Button */
.ai-search-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 8px;
    font-size: 0.85rem;
    position: relative;
    overflow: hidden;
}

.ai-search-btn:hover {
    background: var(--accent-glow);
    border-color: var(--accent);
    transform: scale(1.05);
}

.ai-search-btn.processing {
    animation: pulse 1s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.ai-search-btn input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

/* Search Filters */
.search-filters {
    display: flex;
    gap: 8px;
    padding: 0 4px;
}

.filter-chip {
    cursor: pointer;
}

.filter-chip input {
    display: none;
}

.filter-chip span {
    display: block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--text-muted);
    background: transparent;
    border: 1px solid transparent;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-chip:hover span {
    color: var(--text-secondary);
}

.filter-chip input:checked + span {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

/* Search Dropdown */
.search-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    left: 0;
    right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 20px 50px var(--shadow);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1001;
    max-height: 400px;
    overflow-y: auto;
}

.search-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.search-loading {
    display: none;
    text-align: center;
    padding: 20px;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.search-loading.active {
    display: block;
}

/* Search Result Items */
.search-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.search-result-item:hover {
    background: var(--bg-hover);
}

.search-result-img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    background: var(--bg-tertiary);
}

.search-result-info {
    flex: 1;
    min-width: 0;
}

.search-result-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.search-result-meta {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.search-result-price {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    color: var(--accent);
    font-size: 0.9rem;
}

.search-section-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 12px 0 8px;
    padding-left: 10px;
}

/* AI Search Results - Visual Similarity */
.ai-search-results {
    display: none;
}

.ai-search-results.active {
    display: block;
}

.ai-match-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--accent-glow);
    color: var(--accent);
    font-size: 0.65rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 10px;
    margin-left: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ai-match-badge i {
    font-size: 0.6rem;
}

.ai-preview-img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid var(--accent);
    margin-bottom: 12px;
}

.ai-results-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 10px;
    margin-bottom: 8px;
    border-bottom: 1px solid var(--border);
}

.ai-results-header span {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.ai-results-header strong {
    color: var(--accent);
}

/* ===== NAV ICON BADGES ===== */
.nav-icons-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-right: 12px;
}

.nav-icon-badge {
    position: relative;
    text-decoration: none;
}

.icon-wrap {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
}

.icon-wrap:hover {
    border-color: var(--border-hover);
    background: var(--bg-hover);
    transform: translateY(-2px);
}

.icon-wrap img {
    width: 20px;
    height: 20px;
    object-fit: contain;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.icon-wrap:hover img {
    opacity: 1;
}

.badge-count {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 9px;
    background: var(--accent);
    color: var(--bg-primary);
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', sans-serif;
}

/* Logout button style */
.nav-logout-btn {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text-secondary);
    padding: 10px 24px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 0.5px;
}

.nav-logout-btn:hover {
    border-color: var(--border-hover);
    color: var(--accent);
}

/* HERO */
.hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 140px 5% 80px; position: relative; background: var(--gradient-hero); overflow: hidden; }
.hero-mesh { position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(var(--accent) 1px, transparent 1px), linear-gradient(90deg, var(--accent) 1px, transparent 1px); background-size: 60px 60px; opacity: var(--mesh-opacity); }
.hero-content { max-width: 1000px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; align-items: center; text-align: center; position: relative; z-index: 1; }
.hero-top { animation: fadeInDown 1s ease-out; margin-bottom: 40px; }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
.hero-tag { display: inline-flex; align-items: center; gap: 8px; color: var(--accent); font-size: 0.75rem; font-weight: 500; margin-bottom: 20px; letter-spacing: 4px; text-transform: uppercase; }
.hero-tag::before, .hero-tag::after { content: ''; width: 30px; height: 1px; background: var(--accent); opacity: 0.5; }
.hero h1 { font-family: 'Cinzel', serif; font-size: 4.5rem; font-weight: 700; line-height: 1.05; margin-bottom: 24px; color: var(--text-primary); letter-spacing: -1px; }
.hero h1 em { color: var(--accent); font-style: normal; }
.hero-desc { font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 40px; line-height: 1.8; max-width: 520px; font-weight: 300; }
.hero-actions { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; }
.btn-main { padding: 16px 32px; background: var(--accent); color: var(--bg-primary); border-radius: 12px; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s; border: none; cursor: pointer; letter-spacing: 0.5px; }
.btn-main:hover { transform: translateY(-3px); box-shadow: 0 12px 40px var(--accent-glow); }
.btn-ghost { padding: 16px 32px; background: transparent; color: var(--text-primary); border-radius: 12px; font-weight: 500; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s; border: 1px solid var(--border); cursor: pointer; }
.btn-ghost:hover { border-color: var(--border-hover); color: var(--accent); }
.hero-logo-stage { position: relative; width: 350px; height: 350px; margin: 0 auto 50px; display: flex; align-items: center; justify-content: center; }
.orbit-ring { position: absolute; border-radius: 50%; border: 1px solid var(--border); pointer-events: none; }
.orbit-ring-1 { width: 280px; height: 280px; animation: orbitSpin 20s linear infinite; }
.orbit-ring-2 { width: 340px; height: 340px; animation: orbitSpin 30s linear infinite reverse; border-style: dashed; }
.orbit-ring-3 { width: 400px; height: 400px; animation: orbitSpin 40s linear infinite; opacity: 0.5; }
@keyframes orbitSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.orbit-dot { position: absolute; width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 10px var(--accent-glow); }
.orbit-ring-1 .orbit-dot { top: -4px; left: 50%; transform: translateX(-50%); }
.orbit-ring-2 .orbit-dot { bottom: -4px; left: 30%; }
.orbit-ring-3 .orbit-dot { top: 50%; right: -4px; transform: translateY(-50%); }
.hero-logo-main { position: relative; z-index: 2; width: 200px; animation: logoFloat 6s ease-in-out infinite; }
.hero-logo-main img { width: 100%; filter: drop-shadow(0 20px 60px var(--accent-glow)); }
@keyframes logoFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
.hero-glow { position: absolute; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%); pointer-events: none; animation: glowPulse 4s ease-in-out infinite; }
@keyframes glowPulse { 0%, 100% { opacity: 0.6; transform: scale(1); } 50% { opacity: 1; transform: scale(1.15); } }
.scroll-hint { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase; animation: bounce 2s ease-in-out infinite; }
.scroll-hint i { font-size: 1rem; color: var(--accent); }
@keyframes bounce { 0%, 100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(8px); } }

/* MARQUEE */
.marquee-strip { background: var(--bg-secondary); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 20px 0; overflow: hidden; position: relative; }
.marquee-content { display: flex; gap: 80px; white-space: nowrap; animation: marquee 30s linear infinite; }
.marquee-item { font-family: 'Cinzel', serif; font-size: 0.9rem; color: var(--text-muted); letter-spacing: 3px; text-transform: uppercase; display: flex; align-items: center; gap: 12px; }
.marquee-item i { color: var(--accent); font-size: 0.6rem; }
@keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

/* CATEGORIES */
.categories { padding: 100px 5%; background: var(--bg-primary); position: relative; }
.section-label { text-align: center; color: var(--accent); font-size: 0.75rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 16px; }
.section-title { font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 600; text-align: center; color: var(--text-primary); margin-bottom: 60px; letter-spacing: -0.5px; }
.hex-grid { max-width: 900px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: center; gap: 16px; }
.hex-item { width: 160px; height: 180px; position: relative; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; transition: all 0.4s ease; }
.hex-item::before { content: ''; position: absolute; inset: 0; background: var(--bg-card); border: 1px solid var(--border); clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); transition: all 0.4s ease; z-index: 0; }
.hex-item:hover::before { background: var(--bg-hover); border-color: var(--border-hover); transform: scale(1.05); }
.hex-item:hover { transform: translateY(-5px); }
.hex-item:hover .hex-icon { color: var(--accent); transform: scale(1.1); }
.hex-icon { font-size: 2.5rem; position: relative; z-index: 1; transition: all 0.3s; }
.hex-name { font-size: 0.8rem; font-weight: 500; color: var(--text-secondary); position: relative; z-index: 1; letter-spacing: 1px; }

/* TIMELINE */
.how-it-works { padding: 100px 5%; background: var(--bg-secondary); position: relative; }
.timeline { max-width: 800px; margin: 0 auto; position: relative; }
.timeline::before { content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 1px; background: linear-gradient(180deg, transparent, var(--accent-dim), transparent); transform: translateX(-50%); }
.timeline-item { display: grid; grid-template-columns: 1fr 80px 1fr; gap: 40px; align-items: center; margin-bottom: 60px; position: relative; }
.timeline-item:last-child { margin-bottom: 0; }
.timeline-item:nth-child(even) .timeline-content { grid-column: 3; text-align: left; }
.timeline-item:nth-child(even) .timeline-visual { grid-column: 1; grid-row: 1; text-align: right; }
.timeline-content h3 { font-family: 'Cinzel', serif; font-size: 1.3rem; font-weight: 600; color: var(--text-primary); margin-bottom: 12px; }
.timeline-content p { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7; font-weight: 300; }
.timeline-visual { font-size: 4rem; }
.timeline-dot { width: 60px; height: 60px; border-radius: 50%; background: var(--bg-card); border: 2px solid var(--accent); display: flex; align-items: center; justify-content: center; font-family: 'Cinzel', serif; font-weight: 700; color: var(--accent); position: relative; z-index: 1; margin: 0 auto; box-shadow: 0 0 30px var(--accent-glow); }

/* FEATURED GALLERY */
.featured { padding: 100px 5%; background: var(--bg-primary); position: relative; }
.gallery-header { max-width: 1200px; margin: 0 auto 50px; display: flex; justify-content: space-between; align-items: flex-end; }
.gallery-header .section-title { margin-bottom: 0; text-align: left; }
.gallery-nav { display: flex; gap: 12px; }
.gallery-nav button { width: 48px; height: 48px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-card); color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; font-size: 1rem; }
.gallery-nav button:hover { border-color: var(--accent); color: var(--accent); }
.gallery-scroll { max-width: 1200px; margin: 0 auto; display: flex; gap: 24px; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 20px; scrollbar-width: none; -ms-overflow-style: none; }
.gallery-scroll::-webkit-scrollbar { display: none; }
.gallery-card { min-width: 280px; scroll-snap-align: start; background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; transition: all 0.4s ease; cursor: pointer; }
.gallery-card:hover { transform: translateY(-8px); border-color: var(--border-hover); box-shadow: 0 20px 50px var(--shadow); }
.gallery-img { width: 100%; height: 200px; background: linear-gradient(135deg, var(--bg-tertiary), var(--bg-hover)); display: flex; align-items: center; justify-content: center; font-size: 3.5rem; border-bottom: 1px solid var(--border); }
.gallery-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}
.gallery-info { padding: 24px; }
.gallery-info h4 { font-family: 'Cinzel', serif; font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
.gallery-info .price { font-size: 1.2rem; font-weight: 700; color: var(--accent); }
.gallery-info .store { font-size: 0.8rem; color: var(--text-muted); margin-top: 8px; display: flex; align-items: center; gap: 6px; }
.gallery-info .add-btn { width: 100%; padding: 12px; margin-top: 16px; border: none; border-radius: 10px; background: var(--bg-tertiary); color: var(--text-secondary); font-weight: 500; font-size: 0.8rem; cursor: pointer; transition: all 0.3s; letter-spacing: 1px; }
.gallery-info .add-btn:hover { background: var(--accent); color: var(--bg-primary); }

/* VAULTS */
.vaults { padding: 100px 5%; background: var(--bg-secondary); position: relative; }
.vaults-grid { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 280px); gap: 20px; }
.vault-card { position: relative; border-radius: 20px; overflow: hidden; cursor: pointer; border: 1px solid var(--border); transition: all 0.4s ease; }
.vault-card:hover { transform: translateY(-5px); border-color: var(--border-hover); box-shadow: 0 20px 50px var(--shadow); }
.vault-card:nth-child(1) { grid-row: span 2; }
.vault-card:nth-child(2) { grid-column: span 2; }
.vault-bg { position: absolute; inset: 0; background: linear-gradient(135deg, var(--bg-tertiary), var(--bg-hover)); display: flex; align-items: center; justify-content: center; font-size: 5rem; }
.vault-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.8) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 28px; }
.vault-name { font-family: 'Cinzel', serif; font-size: 1.2rem; font-weight: 600; color: #fff; margin-bottom: 4px; }
.vault-type { font-size: 0.75rem; color: rgba(255,255,255,0.6); letter-spacing: 2px; text-transform: uppercase; }
.vault-stats { display: flex; gap: 20px; margin-top: 12px; }
.vault-stat { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--accent); }
.vault-stat i { font-size: 0.7rem; }

/* STATS */
.stats { padding: 100px 5%; background: var(--bg-primary); position: relative; }
.stats-grid { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; }
.stat-box { text-align: center; padding: 40px 20px; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--border); transition: all 0.4s ease; position: relative; overflow: hidden; }
.stat-box:hover { border-color: var(--border-hover); transform: translateY(-5px); }
.stat-box::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent, var(--accent), transparent); opacity: 0; transition: opacity 0.3s; }
.stat-box:hover::before { opacity: 1; }
.stat-icon { font-size: 2rem; margin-bottom: 16px; opacity: 0.7; }
.stat-number { font-family: 'Cinzel', serif; font-size: 2.6rem; font-weight: 700; color: var(--accent); line-height: 1; margin-bottom: 8px; }
.stat-label { font-size: 0.75rem; color: var(--text-muted); letter-spacing: 2px; text-transform: uppercase; }

/* CTA */
.cta { padding: 120px 5%; background: var(--bg-secondary); text-align: center; position: relative; display: flex; align-items: center; justify-content: center; }
.cta-ring { position: absolute; width: 400px; height: 400px; border-radius: 50%; border: 1px solid var(--border); pointer-events: none; animation: ringPulse 4s ease-in-out infinite; }
.cta-ring:nth-child(2) { width: 300px; height: 300px; animation-delay: 0.5s; }
.cta-ring:nth-child(3) { width: 200px; height: 200px; animation-delay: 1s; }
@keyframes ringPulse { 0%, 100% { opacity: 0.3; transform: scale(1); } 50% { opacity: 0.6; transform: scale(1.05); } }
.cta-content { position: relative; z-index: 1; max-width: 600px; }
.cta-content h2 { font-family: 'Cinzel', serif; font-size: 2.8rem; font-weight: 600; margin-bottom: 20px; color: var(--text-primary); }
.cta-content p { color: var(--text-secondary); font-size: 1rem; margin-bottom: 40px; line-height: 1.8; font-weight: 300; }
.cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

/* FOOTER */
.footer { padding: 60px 5% 30px; background: var(--bg-primary); border-top: 1px solid var(--border); }
.footer-content { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 40px; }
.footer-brand { max-width: 300px; }
.footer-brand .logo-text { font-family: 'Cinzel', serif; font-size: 1.6rem; color: var(--text-primary); margin-bottom: 16px; display: block; }
.footer-brand p { color: var(--text-muted); font-size: 0.85rem; line-height: 1.8; font-weight: 300; }
.footer-links h4 { font-size: 0.7rem; font-weight: 600; color: var(--accent); margin-bottom: 20px; letter-spacing: 3px; text-transform: uppercase; }
.footer-links ul { list-style: none; }
.footer-links li { margin-bottom: 10px; }
.footer-links a { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: color 0.3s; font-weight: 300; }
.footer-links a:hover { color: var(--accent); }
.footer-bottom { max-width: 1100px; margin: 40px auto 0; padding-top: 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
.footer-bottom p { color: var(--text-muted); font-size: 0.8rem; font-weight: 300; }
.footer-tagline { font-family: 'Cinzel', serif; font-size: 0.75rem; color: var(--accent); letter-spacing: 3px; }

/* ANIMATIONS */
.reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
.reveal.active { opacity: 1; transform: translateY(0); }


.ai-search-result {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}
/* Mobile responsive */
@media (max-width: 1024px) {
  .hero h1 { font-size: 3.2rem; }
  .hero-logo-stage { width: 280px; height: 280px; }
  .hero-logo-main { width: 160px; }
  .orbit-ring-1 { width: 220px; height: 220px; }
  .orbit-ring-2 { width: 260px; height: 260px; }
  .orbit-ring-3 { width: 300px; height: 300px; }
  .timeline::before { left: 30px; }
  .timeline-item { grid-template-columns: 60px 1fr; gap: 20px; }
  .timeline-item:nth-child(even) .timeline-content { grid-column: 2; text-align: left; }
  .timeline-item:nth-child(even) .timeline-visual { grid-column: 2; grid-row: auto; text-align: left; }
  .timeline-dot { margin: 0; }
  .vaults-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
  .vault-card:nth-child(1), .vault-card:nth-child(2) { grid-row: auto; grid-column: auto; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-content { flex-direction: column; align-items: center; text-align: center; }
  .navbar { width: calc(100% - 30px); padding: 12px 20px; }
  .nav-center { display: none; }
  .nav-search-wrapper {
      display: none;
  }
  .nav-icons-group {
      margin-right: 8px;
  }
  .icon-wrap {
      width: 36px;
      height: 36px;
  }
  .icon-wrap img {
      width: 18px;
      height: 18px;
  }
}

@media (max-width: 768px) {
  .hero h1 { font-size: 2.4rem; }
  .hero-logo-stage { width: 220px; height: 220px; }
  .hero-logo-main { width: 130px; }
  .orbit-ring-1 { width: 180px; height: 180px; }
  .orbit-ring-2 { width: 210px; height: 210px; }
  .orbit-ring-3 { display: none; }
  .section-title { font-size: 1.8rem; }
  .stats-grid { grid-template-columns: 1fr; }
  .gallery-header { flex-direction: column; align-items: flex-start; gap: 20px; }
  .cta-content h2 { font-size: 2rem; }
  .hex-item { width: 120px; height: 140px; }
}
</style>
</head>
<body>
<!-- NAVBAR -->
<nav class="navbar" id="navbar">
<a href="{{ route('home') }}" class="logo">
  <div class="logo-icon">
    <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove">
  </div>
  <span class="logo-text">The<span>Trove</span></span>
</a>

<!-- NAV CENTER -->
@if(request()->routeIs('admin.*'))
    {{-- ADMIN NAV --}}
    <ul class="nav-center">
        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li><a href="{{ route('admin.categories') }}">Categories</a></li>
        <li><a href="{{ route('admin.boutiques') }}">Vaults</a></li>
        <li><a href="{{ route('admin.clients') }}">Clients</a></li>
        <li><a href="{{ route('admin.produits') }}">Items</a></li>
        <li><a href="{{ route('admin.delivery_companies') }}">Delivery</a></li>
    </ul>

@elseif(request()->routeIs('boutique.*'))
    {{-- BOUTIQUE NAV --}}
    <ul class="nav-center">
        <li><a href="{{ route('boutique.dashboard') }}">Home</a></li>
        <li><a href="{{ route('boutique.categories') }}">Categories</a></li>
        <li><a href="{{ route('boutique.produits') }}">Items</a></li>
        <li><a href="{{ route('boutique.commandes') }}">Orders</a></li>
    </ul>

@elseif(request()->routeIs('client.*'))
    {{-- CLIENT NAV: Search Bar + AI Image Search + Icon Badges --}}
    <div class="nav-search-wrapper">
        <div class="search-container">
            <form class="search-form" action="{{ route('client.search') }}" method="GET" id="searchForm">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input 
                        type="text" 
                        name="q" 
                        class="search-input" 
                        placeholder="Search products, vaults, categories..." 
                        id="searchInput"
                        autocomplete="off"
                    >

                    {{-- AI Image Search Button --}}
                    <button type="button" class="ai-search-btn" id="aiSearchBtn" title="Search by image (like SHEIN)">
                        <i class="fas fa-camera"></i>
                        <input type="file" id="aiImageInput" accept="image/*" capture="environment">
                    </button>
                </div>

                {{-- Search Filters --}}
                <div class="search-filters">
                    <label class="filter-chip">
                        <input type="radio" name="type" value="all" checked>
                        <span>All</span>
                    </label>
                    <label class="filter-chip">
                        <input type="radio" name="type" value="products">
                        <span>Products</span>
                    </label>
                    <label class="filter-chip">
                        <input type="radio" name="type" value="boutiques">
                        <span>Vaults</span>
                    </label>
                    <label class="filter-chip">
                        <input type="radio" name="type" value="categories">
                        <span>Categories</span>
                    </label>
                </div>
            </form>

            {{-- Search Dropdown Results --}}
            <div class="search-dropdown" id="searchDropdown">
                {{-- Regular Search Results --}}
                <div class="search-regular-results" id="regularResults"></div>

                {{-- AI Search Results --}}
                <div class="ai-search-results" id="aiResults">
                    <div class="ai-results-header">
                        <img src="" alt="Uploaded" class="ai-preview-img" id="aiPreviewImg">
                        <div>
                            <span>Visual matches for your image</span>
                            <br>
                            <strong id="aiMatchCount">0 similar items found</strong>
                        </div>
                    </div>
                    <div id="aiResultsList"></div>
                </div>

                <div class="search-loading" id="searchLoading">
                    <i class="fas fa-spinner fa-spin"></i> Searching...
                </div>
            </div>
        </div>
    </div>

@elseif(request()->routeIs('delivery.*'))
    <ul class="nav-center">
        <li><a href="{{ route('delivery.dashboard') }}">Home</a></li>
        <li><a href="{{ route('delivery.orders') }}">Orders</a></li>
        <li><a href="{{ route('delivery.call_history') }}" > Calls</a></li>
        <li><a href="{{ route('delivery.order_history') }}">History</a></li> 
    </ul>   

@else
    {{-- MAIN SITE NAV --}}
    <ul class="nav-center">
        <li><a href="{{ route('home') }}">Home</a></li>
        @if(!request()->routeIs('choice') && !request()->routeIs('login') && !request()->routeIs('register'))
            <li><a href="#categories">Categories</a></li>
            <li><a href="#how">How It Works</a></li>
            <li><a href="#featured">Featured</a></li>
            <li><a href="#vaults">Vaults</a></li>
        @endif
    </ul>
@endif

{{-- NAV RIGHT: Icon Badges (Client) + Theme Toggle + Auth --}}
<div class="nav-right">

    @auth
        @if(request()->routeIs('client.*'))
            {{-- CLIENT ICON BADGES: Wishlist, Basket, Orders --}}
            <div class="nav-icons-group">
                {{-- Wishlist --}}
                <a href="{{ route('client.favoris') }}" class="nav-icon-badge" title="Wishlist">
                    <div class="icon-wrap">
                        <img src="{{ asset('images/wishmini.png') }}" alt="wishlist">
                        @if(($wishlist_count ?? 0) > 0)
                            <span class="badge-count">{{ $wishlist_count }}</span>
                        @endif
                    </div>
                </a>

                {{-- Basket --}}
                <a href="{{ route('client.panier') }}" class="nav-icon-badge" title="Basket">
                    <div class="icon-wrap">
                        <img src="{{ asset('images/paniermini1.png') }}" alt="basket">
                        @if(($basket_count ?? 0) > 0)
                            <span class="badge-count">{{ $basket_count }}</span>
                        @endif
                    </div>
                </a>

                {{-- Orders --}}
                <a href="{{ route('client.commandes') }}" class="nav-icon-badge" title="Orders">
                    <div class="icon-wrap">
                        <img src="{{ asset('images/ordermini1.png') }}" alt="orders">
                        @if(($orders_count ?? 0) > 0)
                            <span class="badge-count">{{ $orders_count }}</span>
                        @endif
                    </div>
                </a>
            </div>
        @endif
    @endauth

    <button class="theme-toggle" id="themeToggle" title="Toggle theme">
        <i class="fas fa-sun" id="themeIcon"></i>
    </button>

    @if(Auth::guard('delivery')->check())
    {{-- Delivery Company Logged In --}}
    <form action="{{ route('delivery.logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="nav-logout-btn">
            <i class="fas fa-sign-out-alt" style="margin-right: 6px;"></i> Logout
        </button>
    </form>
@elseif(auth()->check())
    {{-- Regular User Logged In --}}
    @if(auth()->user()->status === 'active')
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="nav-logout-btn">
                <i class="fas fa-sign-out-alt" style="margin-right: 6px;"></i> Logout
            </button>
        </form>
    @else
        @if(!request()->routeIs('choice') && !request()->routeIs('login') && !request()->routeIs('register'))
            <a href="{{ route('choice') }}" class="nav-cta">Get Started</a>
        @endif
    @endif
@else
    {{-- Guest --}}
    @if(!request()->routeIs('choice') && !request()->routeIs('login') && !request()->routeIs('register'))
        <a href="{{ route('choice') }}" class="nav-cta">Get Started</a>
    @endif
@endif
</div>
</nav>

<!-- MAIN CONTENT -->
@yield('content')

<!-- FOOTER -->
<footer class="footer">
<div class="footer-content">
  <div class="footer-brand">
    <span class="logo-text">The<span style="color:var(--accent)">Trove</span></span>
    <p>The marketplace where treasure hunters discover amazing finds and treasure keepers build thriving vaults.</p>
  </div>
  <div class="footer-links">
    <h4>For Hunters</h4>
    <ul><li><a href="#">Browse Treasures</a></li><li><a href="#">Top Deals</a></li><li><a href="#">Track Orders</a></li><li><a href="#">Protection</a></li></ul>
  </div>
  <div class="footer-links">
    <h4>For Keepers</h4>
    <ul><li><a href="#">Open a Vault</a></li><li><a href="#">Dashboard</a></li><li><a href="#">Shipping</a></li><li><a href="#">Fees</a></li></ul>
  </div>
  <div class="footer-links">
    <h4>Company</h4>
    <ul><li><a href="#">About</a></li><li><a href="#">Careers</a></li><li><a href="#">Press</a></li><li><a href="#">Blog</a></li></ul>
  </div>
  <div class="footer-links">
    <h4>Support</h4>
    <ul><li><a href="#">Help Center</a></li><li><a href="#">Contact</a></li><li><a href="#">Privacy</a></li><li><a href="#">Terms</a></li></ul>
  </div>
</div>
<div class="footer-bottom">
  <p>&copy; 2026 TheTrove. All rights reserved.</p>
  <div class="footer-tagline">Find Your Treasure. Be the Treasure.</div>
</div>
</footer>

<script>
// ========== THEME TOGGLE ==========
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const html = document.documentElement;
function updateThemeIcon() {
  const isDark = html.getAttribute('data-theme') === 'dark';
  themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
}
themeToggle.addEventListener('click', () => {
  const current = html.getAttribute('data-theme');
  const next = current === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', next);
  updateThemeIcon();
  localStorage.setItem('thetrove-theme', next);
});
const savedTheme = localStorage.getItem('thetrove-theme');
if (savedTheme) {
  html.setAttribute('data-theme', savedTheme);
  updateThemeIcon();
}

// ========== NAVBAR SCROLL ==========
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.style.top = '10px';
    navbar.style.backdropFilter = 'blur(60px) saturate(1.8)';
  } else {
    navbar.style.top = '20px';
    navbar.style.backdropFilter = 'blur(40px) saturate(1.5)';
  }
});

// ========== CLIENT SEARCH & AI IMAGE SEARCH ==========
@if(request()->routeIs('client.*'))
(function() {
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    const searchLoading = document.getElementById('searchLoading');
    const regularResults = document.getElementById('regularResults');
    const aiResults = document.getElementById('aiResults');
    const aiResultsList = document.getElementById('aiResultsList');
    const aiPreviewImg = document.getElementById('aiPreviewImg');
    const aiMatchCount = document.getElementById('aiMatchCount');
    const aiSearchBtn = document.getElementById('aiSearchBtn');
    const aiImageInput = document.getElementById('aiImageInput');
    const searchForm = document.getElementById('searchForm');

    let searchTimeout;
    let isAiSearch = false;

    // --- Text Search (Live AJAX) ---
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            searchDropdown.classList.remove('active');
            return;
        }

        searchTimeout = setTimeout(() => {
            performTextSearch(query);
        }, 300);
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container')) {
            searchDropdown.classList.remove('active');
        }
    });

    // Show dropdown on focus if has value
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            searchDropdown.classList.add('active');
        }
    });

    // --- AI Image Search ---
    aiImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Show preview
        const reader = new FileReader();
        reader.onload = function(event) {
            aiPreviewImg.src = event.target.result;
            performAiSearch(file, event.target.result);
        };
        reader.readAsDataURL(file);
    });

    aiSearchBtn.addEventListener('click', function() {
        aiImageInput.click();
    });

    // --- Perform Text Search ---
    function performTextSearch(query) {
        isAiSearch = false;
        searchLoading.classList.add('active');
        regularResults.innerHTML = '';
        aiResults.classList.remove('active');
        searchDropdown.classList.add('active');

        const type = document.querySelector('input[name="type"]:checked').value;

        fetch(`{{ route('client.search.ajax') }}?q=${encodeURIComponent(query)}&type=${type}`)
            .then(res => res.json())
            .then(data => {
                searchLoading.classList.remove('active');
                renderTextResults(data, query);
            })
            .catch(err => {
                searchLoading.classList.remove('active');
                regularResults.innerHTML = '<div class="search-result-item"><span style="color:var(--text-muted);font-size:0.85rem;">Error searching. Please try again.</span></div>';
            });
    }

    // --- Perform AI Image Search ---
    function performAiSearch(file, previewUrl) {
        isAiSearch = true;
        aiSearchBtn.classList.add('processing');
        searchLoading.classList.add('active');
        regularResults.innerHTML = '';
        aiResultsList.innerHTML = '';
        aiResults.classList.add('active');
        searchDropdown.classList.add('active');

        const formData = new FormData();
        formData.append('image', file);

        fetch('{{ route('client.search.ai') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            aiSearchBtn.classList.remove('processing');
            searchLoading.classList.remove('active');
            renderAiResults(data);
        })
        .catch(err => {
            aiSearchBtn.classList.remove('processing');
            searchLoading.classList.remove('active');
            aiResultsList.innerHTML = '<div class="search-result-item"><span style="color:var(--text-muted);font-size:0.85rem;">AI search failed. Please try again.</span></div>';
        });
    }

    // --- Render Text Search Results ---
    function renderTextResults(data, query) {
        let html = '';

        // Products section
        // Products section
if (data.products && data.products.length > 0) {
    html += '<div class="search-section-title">Products</div>';
    data.products.forEach(item => {
        html += `
            <div class="search-result-item" onclick="openProductModal(${item.id}); document.getElementById('searchDropdown').classList.remove('active');" style="cursor:pointer;">
                <img src="${item.image}" class="search-result-img" alt="" onerror="this.src='{{ asset('images/placeholder.png') }}'">
                <div class="search-result-info">
                    <div class="search-result-title">${highlightText(item.name, query)}</div>
                    <div class="search-result-meta">${item.boutique_name || ''}</div>
                </div>
                <div class="search-result-price">${item.price}DT</div>
            </div>
        `;
    });
}

        // Boutiques section
        if (data.boutiques && data.boutiques.length > 0) {
            html += '<div class="search-section-title">Vaults</div>';
            data.boutiques.forEach(item => {
                html += `
                    <a href="${item.url}" class="search-result-item">
                        <img src="{{ asset('images/boutiquemini.png') }}" class="search-result-img" alt="">
                        <div class="search-result-info">
                            <div class="search-result-title">${highlightText(item.name, query)}</div>
                            <div class="search-result-meta">${item.category || 'Vault'}</div>
                        </div>
                    </a>
                `;
            });
        }

        // Categories section
        if (data.categories && data.categories.length > 0) {
            html += '<div class="search-section-title">Categories</div>';
            data.categories.forEach(item => {
                html += `
                    <a href="${item.url}" class="search-result-item">
                        <div class="icon-wrap" style="width:40px;height:40px;">
                            <img src="${item.image || '{{ asset('images/placeholder.png') }}'}" >
                        </div>
                        <div class="search-result-info">
                            <div class="search-result-title">${highlightText(item.name, query)}</div>
                            <div class="search-result-meta">${item.product_count || 0} products</div>
                        </div>
                    </a>
                `;
            });
        }

        if (html === '') {
            html = '<div class="search-result-item"><span style="color:var(--text-muted);font-size:0.85rem;">No results found for "' + escapeHtml(query) + '"</span></div>';
        }

        regularResults.innerHTML = html;
    }

    // --- Render AI Image Search Results ---
    function renderAiResults(data) {
        aiMatchCount.textContent = `${data.matches ? data.matches.length : 0} similar items found`;

        let html = '';

        if (data.matches && data.matches.length > 0) {
            data.matches.forEach(item => {
                html += `
                    <a href="javascript:void(0)" onclick="openProductModal(${item.id}); closeAISearchDropdown();" class="ai-search-result">
                        <img src="${item.image || '{{ asset('images/placeholder.png') }}'}" class="search-result-img" alt="">
                        <div class="search-result-info">
                            <div class="search-result-title">
                                ${item.name}
                            </div>
                            <div class="search-result-meta">${item.boutique_name || ''}</div>
                        </div>
                        <div class="search-result-price">${item.price}DT</div>
                    </a>
                `;
            });
        } else {
            html = '<div class="search-result-item"><span style="color:var(--text-muted);font-size:0.85rem;">No visually similar items found. Try another image.</span></div>';
        }

        aiResultsList.innerHTML = html;
    }

    // --- Helpers ---
    function highlightText(text, query) {
        if (!text || !query) return text;
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return text.replace(regex, '<mark style="background:var(--accent-glow);color:var(--accent);border-radius:2px;padding:0 2px;">$1</mark>');
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Submit form on Enter
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

})();
@endif

// ========== SCROLL REVEAL ==========
const revealElements = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
}, { threshold: 0.1 });
revealElements.forEach(el => revealObserver.observe(el));

// ========== ANIMATED COUNTERS ==========
const statNumbers = document.querySelectorAll('.stat-number');
const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const target = parseInt(entry.target.getAttribute('data-target'));
      let current = 0;
      const increment = target / 50;
      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          entry.target.textContent = target + (target === 98 ? '%' : target === 24 ? 'h' : 'K+');
          clearInterval(timer);
        } else {
          entry.target.textContent = Math.floor(current) + (target === 98 ? '%' : target === 24 ? 'h' : 'K+');
        }
      }, 30);
      counterObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });
statNumbers.forEach(stat => counterObserver.observe(stat));

// ========== SMOOTH SCROLL ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});
</script>
</body>
</html>