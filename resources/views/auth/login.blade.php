<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sign in — Darzi Shop</title>
    <link rel="icon" href="{{ asset('admin-assets/img/logo.png') }}" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1f1d1a;
            --navy: #2c2a26;
            --navy-2: #3f3b35;
            --sand: #c2a15a;
            --sand-soft: #f5f0e6;
            --mist: #f5f4f1;
            --text: #2c2a26;
            --muted: #6f6a63;
            --line: #e6e3dc;
            --danger: #b42318;
            --white: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            min-height: 100%;
            font-family: "Outfit", system-ui, sans-serif;
            color: var(--text);
            background: var(--ink);
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
        }

        .brand-plane {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse 80% 60% at 20% 20%, rgba(194, 161, 90, 0.2), transparent 55%),
                radial-gradient(ellipse 70% 50% at 85% 75%, rgba(63, 59, 53, 0.45), transparent 50%),
                linear-gradient(155deg, #141210 0%, #2c2a26 48%, #1f1d1a 100%);
            color: var(--white);
            padding: clamp(32px, 5vw, 64px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            animation: planeIn 0.9s ease both;
        }

        .brand-plane::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 75% 70% at 40% 45%, #000 20%, transparent 75%);
            pointer-events: none;
            animation: gridDrift 18s linear infinite;
        }

        .brand-plane::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -120px;
            bottom: -140px;
            border: 1px solid rgba(194, 161, 90, 0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: orbitPulse 8s ease-in-out infinite;
        }

        .brand-top,
        .brand-main,
        .brand-foot {
            position: relative;
            z-index: 1;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            filter: drop-shadow(0 8px 18px rgba(0,0,0,0.35));
        }

        .brand-mark span {
            font-size: 13px;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--sand);
            font-weight: 500;
        }

        .brand-main {
            max-width: 560px;
            padding: 40px 0;
            animation: riseIn 1s 0.15s ease both;
        }

        .brand-main h1 {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-weight: 700;
            font-size: clamp(48px, 7vw, 84px);
            line-height: 0.92;
            letter-spacing: 0.02em;
            margin-bottom: 22px;
        }

        .brand-main h1 em {
            font-style: normal;
            color: var(--sand);
            display: block;
        }

        .brand-main p {
            font-size: 17px;
            line-height: 1.65;
            color: rgba(255,255,255,0.72);
            max-width: 420px;
            font-weight: 300;
        }

        .brand-foot {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
        }

        .brand-foot strong {
            color: rgba(255,255,255,0.78);
            font-weight: 500;
        }

        .form-plane {
            background: var(--mist);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(28px, 4vw, 48px);
            position: relative;
            animation: formIn 0.85s 0.1s ease both;
        }

        .form-plane::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 90% 10%, rgba(44, 42, 38, 0.07), transparent 40%),
                radial-gradient(circle at 10% 90%, rgba(194, 161, 90, 0.14), transparent 35%);
            pointer-events: none;
        }

        .form-panel {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .form-kicker {
            font-size: 12px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--navy-2);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-panel h2 {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 42px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
            margin-bottom: 10px;
        }

        .form-panel > p {
            color: var(--muted);
            font-size: 15px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .field { margin-bottom: 18px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .field-wrap { position: relative; }

        .field-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #8a97a8;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            height: 52px;
            border: 1px solid var(--line);
            background: var(--white);
            border-radius: 10px;
            padding: 0 16px 0 44px;
            font-family: inherit;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field input::placeholder { color: #9aa6b5; }

        .field input:focus {
            border-color: var(--sand);
            box-shadow: 0 0 0 4px rgba(194, 161, 90, 0.16);
        }

        .field input.is-invalid { border-color: var(--danger); }

        .invalid-feedback {
            display: block;
            margin-top: 7px;
            color: var(--danger);
            font-size: 13px;
        }

        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 8px 0 26px;
            font-size: 13px;
            color: var(--muted);
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--navy);
        }

        .submit-btn {
            width: 100%;
            height: 54px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            color: var(--white);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            box-shadow: 0 12px 28px rgba(44, 42, 38, 0.28);
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 16px 32px rgba(44, 42, 38, 0.34);
        }

        .submit-btn:active { transform: translateY(0); }

        .form-note {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.04em;
        }

        @keyframes planeIn {
            from { opacity: 0; transform: translateX(-18px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes formIn {
            from { opacity: 0; transform: translateX(18px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes riseIn {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gridDrift {
            from { transform: translateY(0); }
            to { transform: translateY(56px); }
        }

        @keyframes orbitPulse {
            0%, 100% { transform: scale(1); opacity: 0.55; }
            50% { transform: scale(1.06); opacity: 0.9; }
        }

        @media (max-width: 960px) {
            .login-shell { grid-template-columns: 1fr; }
            .brand-plane { min-height: 42vh; padding: 28px 24px; }
            .brand-main { padding: 24px 0 12px; }
            .brand-main h1 { font-size: clamp(40px, 12vw, 56px); }
            .brand-main p { font-size: 15px; }
            .brand-foot { display: none; }
            .form-plane { min-height: 58vh; align-items: flex-start; padding-top: 36px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <aside class="brand-plane" aria-label="Darzi Shop">
            <div class="brand-top">
                <div class="brand-mark">
                    <img src="{{ asset('admin-assets/img/logo.png') }}" alt="Darzi Shop logo">
                    <span>Est. Shop</span>
                </div>
            </div>

            <div class="brand-main">
                <h1>DARZI <em>SHOP</em></h1>
                <p>Secure access to sales, stocks, customers, and shop operations — one place for your daily business.</p>
            </div>

            <div class="brand-foot">
                <div><strong>Retail</strong> · Inventory</div>
                <div><strong>Sales</strong> · Ledger</div>
            </div>
        </aside>

        <main class="form-plane">
            <div class="form-panel">
                <div class="form-kicker">Welcome back</div>
                <h2>Sign in</h2>
                <p>Enter your credentials to continue to the admin panel.</p>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="field-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 6h16v12H4z"></path>
                                <path d="m4 7 8 6 8-6"></path>
                            </svg>
                            <input
                                id="email"
                                type="email"
                                class="@error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                                placeholder="Enter your email"
                            >
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="field-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                            </svg>
                            <input
                                id="password"
                                type="password"
                                class="@error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            >
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-meta">
                        <label class="remember">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="submit-btn">Sign in</button>
                </form>

                <p class="form-note">© {{ date('Y') }} Darzi Shop · Confidential access</p>
            </div>
        </main>
    </div>
</body>
</html>
