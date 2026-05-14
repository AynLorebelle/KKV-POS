<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKV POS — Next Generation Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --yellow:     #F5C400;
            --yellow-dim: #D4A900;
            --black:      #0A0A0A;
            --white:      #FAFAF8;
            --tan:        #E8DCC8;
            --tan-dark:   #C8B898;
            --gray:       #6B6560;
            --card-bg:    #FFFFFF;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--black);
            overflow-x: hidden;
            cursor: none;
        }

        /* ── CUSTOM CURSOR ── */
        .cursor {
            width: 12px; height: 12px;
            background: var(--yellow);
            border-radius: 50%;
            position: fixed; top: 0; left: 0;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.15s ease;
            mix-blend-mode: multiply;
        }
        .cursor-ring {
            width: 36px; height: 36px;
            border: 2px solid var(--black);
            border-radius: 50%;
            position: fixed; top: 0; left: 0;
            pointer-events: none;
            z-index: 9998;
            transition: transform 0.4s cubic-bezier(.25,.46,.45,.94);
        }
        body:hover .cursor { transform: translate(-50%, -50%); }

        /* ── NAVBAR ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 48px;
            background: rgba(250,250,248,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            animation: slideDown 0.6s ease both;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to   { transform: translateY(0);     opacity: 1; }
        }
        .nav-logo {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none;
        }
        .nav-logo-box {
            width: 44px; height: 44px;
            background: var(--yellow);
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--black);
        }
        .nav-logo-box span {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: var(--black);
            letter-spacing: 1px;
        }
        .nav-tagline {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
        }
        .nav-links {
            display: flex; align-items: center; gap: 8px;
        }
        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 2px;
            transition: all 0.2s;
        }
        .btn-ghost {
            color: var(--black);
            border: 1.5px solid transparent;
        }
        .btn-ghost:hover { border-color: var(--black); }
        .btn-solid {
            background: var(--yellow);
            color: var(--black);
            border: 1.5px solid var(--black);
            font-weight: 600;
        }
        .btn-solid:hover { background: var(--black); color: var(--yellow); }

        /* ── HERO ── */
        .hero {
            height: 100vh;
            min-height: 580px;
            max-height: 860px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr; /* Slightly wider left side for better balance */
            align-items: center;
            
            /* ADD THESE THREE LINES */
            max-width: 1300px; 
            margin: 0 auto;
            padding: 80px 40px 40px; 
            
            position: relative;
            overflow: visible; /* Changed from hidden so badges/orbs can breathe */
            gap: 60px; /* Increased gap to make the space feel intentional, not accidental */
        }

        /* Animated background */
        .hero-bg {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 50%, rgba(245,196,0,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 10% 80%, rgba(245,196,0,0.10) 0%, transparent 50%);
        }
        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 300px; height: 300px;
            background: rgba(245,196,0,0.25);
            top: 10%; right: 10%;
            animation-delay: 0s;
        }
        .orb-2 {
            width: 200px; height: 200px;
            background: rgba(245,196,0,0.15);
            bottom: 20%; left: 5%;
            animation-delay: -3s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* Hero Text */
        .hero-text { position: relative; z-index: 2; }
        .hero-label {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--black);
            color: var(--yellow);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 5px 12px;
            margin-bottom: 16px;
            animation: fadeUp 0.8s 0.2s ease both;
        }
        .hero-label::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--yellow);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }
        .hero-title {
        font-family: 'Bebas Neue', sans-serif;
        /* Increased from 88px to 110px for the upper limit */
        font-size: clamp(50px, 8vw, 110px); 
        line-height: 0.85; /* Tighter line height makes it look more "editorial" */
        letter-spacing: -1px; /* Tighter tracking for large titles */
        margin-bottom: 12px;
        animation: fadeUp 0.8s 0.35s ease both;
    }

        .hero-title .accent {
            color: var(--yellow);
            -webkit-text-stroke: 2px var(--black);
            display: block;
        }
        .hero-title .stroke {
            -webkit-text-stroke: 2px var(--black);
            color: transparent;
            display: block;
        }
        .hero-sub {
            font-size: 20px; /* Increased from 14px */
            line-height: 1.6;
            color: var(--gray);
            max-width: 550px; /* Wider to fill horizontal space */
            margin: 24px 0 32px;
            font-weight: 400; /* Slightly heavier for better legibility at larger sizes */
        }
        .hero-cta {
            display: flex; align-items: center; gap: 16px;
            flex-wrap: wrap;
            animation: fadeUp 0.8s 0.65s ease both;
        }
        .cta-primary {
            display: inline-flex; align-items: center; gap: 10px;
            background: var(--yellow);
            color: var(--black);
            text-decoration: none;
            padding: 13px 26px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 2px solid var(--black);
            box-shadow: 4px 4px 0 var(--black);
            transition: all 0.2s;
        }
        .cta-primary:hover {
            background: var(--black);
            color: var(--yellow);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--yellow-dim);
        }
        .cta-primary svg { transition: transform 0.2s; }
        .cta-primary:hover svg { transform: translateX(4px); }
        .cta-demo {
            font-size: 13px;
            color: var(--gray);
            font-family: 'DM Mono', monospace;
        }
        .cta-demo strong { color: var(--black); }

        /* Hero Stats */
        .hero-stats {
            display: flex; gap: 28px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid rgba(0,0,0,0.08);
            animation: fadeUp 0.8s 0.8s ease both;
        }
        .stat-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 30px;
            letter-spacing: 1px;
            color: var(--black);
            line-height: 1;
        }
        .stat-label {
            font-size: 15px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 3px;
        }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: flex-end; /* Align to the right of the constrained container */
            align-items: center;
            padding: 32px 0; /* Removed large right padding */
            animation: fadeUp 0.8s 0.4s ease both;
        }

        .receipt-stack {
            position: relative;
            width: 320px; /* Increased from 240px */
            transform: rotate(2deg); /* Added a slight tilt for more "character" */
        }
        .receipt-card {
            background: var(--card-bg);
            border: 2px solid var(--black);
            padding: 20px 18px;
            position: relative;
            box-shadow: 7px 7px 0 var(--black);
            font-family: 'DM Mono', monospace;
            animation: bobUp 4s ease-in-out infinite;
        }
        .receipt-card-shadow {
            position: absolute;
            top: 10px; left: 10px;
            width: 100%; height: 100%;
            background: var(--yellow);
            border: 2px solid var(--black);
            z-index: -1;
        }
        @keyframes bobUp {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50%       { transform: translateY(-12px) rotate(1deg); }
        }
        .receipt-store {
            text-align: center;
            border-bottom: 1px dashed rgba(0,0,0,0.2);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .receipt-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            letter-spacing: 3px;
            line-height: 1;
        }
        .receipt-logo .dot { color: var(--yellow); }
        .receipt-address {
            font-size: 8px;
            color: var(--gray);
            line-height: 1.5;
            margin-top: 4px;
        }
        .receipt-divider {
            border: none;
            border-top: 1px dashed rgba(0,0,0,0.15);
            margin: 8px 0;
        }
        .receipt-invoice-no {
            font-size: 9px;
            font-weight: 600;
            text-align: center;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .receipt-row {
            display: flex; justify-content: space-between;
            font-size: 9.5px;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .receipt-row .item-name { flex: 1; }
        .receipt-row .item-price { font-weight: 600; white-space: nowrap; }
        .receipt-total-row {
            display: flex; justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
        }
        .receipt-footer {
            text-align: center;
            font-size: 8px;
            color: var(--gray);
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed rgba(0,0,0,0.15);
            letter-spacing: 0.5px;
        }
        /* typing animation on amount */
        .type-anim {
            overflow: hidden;
            border-right: 2px solid var(--yellow);
            white-space: nowrap;
            animation: typing 2s steps(6, end) infinite alternate,
                       blink 0.7s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to   { width: 100%; }
        }
        @keyframes blink {
            50% { border-color: transparent; }
        }

        /* Floating badges */
        .badge {
            position: absolute;
            font-size: 10px;
            font-weight: 600;
            padding: 6px 12px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            animation: badgePop 0.5s cubic-bezier(.34,1.56,.64,1) both;
            z-index: 10;
        }
        .badge-vat {
            top: -14px; right: -14px;
            background: var(--yellow);
            color: var(--black);
            border: 2px solid var(--black);
            animation-delay: 1.2s;
        }
        .badge-fast {
            bottom: -14px; left: -14px;
            background: var(--black);
            color: var(--yellow);
            border: 2px solid var(--black);
            animation-delay: 1.5s;
        }
        @keyframes badgePop {
            from { opacity: 0; transform: scale(0.5) rotate(-10deg); }
            to   { opacity: 1; transform: scale(1) rotate(0deg); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── MARQUEE STRIP ── */
        .marquee-strip {
            background: var(--black);
            color: var(--yellow);
            padding: 14px 0;
            overflow: hidden;
            white-space: nowrap;
            border-top: 2px solid var(--yellow);
            border-bottom: 2px solid var(--yellow);
        }
        .marquee-track {
            display: inline-flex;
            animation: marquee 20s linear infinite;
        }
        .marquee-track span {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 3px;
            padding: 0 32px;
        }
        .marquee-track .sep {
            color: rgba(245,196,0,0.3);
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ── FEATURES ── */
        .features {
            padding: 100px 48px;
            background: var(--white);
        }
        .section-header {
            display: flex; align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 60px;
        }
        .section-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(40px, 5vw, 72px);
            letter-spacing: 2px;
            line-height: 0.95;
        }
        .section-title em { font-style: normal; color: var(--yellow); -webkit-text-stroke: 1.5px var(--black); }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            border: 2px solid var(--black);
        }
        .feature-card {
            padding: 40px 32px;
            border-right: 2px solid var(--black);
            position: relative;
            overflow: hidden;
            transition: background 0.3s;
        }
        .feature-card:last-child { border-right: none; }
        .feature-card::before {
            content: '';
            position: absolute; bottom: 0; left: 0;
            width: 100%; height: 0;
            background: var(--yellow);
            transition: height 0.4s cubic-bezier(.25,.46,.45,.94);
            z-index: 0;
        }
        .feature-card:hover::before { height: 100%; }
        .feature-card:hover .feature-icon { border-color: var(--black); }
        .feature-icon {
            width: 52px; height: 52px;
            border: 2px solid var(--black);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            position: relative; z-index: 1;
            background: var(--white);
            transition: border-color 0.3s;
        }
        .feature-icon svg { width: 24px; height: 24px; }
        .feature-num {
            position: absolute; top: 24px; right: 24px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            color: rgba(0,0,0,0.06);
            letter-spacing: 2px;
            z-index: 1;
            transition: color 0.3s;
        }
        .feature-card:hover .feature-num { color: rgba(0,0,0,0.1); }
        .feature-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            position: relative; z-index: 1;
        }
        .feature-desc {
            font-size: 14px;
            line-height: 1.7;
            color: var(--gray);
            position: relative; z-index: 1;
            transition: color 0.3s;
        }
        .feature-card:hover .feature-desc { color: var(--black); }

        /* ── HOW IT WORKS ── */
        .how-it-works {
            padding: 100px 48px;
            background: var(--black);
            color: var(--white);
            position: relative;
            overflow: hidden;
        }
        .how-it-works::before {
            content: 'POS';
            position: absolute;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 320px;
            color: rgba(255,255,255,0.03);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            letter-spacing: 20px;
            pointer-events: none;
        }
        .how-it-works .section-title { color: var(--white); }
        .how-it-works .section-eyebrow { color: var(--yellow); }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 48px;
            margin-top: 60px;
            position: relative; z-index: 1;
        }
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 28px; left: 16%;
            width: 67%; height: 2px;
            background: linear-gradient(90deg, var(--yellow), rgba(245,196,0,0.2));
        }
        .step-item { text-align: center; }
        .step-num-wrap {
            display: flex; justify-content: center;
            margin-bottom: 24px;
        }
        .step-num {
            width: 56px; height: 56px;
            background: var(--yellow);
            border: 2px solid var(--yellow);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: var(--black);
            position: relative; z-index: 1;
        }
        .step-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .step-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }

        /* ── CTA BANNER ── */
        .cta-banner {
            padding: 80px 48px;
            background: var(--yellow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            border-top: 2px solid var(--black);
            border-bottom: 2px solid var(--black);
        }
        .cta-banner-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(36px, 5vw, 72px);
            line-height: 0.95;
            letter-spacing: 2px;
            max-width: 600px;
        }
        .cta-banner-actions { display: flex; gap: 12px; flex-shrink: 0; }
        .btn-black {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--black); color: var(--yellow);
            text-decoration: none;
            padding: 16px 32px;
            font-size: 15px; font-weight: 700;
            border: 2px solid var(--black);
            transition: all 0.2s;
        }
        .btn-black:hover { background: transparent; color: var(--black); }
        .btn-outline-black {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: var(--black);
            text-decoration: none;
            padding: 16px 32px;
            font-size: 15px; font-weight: 700;
            border: 2px solid var(--black);
            transition: all 0.2s;
        }
        .btn-outline-black:hover { background: var(--black); color: var(--yellow); }

        /* ── FOOTER ── */
        footer {
            background: var(--black);
            color: rgba(255,255,255,0.4);
            padding: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 2px solid rgba(255,255,255,0.05);
        }
        .footer-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            color: var(--yellow);
            letter-spacing: 3px;
        }
        .footer-info { font-size: 12px; line-height: 1.8; text-align: center; }
        .footer-info a { color: var(--yellow); text-decoration: none; }
        .footer-credit { font-size: 11px; font-family: 'DM Mono', monospace; text-align: right; }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; padding: 100px 24px 60px; }
            .hero-visual { display: none; }
            nav { padding: 16px 24px; }
            .features-grid, .steps-grid { grid-template-columns: 1fr; }
            .feature-card { border-right: none; border-bottom: 2px solid var(--black); }
            .features, .how-it-works { padding: 60px 24px; }
            .cta-banner { flex-direction: column; padding: 60px 24px; }
            footer { flex-direction: column; gap: 24px; text-align: center; padding: 32px 24px; }
            .section-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        }
    </style>
