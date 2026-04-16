<?php
// Generate CSRF token if not already present
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
    <title>TaxSafar — Expert CA &amp; Tax Consulting Services</title>
    <meta name="description" content="Professional Chartered Accountant services — GST registration, income tax filing, company incorporation and more. Get expert consultation today.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1F2937;
            background: #FFFFFF;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* === SCROLL PROGRESS BAR === */
        .scroll-progress {
            position: fixed; top: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #059669, #10B981, #34D399);
            z-index: 9999; transition: width 0.1s linear; width: 0%;
        }

        /* === TOP BAR === */
        .top-bar {
            background: linear-gradient(90deg, #065F46 0%, #047857 50%, #059669 100%);
            color: rgba(255,255,255,0.9);
            padding: 0.5rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.8125rem; font-weight: 500;
            position: relative; z-index: 10;
        }
        .top-bar-left, .top-bar-right { display: flex; align-items: center; gap: 1.5rem; }
        .top-bar a { color: rgba(255,255,255,0.85); text-decoration: none; transition: color 0.2s; }
        .top-bar a:hover { color: white; }
        .top-bar i { margin-right: 0.375rem; }
        .top-bar-social { display: flex; gap: 0.75rem; }
        .top-bar-social a {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; background: rgba(255,255,255,0.1);
            font-size: 0.75rem; transition: all 0.3s;
        }
        .top-bar-social a:hover { background: rgba(255,255,255,0.25); transform: translateY(-2px) scale(1.1); }

        /* === NAVBAR === */
        .navbar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            padding: 0 2rem; position: sticky; top: 0; z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        .navbar.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,0.08); background: rgba(255,255,255,0.95); }
        .navbar-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; justify-content: space-between; align-items: center;
            height: 72px;
        }
        .navbar-brand {
            font-size: 1.5rem; font-weight: 800; color: #111827;
            letter-spacing: -0.03em; display: flex; align-items: center; gap: 0.625rem;
            text-decoration: none;
        }
        .navbar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.125rem;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
            transition: transform 0.3s;
        }
        .navbar-brand:hover .brand-icon { transform: rotate(-8deg) scale(1.05); }
        .navbar-brand span { color: #059669; }

        .nav-menu { display: flex; gap: 0.25rem; align-items: center; }
        .nav-menu a {
            text-decoration: none; color: #4B5563;
            font-weight: 500; font-size: 0.9375rem;
            padding: 0.5rem 1rem; border-radius: 8px;
            transition: all 0.2s; position: relative;
        }
        .nav-menu a::after {
            content: ''; position: absolute;
            bottom: 0; left: 50%; width: 0; height: 2px;
            background: #059669; transition: all 0.3s; transform: translateX(-50%);
        }
        .nav-menu a:hover { color: #059669; background: #ECFDF5; }
        .nav-menu a:hover::after { width: 60%; }
        .nav-cta {
            background: linear-gradient(135deg, #059669, #10B981) !important;
            color: white !important;
            padding: 0.5rem 1.25rem !important; border-radius: 999px !important;
            box-shadow: 0 4px 12px rgba(5,150,105,0.25); font-weight: 600 !important;
        }
        .nav-cta::after { display: none !important; }
        .nav-cta:hover {
            box-shadow: 0 6px 20px rgba(5,150,105,0.35) !important;
            transform: translateY(-1px);
            background: linear-gradient(135deg, #047857, #059669) !important;
        }
        .mobile-toggle {
            display: none; background: none; border: none;
            font-size: 1.375rem; color: #374151; cursor: pointer; padding: 0.5rem;
        }

        /* === HERO === */
        .hero {
            background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 30%, #FFF 70%, #F0FDFA 100%);
            padding: 6rem 2rem 8rem;
            position: relative; overflow: hidden;
        }
        .hero-orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.5;
            animation: orbFloat 8s ease-in-out infinite alternate;
        }
        .hero-orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(5,150,105,0.12), transparent 70%); top: -150px; right: -100px; }
        .hero-orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(16,185,129,0.1), transparent 70%); bottom: -100px; left: -80px; animation-delay: 2s; }
        .hero-orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(52,211,153,0.08), transparent 70%); top: 30%; left: 50%; animation-delay: 4s; }
        @keyframes orbFloat { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(30px,-20px) scale(1.1); } }

        .hero-inner {
            max-width: 1280px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 4rem; align-items: center; position: relative; z-index: 1;
        }
        .hero-content { position: relative; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(5,150,105,0.08); color: #047857;
            padding: 0.4375rem 1.125rem; border-radius: 999px;
            font-size: 0.8125rem; font-weight: 600; margin-bottom: 1.75rem;
            border: 1px solid rgba(5,150,105,0.15);
            backdrop-filter: blur(10px); animation: fadeInUp 0.6s ease-out;
        }
        .hero-badge .pulse-dot {
            width: 8px; height: 8px; border-radius: 50%; background: #10B981; position: relative;
        }
        .hero-badge .pulse-dot::after {
            content: ''; position: absolute; inset: -4px;
            border-radius: 50%; border: 2px solid #10B981;
            animation: pulseDot 2s ease-out infinite;
        }
        @keyframes pulseDot { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(2); opacity: 0; } }

        .hero h1 {
            font-size: 3.75rem; font-weight: 900;
            line-height: 1.1; color: #111827;
            margin-bottom: 0.5rem; letter-spacing: -0.04em;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .typed-line {
            height: 4.5rem; display: flex; align-items: center;
            margin-bottom: 1.5rem; animation: fadeInUp 0.6s ease-out 0.15s both;
        }
        .typed-text {
            font-size: 3.75rem; font-weight: 900;
            line-height: 1.1; letter-spacing: -0.04em;
            background: linear-gradient(135deg, #059669, #10B981, #34D399);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .typed-cursor {
            display: inline-block; width: 3px; height: 3.25rem;
            background: #059669; margin-left: 4px;
            animation: blink 1s step-end infinite;
            vertical-align: middle; border-radius: 2px;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

        .hero p {
            font-size: 1.1875rem; color: #6B7280; line-height: 1.8;
            margin-bottom: 2.5rem; max-width: 520px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }
        .hero-btn-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.9375rem 2.25rem;
            background: linear-gradient(135deg, #059669, #10B981);
            color: white; border: none; border-radius: 999px;
            font-weight: 700; font-size: 1rem; text-decoration: none; cursor: pointer;
            box-shadow: 0 4px 14px rgba(5,150,105,0.3);
            transition: all 0.3s; position: relative; overflow: hidden;
        }
        .hero-btn-primary::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
            transform: translateX(-100%); transition: transform 0.6s;
        }
        .hero-btn-primary:hover::before { transform: translateX(100%); }
        .hero-btn-primary:hover { box-shadow: 0 8px 30px rgba(5,150,105,0.45); transform: translateY(-3px); }

        .hero-btn-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.9375rem 2.25rem;
            background: white; color: #059669;
            border: 2px solid #D1FAE5; border-radius: 999px;
            font-weight: 700; font-size: 1rem; text-decoration: none; transition: all 0.3s;
        }
        .hero-btn-secondary:hover {
            border-color: #059669; background: #ECFDF5;
            transform: translateY(-2px); box-shadow: 0 4px 16px rgba(5,150,105,0.15);
        }

        /* Avatar stack */
        .hero-social-proof {
            display: flex; align-items: center; gap: 1rem;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }
        .avatar-stack { display: flex; }
        .avatar-stack .avatar {
            width: 38px; height: 38px; border-radius: 50%;
            border: 3px solid white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.6875rem; color: white;
            margin-left: -10px;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.04);
        }
        .avatar-stack .avatar:first-child { margin-left: 0; }
        .avatar-stack .avatar.more { background: #E5E7EB; color: #6B7280; font-size: 0.625rem; font-weight: 800; }
        .hero-proof-text { font-size: 0.875rem; color: #6B7280; line-height: 1.4; }
        .hero-proof-text strong { color: #111827; }
        .hero-proof-rating { color: #F59E0B; font-size: 0.8125rem; letter-spacing: 1px; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); }
        }

        /* Hero Visual */
        .hero-visual { position: relative; animation: fadeInUp 0.8s ease-out 0.3s both; }
        .hero-card {
            background: white; border-radius: 24px; padding: 2.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.03);
            position: relative; transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
        }
        .hero-card:hover { transform: translateY(-4px); }
        .hero-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
        .hero-card-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.5rem;
            box-shadow: 0 6px 16px rgba(5,150,105,0.3);
        }
        .hero-card-title { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .hero-card-subtitle { font-size: 0.8125rem; color: #9CA3AF; font-weight: 500; }
        .hero-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; }
        .hero-stat {
            text-align: center; padding: 1.25rem 1rem;
            background: linear-gradient(135deg, #F9FAFB, #ECFDF5);
            border-radius: 14px; border: 1px solid rgba(5,150,105,0.06); transition: all 0.3s;
        }
        .hero-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(5,150,105,0.1); }
        .hero-stat-value { font-size: 1.875rem; font-weight: 800; color: #059669; line-height: 1.2; }
        .hero-stat-label { font-size: 0.75rem; color: #9CA3AF; font-weight: 500; margin-top: 0.25rem; }

        .hero-float-card {
            position: absolute; background: white;
            border-radius: 16px; padding: 1rem 1.25rem;
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
            display: flex; align-items: center; gap: 0.75rem;
            animation: floatCard 4s ease-in-out infinite alternate;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .hero-float-card.card-1 { top: -20px; right: -20px; }
        .hero-float-card.card-2 { bottom: -10px; left: -20px; animation-delay: 2s; }
        .float-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
        }
        .float-icon.green { background: #D1FAE5; color: #059669; }
        .float-icon.blue { background: #DBEAFE; color: #3B82F6; }
        .float-text-value { font-weight: 700; font-size: 0.9375rem; color: #111827; }
        .float-text-label { font-size: 0.6875rem; color: #9CA3AF; font-weight: 500; }
        @keyframes floatCard {
            0% { transform: translateY(0) rotate(0deg); } 100% { transform: translateY(-10px) rotate(1deg); }
        }

        /* === SVG WAVE DIVIDERS === */
        .wave-divider { width: 100%; line-height: 0; overflow: hidden; }
        .wave-divider svg { display: block; width: 100%; height: auto; }
        .wave-divider.flip { transform: rotate(180deg); }

        /* === TRUST MARQUEE === */
        .trust-banner {
            background: #FAFAFA; padding: 2rem 0; overflow: hidden;
            border-top: 1px solid #F3F4F6; border-bottom: 1px solid #F3F4F6;
            position: relative; z-index: 1;
        }
        .trust-banner-label {
            text-align: center; font-size: 0.75rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; color: #9CA3AF; margin-bottom: 1.25rem;
        }
        .trust-marquee {
            display: flex; gap: 3rem; animation: marquee 30s linear infinite;
        }
        .trust-marquee-item {
            display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;
            font-size: 1.125rem; font-weight: 700; color: #D1D5DB; opacity: 0.6;
        }
        .trust-marquee-item i { font-size: 1.25rem; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        /* === SERVICES === */
        .services { padding: 7rem 2rem; background: #FFFFFF; position: relative; z-index: 1; }
        .section-header { text-align: center; margin-bottom: 4rem; }
        .section-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #ECFDF5; color: #047857;
            padding: 0.4375rem 1.125rem; border-radius: 999px;
            font-size: 0.8125rem; font-weight: 600;
            margin-bottom: 1rem; border: 1px solid #D1FAE5;
        }
        .section-title {
            font-size: 2.75rem; font-weight: 800; color: #111827;
            margin-bottom: 1rem; letter-spacing: -0.03em;
        }
        .section-subtitle {
            font-size: 1.0625rem; color: #6B7280;
            max-width: 600px; margin: 0 auto; line-height: 1.7;
        }

        .services-grid {
            max-width: 1280px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem;
        }
        .service-card {
            background: white; border: 1px solid #F3F4F6;
            border-radius: 20px; padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
            cursor: default; position: relative; overflow: hidden;
            transform-style: preserve-3d; perspective: 800px;
        }
        .service-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: linear-gradient(90deg, #059669, #10B981, #34D399);
            transform: scaleX(0); transition: transform 0.4s; transform-origin: left;
        }
        .service-card::after {
            content: ''; position: absolute;
            bottom: -50%; right: -50%;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(5,150,105,0.04), transparent 70%);
            border-radius: 50%; transition: all 0.4s;
        }
        .service-card:hover {
            transform: translateY(-8px) rotateX(2deg);
            box-shadow: 0 25px 50px rgba(0,0,0,0.08);
            border-color: #E5E7EB;
        }
        .service-card:hover::before { transform: scaleX(1); }

        .service-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1.25rem;
            transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }
        .service-card:hover .service-icon { transform: scale(1.15) rotate(-5deg); }

        .service-icon.green { background: linear-gradient(135deg, #D1FAE5, #ECFDF5); color: #059669; }
        .service-icon.blue { background: linear-gradient(135deg, #DBEAFE, #EFF6FF); color: #3B82F6; }
        .service-icon.purple { background: linear-gradient(135deg, #EDE9FE, #F5F3FF); color: #7C3AED; }
        .service-icon.orange { background: linear-gradient(135deg, #FFEDD5, #FFF7ED); color: #EA580C; }
        .service-icon.rose { background: linear-gradient(135deg, #FFE4E6, #FFF1F2); color: #E11D48; }
        .service-icon.teal { background: linear-gradient(135deg, #CCFBF1, #F0FDFA); color: #0D9488; }

        .service-card h3 { font-size: 1.125rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; position: relative; z-index: 1; }
        .service-card p { font-size: 0.9375rem; color: #6B7280; line-height: 1.65; margin: 0; position: relative; z-index: 1; }
        .service-card .service-arrow {
            display: inline-flex; align-items: center; gap: 0.375rem;
            font-size: 0.8125rem; font-weight: 600; color: #059669;
            margin-top: 1rem; opacity: 0; transform: translateX(-8px);
            transition: all 0.3s; position: relative; z-index: 1;
        }
        .service-card:hover .service-arrow { opacity: 1; transform: translateX(0); }

        /* === WHY US === */
        .why-us {
            padding: 7rem 2rem;
            background: linear-gradient(180deg, #F9FAFB 0%, #ECFDF5 50%, #F9FAFB 100%);
            position: relative; z-index: 1;
        }
        .why-grid {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4,1fr); gap: 2rem;
        }
        .why-card {
            text-align: center; padding: 2.5rem 1.5rem;
            background: white; border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            transition: all 0.4s;
        }
        .why-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
        .why-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem; font-size: 1.5rem; color: white;
            box-shadow: 0 8px 20px rgba(5,150,105,0.25); transition: transform 0.4s;
        }
        .why-card:hover .why-icon { transform: scale(1.1) rotate(-5deg); }
        .why-card h3 { font-size: 1.0625rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; }
        .why-card p { font-size: 0.875rem; color: #6B7280; line-height: 1.6; }

        /* === STATS === */
        .stats-section {
            background: linear-gradient(135deg, #065F46, #047857, #059669);
            padding: 5rem 2rem; position: relative; overflow: hidden; z-index: 1;
        }
        .stats-section::before {
            content: ''; position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%);
            top: -150px; right: -150px; border-radius: 50%;
        }
        .stats-grid {
            max-width: 1280px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 2rem; position: relative; z-index: 1;
        }
        .stat-box { text-align: center; }
        .stat-number { font-size: 3rem; font-weight: 900; color: white; line-height: 1.2; letter-spacing: -0.03em; }
        .stat-label { font-size: 0.9375rem; color: rgba(255,255,255,0.7); font-weight: 500; margin-top: 0.375rem; }
        .stat-divider { width: 40px; height: 3px; background: rgba(255,255,255,0.2); border-radius: 999px; margin: 0.75rem auto 0; }

        /* === PROCESS === */
        .process-section { padding: 7rem 2rem; background: white; position: relative; z-index: 1; }
        .process-grid {
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4,1fr); gap: 0; position: relative;
        }
        .process-grid::before {
            content: ''; position: absolute;
            top: 40px; left: 12.5%; right: 12.5%;
            height: 2px; background: linear-gradient(90deg, #D1FAE5, #059669, #D1FAE5);
        }
        .process-step { text-align: center; position: relative; padding: 0 1rem; }
        .process-number {
            width: 56px; height: 56px; background: white;
            border: 3px solid #D1FAE5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.125rem; font-weight: 800; color: #059669;
            position: relative; z-index: 1; transition: all 0.4s;
        }
        .process-step:hover .process-number {
            background: linear-gradient(135deg, #059669, #10B981);
            color: white; border-color: #059669;
            transform: scale(1.1); box-shadow: 0 8px 20px rgba(5,150,105,0.3);
        }
        .process-step h3 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.375rem; }
        .process-step p { font-size: 0.8125rem; color: #6B7280; line-height: 1.5; }

        /* === TESTIMONIALS === */
        .testimonials {
            padding: 7rem 2rem;
            background: linear-gradient(180deg, #F9FAFB 0%, #FFF 100%);
            position: relative; z-index: 1;
        }
        .testimonials-grid {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem;
        }
        .testimonial-card {
            background: white; border-radius: 20px; padding: 2rem;
            border: 1px solid #F3F4F6;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03); transition: all 0.4s; position: relative;
        }
        .testimonial-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.06); }
        .testimonial-stars { margin-bottom: 1rem; color: #FBBF24; font-size: 0.875rem; letter-spacing: 2px; }
        .testimonial-text { font-size: 0.9375rem; color: #4B5563; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
        .testimonial-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #047857;
        }
        .testimonial-name { font-weight: 700; font-size: 0.9375rem; color: #111827; }
        .testimonial-role { font-size: 0.75rem; color: #9CA3AF; font-weight: 500; }
        .testimonial-quote-mark {
            position: absolute; top: 1rem; right: 1.5rem;
            font-size: 3rem; color: #F3F4F6; font-family: Georgia, serif; line-height: 1;
        }

        /* === FAQ SECTION === */
        .faq-section { padding: 7rem 2rem; background: #FFFFFF; position: relative; z-index: 1; }
        .faq-grid { max-width: 760px; margin: 0 auto; }
        .faq-item {
            border: 1px solid #F3F4F6; border-radius: 16px; margin-bottom: 0.75rem;
            overflow: hidden; transition: all 0.3s;
            background: white;
        }
        .faq-item:hover { border-color: #D1FAE5; }
        .faq-item.open { border-color: #A7F3D0; box-shadow: 0 4px 16px rgba(5,150,105,0.06); }
        .faq-question {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.25rem 1.5rem; cursor: pointer;
            font-weight: 600; font-size: 1rem; color: #111827;
            transition: color 0.2s; user-select: none;
        }
        .faq-item.open .faq-question { color: #059669; }
        .faq-toggle {
            width: 32px; height: 32px;
            background: #F3F4F6; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; color: #9CA3AF;
            transition: all 0.3s; flex-shrink: 0;
        }
        .faq-item.open .faq-toggle { background: #ECFDF5; color: #059669; transform: rotate(180deg); }
        .faq-answer {
            max-height: 0; overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4,0,0.2,1), padding 0.4s;
            padding: 0 1.5rem;
        }
        .faq-item.open .faq-answer { max-height: 200px; padding: 0 1.5rem 1.25rem; }
        .faq-answer p { font-size: 0.9375rem; color: #6B7280; line-height: 1.7; }

        /* === FORM SECTION === */
        .form-section {
            padding: 7rem 2rem;
            background: linear-gradient(180deg, #FFFFFF, #ECFDF5 40%, #F0FDF4 60%, #FFFFFF);
            position: relative; z-index: 1;
        }
        .form-container {
            max-width: 680px; margin: 0 auto;
            background: white; padding: 2.5rem; border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.03);
        }
        .form-container .form-group { margin-bottom: 1.25rem; }
        .form-container .form-label { display: block; font-weight: 600; font-size: 0.875rem; color: #374151; margin-bottom: 0.5rem; }
        .form-container .form-label .required { color: #EF4444; }
        .form-container .form-input {
            width: 100%; padding: 0.8125rem 1rem;
            border: 1.5px solid #E5E7EB; border-radius: 12px;
            font-size: 0.9375rem; font-family: 'Inter', sans-serif;
            color: #111827; background: #FAFAFA;
            transition: all 0.25s; outline: none;
        }
        .form-container .form-input:hover { border-color: #D1D5DB; }
        .form-container .form-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 4px rgba(5,150,105,0.08); background: white;
        }
        .form-container .form-input::placeholder { color: #9CA3AF; }
        .form-container textarea.form-input { resize: vertical; min-height: 120px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .form-submit-btn {
            width: 100%; padding: 0.9375rem;
            background: linear-gradient(135deg, #059669, #10B981);
            color: white; border: none; border-radius: 14px;
            font-size: 1rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer;
            box-shadow: 0 4px 14px rgba(5,150,105,0.3);
            transition: all 0.3s; display: flex; align-items: center; justify-content: center;
            gap: 0.5rem; margin-top: 0.75rem; position: relative; overflow: hidden;
        }
        .form-submit-btn::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
            transform: translateX(-100%); transition: transform 0.6s;
        }
        .form-submit-btn:hover::before { transform: translateX(100%); }
        .form-submit-btn:hover { box-shadow: 0 8px 30px rgba(5,150,105,0.4); transform: translateY(-2px); }
        .form-submit-btn:active { transform: translateY(0); }

        .form-flash {
            padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;
            font-weight: 500; font-size: 0.9375rem;
            display: flex; align-items: center; gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }
        .form-flash-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .form-flash-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        /* === CTA === */
        .cta-section {
            padding: 5rem 2rem;
            background: linear-gradient(135deg, #065F46, #047857);
            position: relative; overflow: hidden; z-index: 1;
        }
        .cta-section::before {
            content: ''; position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05), transparent 70%);
            top: -100px; right: -100px; border-radius: 50%;
        }
        .cta-inner {
            max-width: 700px; margin: 0 auto; text-align: center; position: relative; z-index: 1;
        }
        .cta-inner h2 { color: white; font-size: 2.25rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: -0.03em; }
        .cta-inner p { color: rgba(255,255,255,0.8); font-size: 1.0625rem; line-height: 1.7; margin-bottom: 2rem; }
        .cta-form { display: flex; gap: 0.75rem; max-width: 480px; margin: 0 auto; }
        .cta-input {
            flex: 1; padding: 0.8125rem 1.25rem;
            border-radius: 999px; border: 2px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1); color: white;
            font-size: 0.9375rem; font-family: 'Inter', sans-serif; outline: none; transition: all 0.3s;
        }
        .cta-input::placeholder { color: rgba(255,255,255,0.5); }
        .cta-input:focus { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.15); }
        .cta-btn {
            padding: 0.8125rem 1.75rem; background: white; color: #059669;
            border: none; border-radius: 999px; font-weight: 700; font-size: 0.9375rem;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        }
        .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

        /* === FOOTER === */
        .footer {
            background: #0F172A; color: #D1D5DB; padding: 4rem 2rem 1.5rem; position: relative; z-index: 1;
        }
        .footer-inner { max-width: 1280px; margin: 0 auto; }
        .footer-grid {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 3rem; margin-bottom: 3rem;
        }
        .footer-brand {
            font-size: 1.375rem; font-weight: 800; color: white;
            margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .footer-brand span { color: #10B981; }
        .footer-desc { font-size: 0.9375rem; color: #64748B; line-height: 1.7; margin-bottom: 1.5rem; }
        .footer-social { display: flex; gap: 0.625rem; }
        .footer-social a {
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.06); border-radius: 10px;
            color: #64748B; text-decoration: none; transition: all 0.3s;
        }
        .footer-social a:hover { background: #059669; color: white; transform: translateY(-2px); }
        .footer-col h4 { color: white; font-size: 0.9375rem; font-weight: 700; margin-bottom: 1.25rem; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 0.75rem; }
        .footer-col a {
            color: #64748B; text-decoration: none; font-size: 0.9375rem;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
        }
        .footer-col a:hover { color: #10B981; transform: translateX(3px); }
        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 0.75rem; font-size: 0.9375rem; color: #64748B;
        }
        .footer-contact-item i { color: #10B981; margin-top: 0.125rem; width: 16px; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 1.5rem;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.8125rem; color: #475569;
        }

        /* === SCROLL TOP BTN === */
        .scroll-top-btn {
            position: fixed; bottom: 2rem; right: 2rem;
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #059669, #10B981);
            color: white; border: none; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.125rem; cursor: pointer;
            box-shadow: 0 8px 24px rgba(5,150,105,0.3);
            opacity: 0; visibility: hidden; transform: translateY(20px);
            transition: all 0.4s; z-index: 90;
        }
        .scroll-top-btn.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .scroll-top-btn:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(5,150,105,0.4); }

        /* === RESPONSIVE === */
        @media (max-width: 1024px) {
            .services-grid { grid-template-columns: repeat(2,1fr); }
            .stats-grid { grid-template-columns: repeat(2,1fr); gap: 1.5rem; }
            .footer-grid { grid-template-columns: repeat(2,1fr); }
            .why-grid { grid-template-columns: repeat(2,1fr); }
            .testimonials-grid { grid-template-columns: repeat(2,1fr); }
            .process-grid { grid-template-columns: repeat(2,1fr); gap: 2rem; }
            .process-grid::before { display: none; }
        }
        @media (max-width: 768px) {
            .top-bar { display: none; }
            .navbar-inner { height: 64px; }
            .nav-menu {
                display: none; position: absolute;
                top: 64px; left: 0; right: 0;
                background: white; flex-direction: column; padding: 1rem;
                border-top: 1px solid #F3F4F6;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .nav-menu.active { display: flex; }
            .mobile-toggle { display: block; }
            .hero-inner { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
            .hero h1 { font-size: 2.5rem; }
            .typed-line { height: 3.25rem; justify-content: center; }
            .typed-text { font-size: 2.5rem; }
            .typed-cursor { height: 2.5rem; }
            .hero p { max-width: 100%; }
            .hero-buttons { justify-content: center; }
            .hero-social-proof { justify-content: center; flex-wrap: wrap; }
            .services-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; gap: 0.5rem; text-align: center; }
            .hero-float-card { display: none; }
            .section-title { font-size: 2rem; }
            .why-grid { grid-template-columns: 1fr; }
            .testimonials-grid { grid-template-columns: 1fr; }
            .process-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .cta-form { flex-direction: column; }
        }
        @media (max-width: 480px) {
            .hero { padding: 3rem 1rem 4rem; }
            .hero h1 { font-size: 2rem; }
            .typed-line { height: 2.75rem; }
            .typed-text { font-size: 2rem; }
            .typed-cursor { height: 2rem; }
            .form-container { padding: 1.5rem; }
            .process-grid { grid-template-columns: 1fr; }
        }

        /* === MOUSE GLOW === */
        .hero-mouse-glow {
            position: absolute; width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(5,150,105,0.07), transparent 70%);
            pointer-events: none; z-index: 0;
            transform: translate(-50%, -50%);
            transition: left 0.3s ease, top 0.3s ease;
            display: none;
        }

        /* === ANIMATED GRADIENT BORDER === */
        .service-card { position: relative; }
        .service-card .gradient-border {
            position: absolute; inset: -1px; border-radius: 21px;
            background: conic-gradient(from var(--angle, 0deg), #059669, #10B981, #34D399, #3B82F6, #8B5CF6, #059669);
            opacity: 0; transition: opacity 0.4s;
            z-index: -1;
            animation: rotateBorder 4s linear infinite;
        }
        .service-card:hover .gradient-border { opacity: 1; }
        @property --angle {
            syntax: '<angle>'; initial-value: 0deg; inherits: false;
        }
        @keyframes rotateBorder {
            to { --angle: 360deg; }
        }
        .service-card .card-inner-bg {
            position: absolute; inset: 1px; background: white;
            border-radius: 20px; z-index: 0;
        }

        /* === PRICING SECTION === */
        .pricing-section {
            padding: 7rem 2rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F0FDF4 50%, #FFFFFF 100%);
            position: relative; z-index: 1;
        }
        .pricing-grid {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem; align-items: center;
        }
        .pricing-card {
            background: white; border-radius: 24px;
            padding: 2.5rem 2rem; text-align: center;
            border: 1px solid #F3F4F6;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
            position: relative; overflow: hidden;
        }
        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }
        .pricing-card.featured {
            background: linear-gradient(135deg, #065F46, #047857, #059669);
            color: white; border-color: transparent;
            transform: scale(1.05);
            box-shadow: 0 20px 50px rgba(5,150,105,0.2);
        }
        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-8px);
            box-shadow: 0 30px 60px rgba(5,150,105,0.3);
        }
        .pricing-card.featured::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .pricing-popular {
            position: absolute; top: 1rem; right: -2rem;
            background: #FBBF24; color: #78350F;
            padding: 0.25rem 2.5rem;
            font-size: 0.6875rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.05em;
            transform: rotate(45deg);
        }
        .pricing-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem; font-size: 1.5rem;
        }
        .pricing-card:not(.featured) .pricing-icon { background: #ECFDF5; color: #059669; }
        .pricing-card.featured .pricing-icon { background: rgba(255,255,255,0.15); color: white; }

        .pricing-name { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .pricing-card:not(.featured) .pricing-name { color: #111827; }
        .pricing-desc { font-size: 0.875rem; margin-bottom: 1.5rem; }
        .pricing-card:not(.featured) .pricing-desc { color: #9CA3AF; }
        .pricing-card.featured .pricing-desc { color: rgba(255,255,255,0.7); }

        .pricing-amount {
            display: flex; align-items: baseline; justify-content: center;
            gap: 0.25rem; margin-bottom: 1.75rem;
        }
        .pricing-currency { font-size: 1.5rem; font-weight: 700; }
        .pricing-value { font-size: 3.5rem; font-weight: 900; line-height: 1; letter-spacing: -0.03em; }
        .pricing-period { font-size: 0.875rem; font-weight: 500; }
        .pricing-card:not(.featured) .pricing-currency,
        .pricing-card:not(.featured) .pricing-value { color: #111827; }
        .pricing-card:not(.featured) .pricing-period { color: #9CA3AF; }
        .pricing-card.featured .pricing-period { color: rgba(255,255,255,0.6); }

        .pricing-features { list-style: none; text-align: left; margin-bottom: 2rem; }
        .pricing-features li {
            padding: 0.5rem 0; font-size: 0.9375rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .pricing-features li i { font-size: 0.75rem; width: 20px; text-align: center; }
        .pricing-card:not(.featured) .pricing-features li { color: #4B5563; border-bottom: 1px solid #F9FAFB; }
        .pricing-card:not(.featured) .pricing-features li i { color: #10B981; }
        .pricing-card.featured .pricing-features li { color: rgba(255,255,255,0.85); border-bottom: 1px solid rgba(255,255,255,0.06); }
        .pricing-card.featured .pricing-features li i { color: #6EE7B7; }

        .pricing-btn {
            width: 100%; padding: 0.8125rem;
            border-radius: 14px; border: none; cursor: pointer;
            font-size: 0.9375rem; font-weight: 700;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s; text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            position: relative; overflow: hidden;
        }
        .pricing-card:not(.featured) .pricing-btn {
            background: #F0FDF4; color: #059669; border: 2px solid #D1FAE5;
        }
        .pricing-card:not(.featured) .pricing-btn:hover {
            background: #059669; color: white; border-color: #059669;
            box-shadow: 0 4px 16px rgba(5,150,105,0.25);
        }
        .pricing-card.featured .pricing-btn {
            background: white; color: #059669;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .pricing-card.featured .pricing-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        /* === FLOATING TRUST BADGES === */
        .hero-trust-badges {
            position: absolute; z-index: 2;
            bottom: 2rem; right: 2rem;
            display: flex; flex-direction: column; gap: 0.625rem;
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }
        .trust-badge-pill {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #F3F4F6;
            border-radius: 999px;
            font-size: 0.75rem; font-weight: 600; color: #374151;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            animation: floatBadge 4s ease-in-out infinite alternate;
        }
        .trust-badge-pill:nth-child(2) { animation-delay: 1.5s; }
        .trust-badge-pill:nth-child(3) { animation-delay: 3s; }
        .trust-badge-pill i { color: #10B981; }
        @keyframes floatBadge {
            0% { transform: translateY(0); } 100% { transform: translateY(-6px); }
        }

        /* === GLASSMORPHIC STAT COUNTERS (stats section) === */
        .stats-section .stat-box {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            transition: all 0.3s;
        }
        .stats-section .stat-box:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        /* === RESPONSIVE PRICING === */
        @media (max-width: 1024px) { .pricing-grid { grid-template-columns: repeat(2, 1fr); .pricing-card.featured { transform: none; } } }
        @media (max-width: 768px) {
            .pricing-grid { grid-template-columns: 1fr; max-width: 400px; }
            .pricing-card.featured { transform: none; }
            .pricing-card.featured:hover { transform: translateY(-8px); }
            .hero-trust-badges { display: none; }
        }

        /* === REVEAL === */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="top-bar-left">
            <span><i class="fas fa-envelope"></i> support@taxsafar.com</span>
            <span><i class="fas fa-phone"></i> +91 98765 43210</span>
        </div>
        <div class="top-bar-right">
            <span>Follow us:</span>
            <div class="top-bar-social">
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar" id="mainNavbar">
        <div class="navbar-inner">
            <a href="#home" class="navbar-brand">
                <div class="brand-icon"><i class="fas fa-briefcase"></i></div>
                Tax<span>Safar</span>
            </a>
            <button class="mobile-toggle" onclick="document.querySelector('.nav-menu').classList.toggle('active')" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-menu" id="navMenu">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#why-us">About</a>
                <a href="#testimonials">Reviews</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact</a>
                <a href="#pricing">Pricing</a>
                <a href="?page=login">Admin</a>
                <a href="#contact" class="nav-cta">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-mouse-glow" id="heroGlow"></div>
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <div class="hero-inner">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="pulse-dot"></span>
                    Trusted by 500+ businesses across India
                </div>
                <h1>Expert Financial <br>Solutions for</h1>
                <div class="typed-line"><span class="typed-text" id="heroTyped">Your Business</span><span class="typed-cursor"></span></div>
                <p>Simplify compliance, maximize returns, and accelerate growth with India's most trusted CA consultancy. We handle the numbers so you can focus on what matters.</p>
                <div class="hero-buttons">
                    <a href="#contact" class="hero-btn-primary"><i class="fas fa-paper-plane"></i> Submit Inquiry</a>
                    <a href="#services" class="hero-btn-secondary"><i class="fas fa-grid-2"></i> Our Services</a>
                </div>
                <div class="hero-social-proof">
                    <div class="avatar-stack">
                        <div class="avatar" style="background: linear-gradient(135deg,#059669,#10B981);">RS</div>
                        <div class="avatar" style="background: linear-gradient(135deg,#3B82F6,#60A5FA);">PS</div>
                        <div class="avatar" style="background: linear-gradient(135deg,#8B5CF6,#A78BFA);">AP</div>
                        <div class="avatar" style="background: linear-gradient(135deg,#F59E0B,#FBBF24);">VK</div>
                        <div class="avatar more">+496</div>
                    </div>
                    <div>
                        <div class="hero-proof-rating">★★★★★</div>
                        <div class="hero-proof-text"><strong>4.9/5</strong> from 500+ clients</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-card">
                    <div class="hero-card-header">
                        <div class="hero-card-icon"><i class="fas fa-chart-pie"></i></div>
                        <div>
                            <div class="hero-card-title">Business Analytics</div>
                            <div class="hero-card-subtitle">Real-time insights</div>
                        </div>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-value" data-count="98">0</div>
                            <div class="hero-stat-label">Success Rate %</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value" data-count="500">0</div>
                            <div class="hero-stat-label">Clients</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value" data-count="15">0</div>
                            <div class="hero-stat-label">Years Exp.</div>
                        </div>
                    </div>
                </div>
                <div class="hero-float-card card-1">
                    <div class="float-icon green"><i class="fas fa-arrow-trend-up"></i></div>
                    <div><div class="float-text-value">+24% Growth</div><div class="float-text-label">This Quarter</div></div>
                </div>
                <div class="hero-float-card card-2">
                    <div class="float-icon blue"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div><div class="float-text-value">₹2.5Cr Saved</div><div class="float-text-label">Tax Optimization</div></div>
                </div>
            </div>
        </div>

        <!-- Floating trust badges -->
        <div class="hero-trust-badges">
            <div class="trust-badge-pill"><i class="fas fa-certificate"></i> ICAI Certified</div>
            <div class="trust-badge-pill"><i class="fas fa-lock"></i> Bank-Grade Security</div>
            <div class="trust-badge-pill"><i class="fas fa-clock"></i> 24hr Turnaround</div>
        </div>
    </section>

    <!-- WAVE DIVIDER -->
    <div class="wave-divider" style="background:#FAFAFA;">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path fill="#ECFDF5" d="M0,64L48,58.7C96,53,192,43,288,42.7C384,43,480,53,576,53.3C672,53,768,43,864,37.3C960,32,1056,32,1152,37.3C1248,43,1344,53,1392,58.7L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
        </svg>
    </div>

    <!-- TRUST MARQUEE -->
    <div class="trust-banner">
        <div class="trust-banner-label">Trusted by leading businesses</div>
        <div style="overflow: hidden;">
            <div class="trust-marquee" id="trustMarquee">
                <div class="trust-marquee-item"><i class="fas fa-building"></i> Reliance SME</div>
                <div class="trust-marquee-item"><i class="fas fa-industry"></i> Tata Startups</div>
                <div class="trust-marquee-item"><i class="fas fa-store"></i> FreshMart</div>
                <div class="trust-marquee-item"><i class="fas fa-laptop-code"></i> TechVentures</div>
                <div class="trust-marquee-item"><i class="fas fa-truck"></i> SwiftLogistics</div>
                <div class="trust-marquee-item"><i class="fas fa-heart-pulse"></i> MediCorp</div>
                <div class="trust-marquee-item"><i class="fas fa-graduation-cap"></i> EduPrime</div>
                <div class="trust-marquee-item"><i class="fas fa-seedling"></i> GreenAgro</div>
                <div class="trust-marquee-item"><i class="fas fa-building"></i> Reliance SME</div>
                <div class="trust-marquee-item"><i class="fas fa-industry"></i> Tata Startups</div>
                <div class="trust-marquee-item"><i class="fas fa-store"></i> FreshMart</div>
                <div class="trust-marquee-item"><i class="fas fa-laptop-code"></i> TechVentures</div>
                <div class="trust-marquee-item"><i class="fas fa-truck"></i> SwiftLogistics</div>
                <div class="trust-marquee-item"><i class="fas fa-heart-pulse"></i> MediCorp</div>
                <div class="trust-marquee-item"><i class="fas fa-graduation-cap"></i> EduPrime</div>
                <div class="trust-marquee-item"><i class="fas fa-seedling"></i> GreenAgro</div>
            </div>
        </div>
    </div>

    <!-- SERVICES -->
    <section class="services" id="services">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-cube"></i> What We Offer</div>
            <h2 class="section-title">Comprehensive CA Services</h2>
            <p class="section-subtitle">Professional and affordable solutions for every stage of your business journey</p>
        </div>
        <div class="services-grid">
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon green"><i class="fas fa-file-contract"></i></div>
                <h3>Company Registration</h3>
                <p>Seamless incorporation for Private Limited, LLP, OPC, and Section 8 companies with end-to-end support.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon blue"><i class="fas fa-percent"></i></div>
                <h3>GST Services</h3>
                <p>Complete GST registration, monthly/quarterly return filing, and compliance management.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon purple"><i class="fas fa-calculator"></i></div>
                <h3>Income Tax Filing</h3>
                <p>Expert tax planning, ITR filing for individuals and businesses with maximum deduction benefits.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon orange"><i class="fas fa-chart-line"></i></div>
                <h3>Financial Planning</h3>
                <p>Strategic financial advisory, cash flow management, and growth planning tailored to your business.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon rose"><i class="fas fa-clipboard-check"></i></div>
                <h3>ROC Compliance</h3>
                <p>Annual filings, regulatory compliance, and corporate governance support to keep you audit-ready.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="service-card reveal">
                <div class="gradient-border"></div><div class="card-inner-bg"></div>
                <div class="service-icon teal"><i class="fas fa-handshake"></i></div>
                <h3>Business Advisory</h3>
                <p>Expert guidance for startups and established businesses — from setup to scaling operations.</p>
                <div class="service-arrow">Learn more <i class="fas fa-arrow-right"></i></div>
            </div>
        </div>
    </section>

    <!-- WAVE -->
    <div class="wave-divider" style="background:#F9FAFB;">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="white" d="M0,0L80,5.3C160,11,320,21,480,26.7C640,32,800,32,960,26.7C1120,21,1280,11,1360,5.3L1440,0L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
        </svg>
    </div>

    <!-- WHY US -->
    <section class="why-us" id="why-us">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-award"></i> Why TaxSafar</div>
            <h2 class="section-title">Why Businesses Choose Us</h2>
            <p class="section-subtitle">We combine deep expertise with modern technology to deliver results that matter</p>
        </div>
        <div class="why-grid">
            <div class="why-card reveal"><div class="why-icon"><i class="fas fa-user-tie"></i></div><h3>Expert CAs</h3><p>Certified professionals with 15+ years of industry experience</p></div>
            <div class="why-card reveal"><div class="why-icon"><i class="fas fa-bolt"></i></div><h3>Quick Turnaround</h3><p>Get responses within 24 hours and fast processing of all filings</p></div>
            <div class="why-card reveal"><div class="why-icon"><i class="fas fa-shield-halved"></i></div><h3>100% Secure</h3><p>Your data is encrypted and handled with bank-grade security</p></div>
            <div class="why-card reveal"><div class="why-icon"><i class="fas fa-tags"></i></div><h3>Affordable Pricing</h3><p>Transparent pricing with no hidden charges. Pay only for what you need</p></div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-box reveal"><div class="stat-number" data-count="500">0</div><div class="stat-label">Happy Clients</div><div class="stat-divider"></div></div>
            <div class="stat-box reveal"><div class="stat-number" data-count="15">0</div><div class="stat-label">Years Experience</div><div class="stat-divider"></div></div>
            <div class="stat-box reveal"><div class="stat-number" data-count="2000">0</div><div class="stat-label">Cases Resolved</div><div class="stat-divider"></div></div>
            <div class="stat-box reveal"><div class="stat-number" data-count="98">0</div><div class="stat-label">Client Satisfaction %</div><div class="stat-divider"></div></div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="process-section">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-route"></i> How It Works</div>
            <h2 class="section-title">Simple 4-Step Process</h2>
            <p class="section-subtitle">Getting started with TaxSafar is quick and effortless</p>
        </div>
        <div class="process-grid">
            <div class="process-step reveal"><div class="process-number">01</div><h3>Submit Inquiry</h3><p>Fill our quick form with your details and requirements</p></div>
            <div class="process-step reveal"><div class="process-number">02</div><h3>Expert Review</h3><p>Our CA team reviews and assigns a dedicated expert</p></div>
            <div class="process-step reveal"><div class="process-number">03</div><h3>Free Consultation</h3><p>Get a personalized call to discuss your needs</p></div>
            <div class="process-step reveal"><div class="process-number">04</div><h3>Get Started</h3><p>Begin your hassle-free compliance journey with us</p></div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials" id="testimonials">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-star"></i> Testimonials</div>
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">Real stories from real businesses we've helped grow</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card reveal">
                <span class="testimonial-quote-mark">"</span>
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"TaxSafar made our GST registration incredibly smooth. Their team was responsive and handled everything within 3 days. Highly recommend!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">RS</div>
                    <div><div class="testimonial-name">Rahul Sharma</div><div class="testimonial-role">CEO, FreshMart Pvt Ltd</div></div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <span class="testimonial-quote-mark">"</span>
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Best CA firm we've worked with. Their tax planning saved us over ₹8 lakhs last financial year. Professional and always available."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">PS</div>
                    <div><div class="testimonial-name">Priya Singh</div><div class="testimonial-role">Director, TechVentures</div></div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <span class="testimonial-quote-mark">"</span>
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"From company incorporation to monthly accounting, TaxSafar has been our one-stop solution. Affordable and reliable — exactly what startups need."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AP</div>
                    <div><div class="testimonial-name">Amit Patel</div><div class="testimonial-role">Founder, SwiftLogistics</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section class="pricing-section" id="pricing">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-indian-rupee-sign"></i> Pricing</div>
            <h2 class="section-title">Simple, Transparent Pricing</h2>
            <p class="section-subtitle">Choose a plan that fits your business. No hidden fees, cancel anytime.</p>
        </div>
        <div class="pricing-grid">
            <div class="pricing-card reveal">
                <div class="pricing-icon"><i class="fas fa-seedling"></i></div>
                <div class="pricing-name">Starter</div>
                <div class="pricing-desc">Perfect for freelancers & individuals</div>
                <div class="pricing-amount">
                    <span class="pricing-currency">₹</span>
                    <span class="pricing-value">999</span>
                    <span class="pricing-period">/filing</span>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> ITR Filing (1 return)</li>
                    <li><i class="fas fa-check"></i> Tax Planning Advice</li>
                    <li><i class="fas fa-check"></i> Email Support</li>
                    <li><i class="fas fa-check"></i> 7-day Turnaround</li>
                </ul>
                <a href="#contact" class="pricing-btn">Get Started</a>
            </div>
            <div class="pricing-card featured reveal">
                <div class="pricing-popular">Popular</div>
                <div class="pricing-icon"><i class="fas fa-rocket"></i></div>
                <div class="pricing-name">Professional</div>
                <div class="pricing-desc">Ideal for small businesses & startups</div>
                <div class="pricing-amount">
                    <span class="pricing-currency">₹</span>
                    <span class="pricing-value">4,999</span>
                    <span class="pricing-period">/month</span>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> GST + ITR Filing</li>
                    <li><i class="fas fa-check"></i> Dedicated CA Expert</li>
                    <li><i class="fas fa-check"></i> Monthly Bookkeeping</li>
                    <li><i class="fas fa-check"></i> Priority Phone Support</li>
                    <li><i class="fas fa-check"></i> 48hr Turnaround</li>
                </ul>
                <a href="#contact" class="pricing-btn">Get Started <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="pricing-card reveal">
                <div class="pricing-icon"><i class="fas fa-building"></i></div>
                <div class="pricing-name">Enterprise</div>
                <div class="pricing-desc">Full-service for established companies</div>
                <div class="pricing-amount">
                    <span class="pricing-currency">₹</span>
                    <span class="pricing-value">14,999</span>
                    <span class="pricing-period">/month</span>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> All Professional Features</li>
                    <li><i class="fas fa-check"></i> Virtual CFO Services</li>
                    <li><i class="fas fa-check"></i> ROC/MCA Compliance</li>
                    <li><i class="fas fa-check"></i> Audit & Assurance</li>
                    <li><i class="fas fa-check"></i> 24/7 Dedicated Support</li>
                </ul>
                <a href="#contact" class="pricing-btn">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section" id="faq">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-circle-question"></i> FAQ</div>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Quick answers to common queries about our services</p>
        </div>
        <div class="faq-grid">
            <div class="faq-item open reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How quickly can I get my GST registration?
                    <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer"><p>Our streamlined process ensures GST registration within 3-5 working days. We handle all documentation and follow-ups with the department for you.</p></div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    What documents are needed for company incorporation?
                    <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer"><p>You'll need Aadhaar, PAN, a recent photograph, address proof for the registered office, and digital signatures. We guide you through every step.</p></div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Do you offer services for freelancers and individuals?
                    <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer"><p>Absolutely! We offer ITR filing, tax planning, and financial advisory services tailored for freelancers, consultants, and salaried individuals.</p></div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I track the status of my inquiry?
                    <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer"><p>Once you submit an inquiry, our team contacts you within 24 hours. You'll receive regular updates via email and phone throughout the process.</p></div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    What are your payment terms?
                    <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer"><p>We offer flexible payment options including UPI, bank transfer, and EMI. Pricing is fully transparent with no hidden fees — you pay only for the services you choose.</p></div>
            </div>
        </div>
    </section>

    <!-- FORM -->
    <section class="form-section" id="contact">
        <div class="section-header reveal">
            <div class="section-badge"><i class="fas fa-paper-plane"></i> Get in Touch</div>
            <h2 class="section-title">Submit Your Inquiry</h2>
            <p class="section-subtitle">Fill in your details and our CA experts will get back to you within 24 hours</p>
        </div>
        <div class="form-container reveal">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="form-flash form-flash-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="form-flash form-flash-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
                </div>
            <?php endif; ?>
            <?php $old = $_SESSION['form_data'] ?? []; unset($_SESSION['form_data']); ?>

            <form action="?page=process_submit" method="POST" id="inquiryForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required">*</span></label>
                        <input type="text" class="form-input" name="full_name" placeholder="e.g. Rahul Sharma"
                               value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required minlength="2" maxlength="150">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address <span class="required">*</span></label>
                        <input type="email" class="form-input" name="email" placeholder="you@company.com"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="required">*</span></label>
                        <input type="tel" class="form-input" name="mobile" placeholder="9876543210"
                               pattern="[6-9][0-9]{9}" title="Enter a valid 10-digit Indian mobile number"
                               value="<?= htmlspecialchars($old['mobile'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City <span class="required">*</span></label>
                        <input type="text" class="form-input" name="city" placeholder="e.g. Mumbai"
                               value="<?= htmlspecialchars($old['city'] ?? '') ?>" required maxlength="100">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Service Required <span class="required">*</span></label>
                    <select class="form-input" name="service" required style="cursor:pointer;">
                        <option value="" disabled <?= empty($old['service']) ? 'selected' : '' ?>>Select a service...</option>
                        <?php
                        $services = ['GST Registration', 'Income Tax Return Filing', 'Company Incorporation', 'TDS Return Filing', 'Accounting & Bookkeeping', 'ROC/MCA Compliance', 'Virtual CFO Services', 'Audit & Assurance'];
                        foreach ($services as $srv): ?>
                            <option value="<?= htmlspecialchars($srv) ?>" <?= (($old['service'] ?? '') === $srv) ? 'selected' : '' ?>><?= htmlspecialchars($srv) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" name="message" placeholder="Tell us about your requirements..." maxlength="1000"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="form-submit-btn"><i class="fas fa-paper-plane"></i> Submit Inquiry</button>
            </form>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="cta-inner reveal">
            <h2>Stay Updated with Tax News</h2>
            <p>Get the latest updates on GST, Income Tax, and compliance changes delivered straight to your inbox.</p>
            <form class="cta-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                <input type="email" class="cta-input" placeholder="Enter your email address" required>
                <button type="submit" class="cta-btn">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-brand"><i class="fas fa-briefcase" style="color:#10B981;"></i> Tax<span>Safar</span></div>
                    <p class="footer-desc">Professional Chartered Accountant services to help your business thrive. Trusted by 500+ businesses across India.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Home</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Services</a></li>
                        <li><a href="#contact"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Contact Us</a></li>
                        <li><a href="?page=login"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Admin Portal</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Company Registration</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> GST Services</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Tax Filing</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.625rem;"></i> Business Advisory</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <div class="footer-contact-item"><i class="fas fa-envelope"></i><span>support@taxsafar.com</span></div>
                    <div class="footer-contact-item"><i class="fas fa-phone"></i><span>+91 98765 43210</span></div>
                    <div class="footer-contact-item"><i class="fas fa-clock"></i><span>Mon - Fri, 9 AM - 6 PM</span></div>
                    <div class="footer-contact-item"><i class="fas fa-location-dot"></i><span>Mumbai, Maharashtra</span></div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; <?= date('Y') ?> TaxSafar. All rights reserved.</span>
                <span>Made with <i class="fas fa-heart" style="color:#EF4444;font-size:0.75rem;"></i> in India</span>
            </div>
        </div>
    </footer>

    <button class="scroll-top-btn" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top"><i class="fas fa-arrow-up"></i></button>

    <script>
        // Scroll progress
        window.addEventListener('scroll', () => {
            const s = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            document.getElementById('scrollProgress').style.width = s + '%';
        });
        // Navbar scroll
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 30));
        // Scroll top btn
        const stb = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => stb.classList.toggle('visible', window.scrollY > 500));
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function(e) {
                const t = document.querySelector(this.getAttribute('href'));
                if (t) { e.preventDefault(); t.scrollIntoView({behavior:'smooth',block:'start'}); document.querySelector('.nav-menu')?.classList.remove('active'); }
            });
        });
        // Reveal
        const ro = new IntersectionObserver((entries) => {
            entries.forEach((entry,i) => {
                if (entry.isIntersecting) { setTimeout(() => entry.target.classList.add('visible'), i*80); ro.unobserve(entry.target); }
            });
        }, {threshold: 0.1, rootMargin: '0px 0px -40px 0px'});
        document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
        // Counters
        const co = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target, target = parseInt(el.dataset.count), dur = 2000, start = Date.now();
                    function tick() {
                        const p = Math.min((Date.now()-start)/dur,1), eased = 1-Math.pow(1-p,3);
                        el.textContent = Math.floor(eased*target) + (p>=1?'+':'');
                        if (p<1) requestAnimationFrame(tick);
                    }
                    tick(); co.unobserve(el);
                }
            });
        }, {threshold:0.5});
        document.querySelectorAll('[data-count]').forEach(el => co.observe(el));
        // Typed
        const typedWords=['Your Business','Your Startup','Your Growth','Your Future'];
        let wordIdx=0; const heroTyped=document.getElementById('heroTyped');
        function typeWord(){
            const word=typedWords[wordIdx]; heroTyped.textContent=''; let ci=0;
            function typeC(){if(ci<word.length){heroTyped.textContent+=word[ci];ci++;setTimeout(typeC,60);}else setTimeout(eraseW,2500);}
            function eraseW(){if(heroTyped.textContent.length>0){heroTyped.textContent=heroTyped.textContent.slice(0,-1);setTimeout(eraseW,30);}else{wordIdx=(wordIdx+1)%typedWords.length;setTimeout(typeWord,300);}}
            typeC();
        }
        setTimeout(typeWord,1500);
        // FAQ
        function toggleFaq(el){
            const item=el.parentElement;
            document.querySelectorAll('.faq-item.open').forEach(i=>{if(i!==item)i.classList.remove('open');});
            item.classList.toggle('open');
        }

        // Mouse-follow glow
        const heroEl = document.querySelector('.hero');
        const glowEl = document.getElementById('heroGlow');
        if (heroEl && glowEl) {
            heroEl.addEventListener('mouseenter', () => glowEl.style.display = 'block');
            heroEl.addEventListener('mouseleave', () => glowEl.style.display = 'none');
            heroEl.addEventListener('mousemove', (e) => {
                const rect = heroEl.getBoundingClientRect();
                glowEl.style.left = (e.clientX - rect.left) + 'px';
                glowEl.style.top = (e.clientY - rect.top) + 'px';
            });
        }
    </script>
</body>
</html>
