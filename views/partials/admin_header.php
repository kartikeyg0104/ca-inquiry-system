<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel'); ?> — TaxSafar Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* === ADMIN SIDEBAR LAYOUT === */
        body { display: flex; min-height: 100vh; background: #F8FAFC; }

        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            display: flex; align-items: center; gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.125rem; color: white;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
        }
        .sidebar-brand-text {
            font-size: 1.25rem; font-weight: 800;
            letter-spacing: -0.02em;
        }
        .sidebar-brand-text span { color: #10B981; }
        .sidebar-brand-badge {
            font-size: 0.5625rem;
            background: rgba(16,185,129,0.15); color: #10B981;
            padding: 0.125rem 0.5rem; border-radius: 999px;
            font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; margin-left: auto;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
            flex: 1;
        }
        .sidebar-nav-label {
            font-size: 0.625rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.3);
            padding: 0.75rem 0.75rem 0.5rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.6875rem 0.875rem;
            border-radius: 10px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            position: relative;
        }
        .sidebar-link i { width: 20px; text-align: center; font-size: 0.9375rem; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(5,150,105,0.15), rgba(16,185,129,0.1));
            color: #10B981; font-weight: 600;
        }
        .sidebar-link.active::before {
            content: ''; position: absolute;
            left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%;
            background: #10B981; border-radius: 0 3px 3px 0;
        }
        .sidebar-link .link-badge {
            margin-left: auto;
            font-size: 0.625rem; font-weight: 700;
            background: rgba(16,185,129,0.15); color: #10B981;
            padding: 0.125rem 0.5rem;
            border-radius: 999px;
        }

        .sidebar-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8125rem; color: white;
        }
        .sidebar-user-name {
            font-size: 0.8125rem; font-weight: 600; color: rgba(255,255,255,0.85);
        }
        .sidebar-user-role {
            font-size: 0.6875rem; color: rgba(255,255,255,0.35);
        }
        .sidebar-logout {
            margin-left: auto;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            color: rgba(255,255,255,0.3);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        .sidebar-logout:hover { background: rgba(239,68,68,0.12); color: #EF4444; }

        /* === MAIN CONTENT === */
        .admin-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-topbar {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 0 2rem;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #F1F5F9;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left {
            display: flex; align-items: center; gap: 1rem;
        }
        .topbar-breadcrumb {
            font-size: 0.8125rem; color: #94A3B8;
        }
        .topbar-breadcrumb span { color: #334155; font-weight: 600; }
        .topbar-search {
            display: flex; align-items: center; gap: 0.5rem;
            background: #F1F5F9; border-radius: 10px;
            padding: 0.5rem 0.875rem;
        }
        .topbar-search i { color: #94A3B8; font-size: 0.8125rem; }
        .topbar-search input {
            border: none; background: none; outline: none;
            font-size: 0.8125rem; font-family: 'Inter',sans-serif;
            color: #334155; width: 200px;
        }
        .topbar-search input::placeholder { color: #94A3B8; }

        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-btn {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; border: none;
            background: #F1F5F9; color: #64748B;
            cursor: pointer; font-size: 0.9375rem;
            transition: all 0.2s; position: relative;
        }
        .topbar-btn:hover { background: #E2E8F0; color: #334155; }
        .topbar-btn .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #EF4444; border-radius: 50%;
            border: 2px solid white;
        }
        .topbar-date {
            font-size: 0.8125rem; color: #94A3B8; font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .mobile-menu-btn {
            display: none;
            width: 36px; height: 36px;
            align-items: center; justify-content: center;
            border-radius: 10px; border: none;
            background: #F1F5F9; color: #64748B;
            cursor: pointer; font-size: 1.125rem;
        }

        .admin-main {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 1.5rem 2rem 3rem;
        }

        /* === RESPONSIVE === */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 0 0 60px rgba(0,0,0,0.3);
            }
            .admin-wrapper { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
            .sidebar-overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 99;
                display: none;
            }
            .sidebar-overlay.active { display: block; }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="fas fa-briefcase"></i></div>
            <div class="sidebar-brand-text">Tax<span>Safar</span></div>
            <span class="sidebar-brand-badge">Admin</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Main Menu</div>
            <?php $currentPage = $_GET['page'] ?? ''; ?>
            <a href="?page=admin" class="sidebar-link <?= ($currentPage === 'admin') ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="?page=inquiries" class="sidebar-link <?= ($currentPage === 'inquiries') ? 'active' : '' ?>">
                <i class="fas fa-inbox"></i> Inquiries
            </a>

            <div class="sidebar-nav-label" style="margin-top: 0.5rem;">Quick Actions</div>
            <a href="?page=landing" class="sidebar-link" target="_blank">
                <i class="fas fa-globe"></i> View Website
                <span class="link-badge"><i class="fas fa-external-link" style="font-size: 0.5rem;"></i></span>
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) . strtoupper(substr($_SESSION['admin_name'] ?? 'A', 1, 1)) ?></div>
            <div>
                <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
            <a href="?page=logout" class="sidebar-logout" title="Logout">
                <i class="fas fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="admin-wrapper">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-breadcrumb">
                    Admin / <span><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-date" id="topbarDate"></div>
                <button class="topbar-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-dot"></span>
                </button>
                <button class="topbar-btn" title="Settings">
                    <i class="fas fa-gear"></i>
                </button>
            </div>
        </header>

        <!-- Admin Main Content -->
        <div class="admin-main">

<script>
// Topbar date/time
function updateTopDate() {
    const now = new Date();
    document.getElementById('topbarDate').textContent =
        now.toLocaleDateString('en-IN', { weekday: 'short', month: 'short', day: 'numeric' }) +
        ' · ' + now.toLocaleTimeString('en-IN', { hour: '2-digit', minute:'2-digit' });
}
updateTopDate();
setInterval(updateTopDate, 30000);

// Mobile sidebar toggle
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}
</script>