</head>
<body>

    <!-- Custom Cursor -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <!-- ── NAVBAR ── -->
    <nav>
        <a href="#" class="nav-logo">
            <div class="nav-logo-box"><span>KKV</span></div>
            <span class="nav-tagline"> </span>
        </a>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-links">
                        <span class="btn-ghost" style="padding:10px 20px; border:1.5px solid transparent; font-size:14px; font-weight:500;">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-solid">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <div class="hero-text">
            <div class="hero-label">KKV Retail Corporation</div>
            <h1 class="hero-title">
                <span>NEXT</span>
                <span>GEN</span>
                <span class="accent">CHECK</span>
                <span class="stroke">OUT.</span>
            </h1>
            <p class="hero-sub">
                Streamline your store operations with a blazing-fast POS built for
                KKV Mandaue. Auto VAT, real invoices, zero friction.
            </p>
            <div class="hero-cta">
                <a href="{{ route('login') }}" class="cta-primary">
                    Start Selling
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M4 10h12M10 4l6 6-6 6"/>
                    </svg>
                </a>
                <span class="cta-demo">
                    Demo: <strong>admin@kkv.com</strong> / <strong>password</strong>
                </span>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">12%</div>
                    <div class="stat-label">Auto VAT</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">∞</div>
                    <div class="stat-label">Products</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">1s</div>
                    <div class="stat-label">Checkout Time</div>
                </div>
            </div>
        </div>

        <!-- Receipt Mockup -->
        <div class="hero-visual">
            <div class="receipt-stack">
                <div class="receipt-card-shadow"></div>
                <div class="receipt-card">
                    <div class="badge badge-vat">✓ VAT Computed</div>
                    <div class="badge badge-fast">⚡ Instant Print</div>

                    <div class="receipt-store">
                        <div class="receipt-logo">K<span class="dot">!</span>KV</div>
                        <div class="receipt-address">
                            KKV RETAIL CORPORATION<br>
                            SM CITY J MALL, MANDAUE CEBU<br>
                            VAT REG TIN: 648-350-240-00006
                        </div>
                    </div>

                    <div class="receipt-invoice-no">SALES INVOICE #0000014339</div>
                    <div class="receipt-row">
                        <span>Sold To:</span>
                        <span>01 WALK IN</span>
                    </div>
                    <hr class="receipt-divider">
                    <div class="receipt-row">
                        <span class="item-name" style="font-size:9px;">6942349706271</span>
                    </div>
                    <div class="receipt-row">
                        <span class="item-name">1 Strawberry Softeni</span>
                        <span class="item-price">₱59.00</span>
                    </div>
                    <hr class="receipt-divider">
                    <div class="receipt-row" style="font-weight:700;">
                        <span>TOTAL</span>
                        <span class="type-anim">₱59.00</span>
                    </div>
                    <div class="receipt-row">
                        <span>Cash</span><span>₱100.00</span>
                    </div>
                    <div class="receipt-row">
                        <span>Change</span><span>₱41.00</span>
                    </div>
                    <hr class="receipt-divider">
                    <div class="receipt-row" style="font-size:9px;">
                        <span>VATables</span><span>52.68</span>
                    </div>
                    <div class="receipt-row" style="font-size:9px;">
                        <span>VAT Amount</span><span>6.32</span>
                    </div>
                    <div class="receipt-footer">
                        CASHIER: RENIE MARK ABONG · TRX#15281<br>
                        THIS SERVES AS OFFICIAL SALES INVOICE
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── MARQUEE ── -->
    <div class="marquee-strip">
        <div class="marquee-track">
            <span>FAST CHECKOUT</span><span class="sep">◆</span>
            <span>AUTO VAT CALC</span><span class="sep">◆</span>
            <span>OFFICIAL INVOICE</span><span class="sep">◆</span>
            <span>PRODUCT CRUD</span><span class="sep">◆</span>
            <span>SALES HISTORY</span><span class="sep">◆</span>
            <span>BARCODE SEARCH</span><span class="sep">◆</span>
            <span>FAST CHECKOUT</span><span class="sep">◆</span>
            <span>AUTO VAT CALC</span><span class="sep">◆</span>
            <span>OFFICIAL INVOICE</span><span class="sep">◆</span>
            <span>PRODUCT CRUD</span><span class="sep">◆</span>
            <span>SALES HISTORY</span><span class="sep">◆</span>
            <span>BARCODE SEARCH</span><span class="sep">◆</span>
        </div>
    </div>

    <!-- ── FEATURES ── -->
    <section class="features">
        <div class="section-header reveal">
            <div>
                <div class="section-eyebrow">What's Included</div>
                <h2 class="section-title">BUILT FOR<br><em>REAL</em> STORES</h2>
            </div>
        </div>
        <div class="features-grid reveal">
            <div class="feature-card">
                <div class="feature-num">01</div>
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </div>
                <h3 class="feature-title">Product CRUD</h3>
                <p class="feature-desc">Full product management — add, edit, delete, and organize inventory with category filtering and stock tracking.</p>
            </div>
            <div class="feature-card">
                <div class="feature-num">02</div>
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <h3 class="feature-title">POS Terminal</h3>
                <p class="feature-desc">Split-screen checkout interface with barcode search, cart management, and one-click payment processing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-num">03</div>
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <h3 class="feature-title">Official Invoice</h3>
                <p class="feature-desc">Generates BIR-compliant sales invoices matching KKV's exact receipt format with auto VAT breakdown.</p>
            </div>
        </div>
    </section>

    <!-- ── HOW IT WORKS ── -->
    <section class="how-it-works">
        <div class="section-header reveal">
            <div>
                <div class="section-eyebrow">Workflow</div>
                <h2 class="section-title">HOW IT<br><em>WORKS</em></h2>
            </div>
        </div>
        <div class="steps-grid reveal">
            <div class="step-item">
                <div class="step-num-wrap">
                    <div class="step-num">01</div>
                </div>
                <h3 class="step-title">Scan or Search</h3>
                <p class="step-desc">Find products instantly via barcode scanner or name search. Add to cart with one click.</p>
            </div>
            <div class="step-item">
                <div class="step-num-wrap">
                    <div class="step-num">02</div>
                </div>
                <h3 class="step-title">Checkout</h3>
                <p class="step-desc">Enter cash tendered. The system auto-computes VAT, change, and all totals instantly.</p>
            </div>
            <div class="step-item">
                <div class="step-num-wrap">
                    <div class="step-num">03</div>
                </div>
                <h3 class="step-title">Print Invoice</h3>
                <p class="step-desc">Generate and print the official KKV sales invoice. Reprint anytime from sales history.</p>
            </div>
        </div>
    </section>

    <!-- ── CTA BANNER ── -->
    <section class="cta-banner reveal">
        <h2 class="cta-banner-title">READY TO START SELLING?</h2>
        <div class="cta-banner-actions">
            <a href="{{ route('login') }}" class="btn-black">Log In</a>
            <a href="{{ route('register') }}" class="btn-outline-black">Register</a>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="footer-logo">KKV</div>
        <div class="footer-info">
            KKV Retail Corporation · SM City J Mall, A.S. Fortuna St.<br>
            Bakilid, City of Mandaue, Cebu · VAT REG TIN: 648-350-240-00006<br>
            <a href="#">support@kkv.com</a>
        </div>
        <div class="footer-credit">
            Built with Laravel + Breeze<br>
            © {{ date('Y') }} KKV POS System
        </div>
    </footer>

    <script>
        // Custom cursor
        const cursor = document.getElementById('cursor');
        const ring   = document.getElementById('cursorRing');
        let mx = 0, my = 0, rx = 0, ry = 0;
        document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
        function animCursor() {
            cursor.style.transform = `translate(${mx}px, ${my}px) translate(-50%,-50%)`;
            rx += (mx - rx) * 0.12;
            ry += (my - ry) * 0.12;
            ring.style.transform = `translate(${rx}px, ${ry}px) translate(-50%,-50%)`;
            requestAnimationFrame(animCursor);
        }
        animCursor();
        document.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.style.transform += ' scale(1.8)';
                ring.style.transform   += ' scale(1.5)';
            });
            el.addEventListener('mouseleave', () => { });
        });

        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.12 });
        reveals.forEach(r => obs.observe(r));

        // Navbar scroll effect
        const nav = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            nav.style.background = window.scrollY > 40
                ? 'rgba(10,10,10,0.92)'
                : 'rgba(250,250,248,0.85)';
            nav.style.color = window.scrollY > 40 ? '#FAFAF8' : '';
            nav.querySelectorAll('a').forEach(a => {
                a.style.color = window.scrollY > 40 ? '#FAFAF8' : '';
            });
        });
    </script>
</body>
</html>