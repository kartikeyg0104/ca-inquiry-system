<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — TaxSafar</title>
    <meta name="description" content="Secure admin login portal for TaxSafar CA Inquiry Management System.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #060B18;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        /* ====== LEFT PANEL — FEATURE SHOWCASE ====== */
        .login-showcase {
            flex: 1;
            background: linear-gradient(160deg, #041210 0%, #062E25 30%, #064E3B 60%, #059669 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            overflow: hidden;
        }

        /* Animated mesh grid */
        .mesh-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(16,185,129,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* Floating geometric shapes */
        .geo-shape {
            position: absolute;
            border: 1.5px solid rgba(16,185,129,0.15);
            animation: geoFloat 12s ease-in-out infinite;
        }
        .geo-1 {
            width: 120px; height: 120px;
            border-radius: 24px;
            top: 10%; right: 15%;
            transform: rotate(30deg);
            animation-delay: 0s;
        }
        .geo-2 {
            width: 80px; height: 80px;
            border-radius: 50%;
            bottom: 20%; left: 10%;
            animation-delay: 3s;
        }
        .geo-3 {
            width: 60px; height: 60px;
            border-radius: 12px;
            top: 55%; right: 8%;
            transform: rotate(45deg);
            animation-delay: 6s;
            border-color: rgba(52,211,153,0.12);
        }
        .geo-4 {
            width: 150px; height: 150px;
            border-radius: 50%;
            top: -30px; left: -30px;
            border-color: rgba(16,185,129,0.08);
            animation-delay: 2s;
        }
        .geo-5 {
            width: 40px; height: 40px;
            border-radius: 8px;
            bottom: 35%; right: 25%;
            transform: rotate(15deg);
            animation-delay: 4s;
            background: rgba(16,185,129,0.04);
        }
        @keyframes geoFloat {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 30deg)); opacity: 0.6; }
            50% { transform: translateY(-20px) rotate(calc(var(--rot, 30deg) + 10deg)); opacity: 1; }
        }
        .geo-1 { --rot: 30deg; }
        .geo-3 { --rot: 45deg; }
        .geo-5 { --rot: 15deg; }
        .geo-2, .geo-4 { --rot: 0deg; }

        /* Glowing orb behind content */
        .showcase-glow {
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(5,150,105,0.2), transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            filter: blur(60px);
            animation: glowPulse 6s ease-in-out infinite;
        }
        @keyframes glowPulse {
            0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.15); }
        }

        .showcase-content {
            position: relative; z-index: 2;
            max-width: 480px;
        }

        .showcase-brand {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 3rem;
        }
        .showcase-brand-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.25rem;
            box-shadow: 0 8px 24px rgba(5,150,105,0.35);
        }
        .showcase-brand-text {
            font-size: 1.375rem; font-weight: 800; color: white;
            letter-spacing: -0.02em;
        }
        .showcase-brand-text span { color: #34D399; }

        .showcase-headline {
            font-size: 2.75rem; font-weight: 800; color: white;
            line-height: 1.15; letter-spacing: -0.04em;
            margin-bottom: 1.25rem;
        }
        .showcase-headline .accent {
            background: linear-gradient(135deg, #34D399, #6EE7B7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .showcase-desc {
            font-size: 1.0625rem; color: rgba(255,255,255,0.55);
            line-height: 1.7; margin-bottom: 3rem;
        }

        /* Feature cards carousel */
        .showcase-features {
            display: flex; flex-direction: column; gap: 1rem;
        }
        .feature-card {
            display: flex; align-items: center; gap: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
            cursor: default;
        }
        .feature-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(16,185,129,0.2);
            transform: translateX(6px);
        }
        .feature-card.active {
            background: rgba(16,185,129,0.08);
            border-color: rgba(16,185,129,0.25);
        }
        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.125rem;
            flex-shrink: 0;
        }
        .feature-icon.green { background: rgba(16,185,129,0.12); color: #34D399; }
        .feature-icon.blue { background: rgba(59,130,246,0.12); color: #93C5FD; }
        .feature-icon.purple { background: rgba(139,92,246,0.12); color: #C4B5FD; }
        .feature-title { font-size: 0.875rem; font-weight: 600; color: rgba(255,255,255,0.85); }
        .feature-desc { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 0.125rem; }

        /* Floating stats pills */
        .showcase-stats {
            display: flex; gap: 0.75rem; margin-top: 2rem;
        }
        .stat-pill {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 999px;
            font-size: 0.75rem; font-weight: 600;
            color: rgba(255,255,255,0.7);
        }
        .stat-pill .stat-val { color: #34D399; font-weight: 800; }

        /* ====== RIGHT PANEL — LOGIN FORM ====== */
        .login-panel {
            width: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            background: #0A0F1E;
        }

        /* Subtle noise texture */
        .login-panel::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* Accent glow */
        .login-panel::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(5,150,105,0.08), transparent 70%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 5;
        }

        /* Mobile header (hidden on desktop) */
        .login-mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-mobile-brand .mobile-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 16px;
            display: inline-flex; align-items: center; justify-content: center;
            color: white; font-size: 1.5rem; margin-bottom: 0.75rem;
            box-shadow: 0 8px 24px rgba(5,150,105,0.3);
        }
        .login-mobile-brand h2 {
            font-size: 1.5rem; font-weight: 800; color: white;
        }
        .login-mobile-brand h2 span { color: #10B981; }

        /* Welcome text */
        .login-welcome {
            margin-bottom: 2rem;
        }
        .login-welcome h1 {
            font-size: 1.75rem; font-weight: 800; color: #F8FAFC;
            letter-spacing: -0.03em; margin-bottom: 0.5rem;
        }
        .login-welcome p {
            font-size: 0.9375rem; color: #64748B;
        }

        /* Form Card */
        .login-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(20px);
            position: relative;
        }

        /* Corner accent */
        .login-card::before {
            content: '';
            position: absolute; top: -1px; left: 24px;
            width: 60px; height: 3px;
            background: linear-gradient(90deg, #059669, #10B981, transparent);
            border-radius: 0 0 4px 4px;
        }

        /* Alerts */
        .login-alert {
            padding: 0.875rem 1rem; border-radius: 12px;
            margin-bottom: 1.25rem;
            display: flex; gap: 0.75rem; align-items: center;
            font-size: 0.8125rem; font-weight: 500;
            animation: alertSlide 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        .login-alert-danger {
            background: rgba(239,68,68,0.08);
            color: #FCA5A5;
            border: 1px solid rgba(239,68,68,0.12);
        }
        .login-alert-success {
            background: rgba(16,185,129,0.08);
            color: #6EE7B7;
            border: 1px solid rgba(16,185,129,0.12);
        }
        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Form */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.5rem;
            font-weight: 600; font-size: 0.8125rem;
            color: rgba(255,255,255,0.5);
        }

        .input-wrapper { position: relative; }
        .input-wrapper .input-icon {
            position: absolute; left: 0.875rem; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.2); font-size: 0.875rem;
            pointer-events: none; transition: color 0.3s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.8125rem 1rem 0.8125rem 2.625rem;
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            font-size: 0.9375rem;
            font-family: 'Inter', sans-serif;
            color: #F1F5F9;
            background: rgba(255,255,255,0.03);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            outline: none;
        }
        .input-wrapper input:hover {
            border-color: rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
        }
        .input-wrapper input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1), 0 0 20px rgba(16,185,129,0.05);
            background: rgba(255,255,255,0.05);
        }
        .input-wrapper input:focus + .input-icon,
        .input-wrapper input:focus ~ .input-icon { color: #10B981; }
        .input-wrapper input::placeholder { color: rgba(255,255,255,0.15); }
        .input-wrapper input:-webkit-autofill {
            -webkit-text-fill-color: #F1F5F9;
            -webkit-box-shadow: 0 0 0 1000px #0D1323 inset;
        }

        .password-toggle {
            position: absolute; right: 0.875rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: rgba(255,255,255,0.2); cursor: pointer;
            padding: 0.25rem; font-size: 0.875rem;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: rgba(255,255,255,0.5); }

        /* Options Row */
        .form-options {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 1.5rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.8125rem; color: rgba(255,255,255,0.4);
            cursor: pointer; font-weight: 500;
        }
        .custom-check {
            width: 18px; height: 18px;
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
            flex-shrink: 0;
        }
        .custom-check i { font-size: 0.5rem; color: white; display: none; }
        .remember-input { display: none; }
        .remember-input:checked + .custom-check {
            background: #059669; border-color: #059669;
        }
        .remember-input:checked + .custom-check i { display: block; }

        .forgot-link {
            font-size: 0.8125rem; color: #10B981;
            text-decoration: none; font-weight: 600;
            transition: all 0.2s;
        }
        .forgot-link:hover { color: #34D399; }

        /* Submit Button */
        .login-btn {
            width: 100%; padding: 0.875rem;
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 0.9375rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 8px 24px rgba(5,150,105,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            position: relative; overflow: hidden;
        }
        .login-btn::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.1) 50%, transparent 80%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .login-btn:hover::after { transform: translateX(100%); }
        .login-btn:hover {
            box-shadow: 0 12px 36px rgba(5,150,105,0.4), inset 0 1px 0 rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        .login-btn:active { transform: translateY(0); }
        .login-btn.loading { pointer-events: none; opacity: 0.85; }

        .login-btn .spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        .login-btn.loading .spinner { display: block; }
        .login-btn.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Divider */
        .login-divider {
            display: flex; align-items: center; gap: 1rem;
            margin: 1.5rem 0;
            color: rgba(255,255,255,0.15);
            font-size: 0.6875rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em;
        }
        .login-divider::before, .login-divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.06);
        }

        /* Security badges */
        .security-row {
            display: flex; gap: 0.75rem; justify-content: center;
        }
        .security-badge {
            display: flex; align-items: center; gap: 0.375rem;
            font-size: 0.6875rem; font-weight: 500;
            color: rgba(255,255,255,0.25);
            padding: 0.375rem 0.75rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 999px;
        }
        .security-badge i { color: rgba(16,185,129,0.5); font-size: 0.625rem; }

        /* Footer */
        .login-footer {
            text-align: center; margin-top: 2rem;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: rgba(255,255,255,0.3); font-size: 0.8125rem;
            font-weight: 500; text-decoration: none;
            transition: all 0.2s; padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        .back-link:hover { color: #10B981; background: rgba(16,185,129,0.05); }

        .login-time {
            text-align: center; margin-top: 1rem;
            color: rgba(255,255,255,0.12); font-size: 0.6875rem;
            font-weight: 500; font-variant-numeric: tabular-nums;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 1024px) {
            .login-showcase { display: none; }
            .login-panel { width: 100%; }
            .login-mobile-brand { display: block; }
        }

        @media (max-width: 480px) {
            .login-panel { padding: 2rem 1.5rem; }
            .login-card { padding: 1.5rem; }
            .login-welcome h1 { font-size: 1.5rem; }
            .security-row { flex-wrap: wrap; justify-content: center; }
        }

        /* ====== ENTRANCE ANIMATION ====== */
        .login-wrapper { animation: formEnter 0.7s cubic-bezier(0.4,0,0.2,1); }
        .showcase-content { animation: showcaseEnter 0.8s cubic-bezier(0.4,0,0.2,1) 0.2s both; }
        @keyframes formEnter {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes showcaseEnter {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Feature cards staggered entrance */
        .feature-card:nth-child(1) { animation: cardEnter 0.5s ease 0.4s both; }
        .feature-card:nth-child(2) { animation: cardEnter 0.5s ease 0.55s both; }
        .feature-card:nth-child(3) { animation: cardEnter 0.5s ease 0.7s both; }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateX(-16px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>

    <!-- ====== LEFT: SHOWCASE PANEL ====== -->
    <div class="login-showcase">
        <div class="mesh-grid"></div>
        <div class="showcase-glow"></div>

        <!-- Geometric shapes -->
        <div class="geo-shape geo-1"></div>
        <div class="geo-shape geo-2"></div>
        <div class="geo-shape geo-3"></div>
        <div class="geo-shape geo-4"></div>
        <div class="geo-shape geo-5"></div>

        <div class="showcase-content">
            <div class="showcase-brand">
                <div class="showcase-brand-icon"><i class="fas fa-briefcase"></i></div>
                <div class="showcase-brand-text">Tax<span>Safar</span></div>
            </div>

            <h2 class="showcase-headline">
                Manage your<br>inquiries with<br><span class="accent">confidence.</span>
            </h2>
            <p class="showcase-desc">
                Access your admin dashboard to track, manage, and respond to client inquiries — all in one powerful interface.
            </p>

            <div class="showcase-features">
                <div class="feature-card active" id="feat1">
                    <div class="feature-icon green"><i class="fas fa-chart-pie"></i></div>
                    <div>
                        <div class="feature-title">Real-time Dashboard</div>
                        <div class="feature-desc">Live stats, status tracking, and instant insights</div>
                    </div>
                </div>
                <div class="feature-card" id="feat2">
                    <div class="feature-icon blue"><i class="fas fa-inbox"></i></div>
                    <div>
                        <div class="feature-title">Inquiry Management</div>
                        <div class="feature-desc">Search, filter, and update inquiries in seconds</div>
                    </div>
                </div>
                <div class="feature-card" id="feat3">
                    <div class="feature-icon purple"><i class="fas fa-shield-halved"></i></div>
                    <div>
                        <div class="feature-title">Enterprise Security</div>
                        <div class="feature-desc">Argon2 hashing, CSRF protection, rate limiting</div>
                    </div>
                </div>
            </div>

            <div class="showcase-stats">
                <div class="stat-pill"><span class="stat-val">500+</span> clients served</div>
                <div class="stat-pill"><span class="stat-val">99.9%</span> uptime</div>
                <div class="stat-pill"><span class="stat-val">24/7</span> access</div>
            </div>
        </div>
    </div>

    <!-- ====== RIGHT: LOGIN FORM ====== -->
    <div class="login-panel">
        <div class="login-wrapper">

            <!-- Mobile brand (visible < 1024px) -->
            <div class="login-mobile-brand">
                <div class="mobile-icon"><i class="fas fa-briefcase"></i></div>
                <h2>Tax<span>Safar</span></h2>
            </div>

            <!-- Welcome -->
            <div class="login-welcome">
                <h1>Welcome back 👋</h1>
                <p>Sign in to your admin dashboard</p>
            </div>

            <!-- Login Card -->
            <div class="login-card">
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="login-alert login-alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="login-alert login-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></span>
                    </div>
                <?php endif; ?>

                <form action="?page=process_login" method="POST" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email"
                                   placeholder="admin@taxsafar.com" required autofocus
                                   value="<?= htmlspecialchars($_SESSION['login_email'] ?? ''); ?>">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            Password
                            <a href="#" class="forgot-link" title="Contact admin for password reset">Forgot?</a>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password"
                                   placeholder="Enter your password" required autocomplete="current-password">
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" class="remember-input">
                            <span class="custom-check"><i class="fas fa-check"></i></span>
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="login-btn" id="loginBtn">
                        <span class="spinner"></span>
                        <span class="btn-text">Sign In <i class="fas fa-arrow-right"></i></span>
                    </button>
                </form>
            </div>

            <!-- Security Badges -->
            <div class="login-divider">Secured Access</div>
            <div class="security-row">
                <div class="security-badge"><i class="fas fa-lock"></i> SSL Encrypted</div>
                <div class="security-badge"><i class="fas fa-shield-halved"></i> CSRF Protected</div>
                <div class="security-badge"><i class="fas fa-clock"></i> Rate Limited</div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <a href="?page=landing" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Homepage
                </a>
                <div class="login-time" id="loginTime"></div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
            else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('loginBtn').classList.add('loading');
        });

        // Live clock
        function updateTime() {
            const now = new Date();
            document.getElementById('loginTime').textContent =
                now.toLocaleDateString('en-IN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) +
                ' · ' + now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Feature card cycling highlight
        const feats = document.querySelectorAll('.feature-card');
        let activeIdx = 0;
        setInterval(() => {
            feats[activeIdx].classList.remove('active');
            activeIdx = (activeIdx + 1) % feats.length;
            feats[activeIdx].classList.add('active');
        }, 3000);
    </script>
</body>
</html>
