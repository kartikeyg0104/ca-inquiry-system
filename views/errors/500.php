<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error | TaxSafar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #0F172A;
            color: #F8FAFC;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; text-align: center; padding: 2rem;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        .orb { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; }
        .orb-1 { width: 400px; height: 400px; background: rgba(239,68,68,0.1); top: -100px; right: -100px; animation: drift1 10s ease-in-out infinite alternate; }
        .orb-2 { width: 300px; height: 300px; background: rgba(248,113,113,0.06); bottom: -80px; left: -80px; animation: drift2 8s ease-in-out infinite alternate; }
        @keyframes drift1 { 0% { transform: translate(0,0); } 100% { transform: translate(-30px, 20px); } }
        @keyframes drift2 { 0% { transform: translate(0,0); } 100% { transform: translate(20px, -30px); } }

        .error-container { max-width: 500px; position: relative; z-index: 1; animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .error-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #EF4444, #F87171);
            color: white; border-radius: 22px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 2rem;
            box-shadow: 0 12px 30px rgba(239, 68, 68, 0.3);
            animation: shake 4s ease-in-out infinite;
        }
        @keyframes shake {
            0%, 100% { transform: rotate(0); }
            10% { transform: rotate(-3deg); }
            20% { transform: rotate(3deg); }
            30% { transform: rotate(0); }
        }

        .error-code {
            font-size: 8rem; font-weight: 900;
            background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1; letter-spacing: -0.05em; margin-bottom: 1rem;
        }
        h1 { font-size: 1.5rem; font-weight: 700; color: #F8FAFC; margin-bottom: 0.75rem; }
        p { color: #94A3B8; font-size: 1rem; line-height: 1.7; margin-bottom: 0.75rem; }

        .contact-line { margin-bottom: 2rem; }
        .contact-line a {
            color: #10B981; text-decoration: none; font-weight: 600;
            transition: color 0.2s;
        }
        .contact-line a:hover { color: #34D399; }

        .btn-home {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.8125rem 2rem;
            background: linear-gradient(135deg, #059669, #10B981);
            color: white; border-radius: 999px; text-decoration: none;
            font-weight: 700; font-size: 0.9375rem;
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.3);
            transition: all 0.3s; position: relative; overflow: hidden;
        }
        .btn-home::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
            transform: translateX(-100%); transition: transform 0.6s;
        }
        .btn-home:hover::before { transform: translateX(100%); }
        .btn-home:hover { box-shadow: 0 12px 32px rgba(5,150,105,0.45); transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="error-container">
        <div class="error-icon"><i class="fas fa-server"></i></div>
        <div class="error-code">500</div>
        <h1>Internal Server Error</h1>
        <p>Something went wrong on our end. Our team has been notified and is working on it.</p>
        <p class="contact-line">
            <i class="far fa-envelope"></i> Contact support:
            <a href="mailto:support@taxsafar.com">support@taxsafar.com</a>
        </p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : 'index.php' ?>" class="btn-home">
            <i class="fas fa-house"></i> Return Home
        </a>
    </div>
</body>
</html>
