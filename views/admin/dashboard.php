<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
requireLogin();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../src/models/Inquiry.php';

$inquiry = new Inquiry($pdo);
$stats = $inquiry->getStats();
$allInquiries = $inquiry->getAll();
$recentInquiries = array_slice($allInquiries, 0, 8);

$total = $stats['total_inquiries'] ?? 0;
$newCount = $stats['new_inquiries'] ?? 0;
$contactedCount = $stats['contacted_inquiries'] ?? 0;
$closedCount = $stats['closed_inquiries'] ?? 0;

// Percentages for donut chart
$newPct = $total > 0 ? round(($newCount / $total) * 100) : 0;
$contactedPct = $total > 0 ? round(($contactedCount / $total) * 100) : 0;
$closedPct = $total > 0 ? round(($closedCount / $total) * 100) : 0;

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../partials/admin_header.php';

$hour = (int) date('H');
$greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
$greetingIcon = $hour < 12 ? '☀️' : ($hour < 17 ? '🌤️' : '🌙');
?>

<style>
    /* === DASHBOARD PREMIUM STYLES === */

    /* Greeting */
    .dash-greeting {
        margin-bottom: 2rem; padding: 2rem 2.5rem;
        background: linear-gradient(135deg, #059669 0%, #10B981 50%, #34D399 100%);
        border-radius: 20px; color: white;
        position: relative; overflow: hidden;
    }
    .dash-greeting::before {
        content: ''; position: absolute;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
        top: -100px; right: -50px; border-radius: 50%;
    }
    .dash-greeting::after {
        content: ''; position: absolute;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%);
        bottom: -60px; left: -30px; border-radius: 50%;
    }
    .dash-greeting-content { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
    .dash-greeting h1 {
        font-size: 1.75rem; font-weight: 800; color: white;
        letter-spacing: -0.03em; margin-bottom: 0.375rem;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .dash-greeting p { color: rgba(255,255,255,0.8); font-size: 0.9375rem; margin: 0; }
    .dash-greeting-meta { display: flex; gap: 2rem; margin-top: 0.75rem; }
    .dash-greeting-stat {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.8125rem; font-weight: 500; color: rgba(255,255,255,0.85);
    }
    .dash-greeting-stat i { font-size: 0.875rem; }

    .greeting-quick-actions { display: flex; gap: 0.75rem; }
    .quick-action-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5625rem 1.125rem;
        background: rgba(255,255,255,0.15); color: white;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px; text-decoration: none;
        font-size: 0.8125rem; font-weight: 600;
        transition: all 0.2s; backdrop-filter: blur(10px);
    }
    .quick-action-btn:hover {
        background: rgba(255,255,255,0.25); transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Stats Row */
    .stats-row {
        display: grid; grid-template-columns: repeat(4,1fr);
        gap: 1.25rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: white; border-radius: 18px; padding: 1.5rem;
        border: 1px solid #F1F5F9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        border-radius: 0 4px 4px 0;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.06);
    }
    .stat-card.green::before { background: linear-gradient(180deg,#059669,#34D399); }
    .stat-card.blue::before { background: linear-gradient(180deg,#3B82F6,#93C5FD); }
    .stat-card.amber::before { background: linear-gradient(180deg,#F59E0B,#FCD34D); }
    .stat-card.emerald::before { background: linear-gradient(180deg,#10B981,#6EE7B7); }

    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.125rem;
    }
    .stat-icon.green { background: linear-gradient(135deg,#ECFDF5,#D1FAE5); color: #059669; }
    .stat-icon.blue { background: linear-gradient(135deg,#EFF6FF,#DBEAFE); color: #3B82F6; }
    .stat-icon.amber { background: linear-gradient(135deg,#FFFBEB,#FEF3C7); color: #F59E0B; }
    .stat-icon.emerald { background: linear-gradient(135deg,#ECFDF5,#D1FAE5); color: #10B981; }

    .stat-value { font-size: 2.5rem; font-weight: 800; color: #0F172A; line-height: 1; letter-spacing: -0.03em; }
    .stat-desc { font-size: 0.75rem; color: #94A3B8; margin-top: 0.375rem; font-weight: 500; }

    .stat-sparkline { margin-top: 0.75rem; height: 32px; display: flex; align-items: flex-end; gap: 3px; }
    .spark-bar { flex: 1; border-radius: 3px; transition: height 0.4s ease; min-height: 4px; }
    .spark-bar.green { background: #D1FAE5; } .spark-bar.green:last-child { background: #059669; }
    .spark-bar.blue { background: #DBEAFE; } .spark-bar.blue:last-child { background: #3B82F6; }
    .spark-bar.amber { background: #FEF3C7; } .spark-bar.amber:last-child { background: #F59E0B; }
    .spark-bar.emerald { background: #D1FAE5; } .spark-bar.emerald:last-child { background: #10B981; }

    /* Main Grid — 2 columns */
    .dash-grid {
        display: grid; grid-template-columns: 1fr 360px;
        gap: 1.5rem; margin-bottom: 1.75rem;
    }

    /* Card base */
    .dash-card {
        background: white; border-radius: 18px;
        border: 1px solid #F1F5F9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .dash-card-header {
        padding: 1.25rem 1.5rem;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #F1F5F9;
    }
    .dash-card-header h3 {
        font-size: 1.0625rem; font-weight: 700; color: #0F172A;
        margin: 0; display: flex; align-items: center; gap: 0.5rem;
    }
    .count-badge {
        font-size: 0.625rem; background: #ECFDF5; color: #059669;
        padding: 0.25rem 0.625rem; border-radius: 999px; font-weight: 700;
    }
    .btn-view-all {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.5rem 1.125rem;
        background: linear-gradient(135deg,#059669,#10B981);
        color: white; border: none; border-radius: 999px;
        text-decoration: none; font-weight: 600; font-size: 0.8125rem;
        transition: all 0.3s; box-shadow: 0 2px 8px rgba(5,150,105,0.2);
    }
    .btn-view-all:hover {
        box-shadow: 0 6px 20px rgba(5,150,105,0.3);
        transform: translateY(-2px); color: white;
    }

    /* Table */
    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table thead th {
        background: #F8FAFC; font-size: 0.6875rem; font-weight: 700;
        color: #94A3B8; text-transform: uppercase; letter-spacing: 0.06em;
        padding: 0.875rem 1.25rem; text-align: left;
        border-bottom: 1px solid #F1F5F9; white-space: nowrap;
    }
    .dash-table tbody td {
        padding: 0.875rem 1.25rem; border-bottom: 1px solid #F8FAFC;
        font-size: 0.875rem; color: #64748B; vertical-align: middle;
    }
    .dash-table tbody tr { transition: background 0.15s; }
    .dash-table tbody tr:hover { background: #F8FAFC; }
    .dash-table tbody tr:last-child td { border-bottom: none; }

    .cell-id { font-weight: 700; color: #059669; font-size: 0.8125rem; }
    .cell-name { font-weight: 600; color: #0F172A; }
    .cell-subject { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cell-date { font-size: 0.8125rem; color: #94A3B8; white-space: nowrap; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 0.375rem;
        font-weight: 600; padding: 0.25rem 0.75rem;
        border-radius: 999px; font-size: 0.6875rem;
        text-transform: capitalize;
    }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .status-new { background: #ECFDF5; color: #065F46; } .status-new::before { background: #10B981; }
    .status-contacted { background: #EFF6FF; color: #1E40AF; } .status-contacted::before { background: #3B82F6; }
    .status-closed { background: #F1F5F9; color: #334155; } .status-closed::before { background: #64748B; }

    .action-group { display: flex; gap: 0.375rem; }
    .action-btn {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none; cursor: pointer;
        font-size: 0.8125rem; transition: all 0.2s; text-decoration: none;
    }
    .action-edit { background: #ECFDF5; color: #059669; }
    .action-edit:hover { background: #059669; color: white; }

    /* Donut Chart Card */
    .donut-card-inner { padding: 1.5rem; }
    .donut-wrap { display: flex; justify-content: center; margin-bottom: 1.5rem; position: relative; }
    .donut-center {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        text-align: center;
    }
    .donut-center-value { font-size: 2rem; font-weight: 800; color: #0F172A; line-height: 1; }
    .donut-center-label { font-size: 0.6875rem; color: #94A3B8; font-weight: 600; }

    .donut-legend { display: flex; flex-direction: column; gap: 0.75rem; }
    .legend-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.625rem 0.875rem;
        background: #F8FAFC; border-radius: 10px;
        transition: all 0.2s;
    }
    .legend-item:hover { transform: translateX(4px); background: #F1F5F9; }
    .legend-left { display: flex; align-items: center; gap: 0.625rem; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-name { font-size: 0.8125rem; font-weight: 600; color: #334155; }
    .legend-count { font-size: 0.875rem; font-weight: 800; color: #0F172A; }
    .legend-pct { font-size: 0.6875rem; color: #94A3B8; font-weight: 600; margin-left: 0.375rem; }

    /* Activity Timeline */
    .timeline { padding: 1.5rem; }
    .timeline-item {
        display: flex; gap: 0.875rem; padding-bottom: 1.25rem;
        position: relative;
    }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-item::before {
        content: ''; position: absolute;
        left: 15px; top: 32px; bottom: 0;
        width: 1.5px; background: #F1F5F9;
    }
    .timeline-item:last-child::before { display: none; }
    .timeline-dot {
        width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; position: relative; z-index: 1;
    }
    .timeline-dot.green { background: #ECFDF5; color: #059669; }
    .timeline-dot.blue { background: #EFF6FF; color: #3B82F6; }
    .timeline-dot.gray { background: #F1F5F9; color: #64748B; }
    .timeline-text { font-size: 0.8125rem; color: #334155; font-weight: 500; line-height: 1.5; }
    .timeline-text strong { color: #0F172A; font-weight: 700; }
    .timeline-time { font-size: 0.6875rem; color: #94A3B8; font-weight: 500; margin-top: 0.125rem; }

    /* Quick Actions Grid */
    .quick-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 1rem; padding: 1.5rem;
    }
    .quick-tile {
        padding: 1.25rem; border-radius: 14px;
        text-align: center; text-decoration: none;
        transition: all 0.3s; border: 1px solid #F1F5F9;
        background: #F8FAFC;
    }
    .quick-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        border-color: #E2E8F0;
    }
    .quick-tile-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 0.75rem; font-size: 1.125rem;
    }
    .quick-tile-icon.green { background: linear-gradient(135deg,#D1FAE5,#ECFDF5); color: #059669; }
    .quick-tile-icon.blue { background: linear-gradient(135deg,#DBEAFE,#EFF6FF); color: #3B82F6; }
    .quick-tile-icon.purple { background: linear-gradient(135deg,#EDE9FE,#F5F3FF); color: #7C3AED; }
    .quick-tile-icon.amber { background: linear-gradient(135deg,#FEF3C7,#FFFBEB); color: #F59E0B; }
    .quick-tile-label { font-size: 0.75rem; font-weight: 600; color: #334155; }

    /* Empty State */
    .empty-state { padding: 4rem 2rem; text-align: center; }
    .empty-icon {
        width: 72px; height: 72px;
        background: linear-gradient(135deg,#F1F5F9,#E2E8F0);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.75rem; color: #CBD5E1;
    }
    .empty-text { font-size: 0.9375rem; color: #94A3B8; font-weight: 500; }

    /* Responsive */
    @media (max-width: 1200px) {
        .dash-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 1024px) {
        .stats-row { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .dash-greeting h1 { font-size: 1.375rem; }
        .dash-greeting-meta { flex-direction: column; gap: 0.5rem; }
        .dash-greeting-content { flex-direction: column; }
        .greeting-quick-actions { flex-wrap: wrap; }
        .dash-table thead th, .dash-table tbody td { padding: 0.625rem 0.75rem; font-size: 0.8125rem; }
    }

    /* Entrance animation */
    .dash-animate { animation: dashFade 0.5s ease both; }
    .dash-animate:nth-child(1) { animation-delay: 0s; }
    .dash-animate:nth-child(2) { animation-delay: 0.05s; }
    .dash-animate:nth-child(3) { animation-delay: 0.1s; }
    .dash-animate:nth-child(4) { animation-delay: 0.15s; }
    @keyframes dashFade {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Horizontal Bar Chart */
    .bar-chart { padding: 1.5rem; }
    .bar-row { margin-bottom: 1rem; }
    .bar-row:last-child { margin-bottom: 0; }
    .bar-label-row { display: flex; justify-content: space-between; margin-bottom: 0.375rem; }
    .bar-label { font-size: 0.8125rem; font-weight: 600; color: #334155; }
    .bar-value { font-size: 0.8125rem; font-weight: 700; color: #0F172A; }
    .bar-track { height: 10px; background: #F1F5F9; border-radius: 999px; overflow: hidden; }
    .bar-fill {
        height: 100%; border-radius: 999px;
        transition: width 1.5s cubic-bezier(0.4,0,0.2,1); width: 0;
    }
    .bar-fill.green { background: linear-gradient(90deg, #10B981, #34D399); }
    .bar-fill.blue { background: linear-gradient(90deg, #3B82F6, #93C5FD); }
    .bar-fill.gray { background: linear-gradient(90deg, #94A3B8, #CBD5E1); }

    /* Performance Ring */
    .perf-card-inner { padding: 1.5rem; text-align: center; }
    .perf-ring-wrap { position: relative; display: inline-block; margin-bottom: 1rem; }
    .perf-ring-label { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; }
    .perf-score { font-size: 2rem; font-weight: 900; color: #0F172A; line-height: 1; }
    .perf-unit { font-size: 0.6875rem; color: #94A3B8; font-weight: 600; }
    .perf-desc { font-size: 0.8125rem; color: #64748B; font-weight: 500; margin-bottom: 1rem; }
    .perf-indicator {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.375rem 0.875rem; border-radius: 999px;
        font-size: 0.75rem; font-weight: 700;
    }
    .perf-indicator.good { background: #ECFDF5; color: #059669; }

    /* Summary Mini Row */
    .summary-row {
        display: grid; grid-template-columns: repeat(3,1fr);
        gap: 1rem; margin-bottom: 1.75rem;
    }
    .summary-mini {
        background: white; border-radius: 14px; padding: 1rem 1.25rem;
        text-align: center; border: 1px solid #F1F5F9; transition: all 0.3s;
    }
    .summary-mini:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    .summary-mini-icon { font-size: 1.25rem; margin-bottom: 0.5rem; }
    .summary-mini-val { font-size: 1.25rem; font-weight: 800; color: #0F172A; line-height: 1.2; }
    .summary-mini-label { font-size: 0.6875rem; color: #94A3B8; font-weight: 600; margin-top: 0.125rem; }
</style>

<!-- Greeting Card -->
<div class="dash-greeting dash-animate">
    <div class="dash-greeting-content">
        <div>
            <h1><?= $greetingIcon ?> <?= $greeting ?>, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>!</h1>
            <p>Here's a snapshot of your inquiry management dashboard</p>
            <div class="dash-greeting-meta">
                <div class="dash-greeting-stat"><i class="fas fa-calendar-day"></i> <?= date('l, d M Y') ?></div>
                <div class="dash-greeting-stat"><i class="fas fa-inbox"></i> <?= number_format($newCount) ?> new inquiries awaiting</div>
            </div>
        </div>
        <div class="greeting-quick-actions">
            <a href="?page=inquiries" class="quick-action-btn"><i class="fas fa-inbox"></i> All Inquiries</a>
            <a href="?page=landing" class="quick-action-btn" target="_blank"><i class="fas fa-globe"></i> View Site</a>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-card green dash-animate">
        <div class="stat-top">
            <div class="stat-label">Total Inquiries</div>
            <div class="stat-icon green"><i class="fas fa-inbox"></i></div>
        </div>
        <div class="stat-value"><?= number_format($total) ?></div>
        <div class="stat-desc">All time submissions</div>
        <div class="stat-sparkline">
            <div class="spark-bar green" style="height:40%"></div><div class="spark-bar green" style="height:60%"></div>
            <div class="spark-bar green" style="height:45%"></div><div class="spark-bar green" style="height:80%"></div>
            <div class="spark-bar green" style="height:55%"></div><div class="spark-bar green" style="height:70%"></div>
            <div class="spark-bar green" style="height:100%"></div>
        </div>
    </div>
    <div class="stat-card emerald dash-animate">
        <div class="stat-top">
            <div class="stat-label">New</div>
            <div class="stat-icon emerald"><i class="fas fa-sparkles"></i></div>
        </div>
        <div class="stat-value"><?= number_format($newCount) ?></div>
        <div class="stat-desc">Awaiting response</div>
        <div class="stat-sparkline">
            <div class="spark-bar emerald" style="height:50%"></div><div class="spark-bar emerald" style="height:30%"></div>
            <div class="spark-bar emerald" style="height:70%"></div><div class="spark-bar emerald" style="height:40%"></div>
            <div class="spark-bar emerald" style="height:90%"></div><div class="spark-bar emerald" style="height:60%"></div>
            <div class="spark-bar emerald" style="height:100%"></div>
        </div>
    </div>
    <div class="stat-card blue dash-animate">
        <div class="stat-top">
            <div class="stat-label">Contacted</div>
            <div class="stat-icon blue"><i class="fas fa-phone"></i></div>
        </div>
        <div class="stat-value"><?= number_format($contactedCount) ?></div>
        <div class="stat-desc">In communication</div>
        <div class="stat-sparkline">
            <div class="spark-bar blue" style="height:60%"></div><div class="spark-bar blue" style="height:80%"></div>
            <div class="spark-bar blue" style="height:40%"></div><div class="spark-bar blue" style="height:50%"></div>
            <div class="spark-bar blue" style="height:75%"></div><div class="spark-bar blue" style="height:85%"></div>
            <div class="spark-bar blue" style="height:100%"></div>
        </div>
    </div>
    <div class="stat-card amber dash-animate">
        <div class="stat-top">
            <div class="stat-label">Closed</div>
            <div class="stat-icon amber"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value"><?= number_format($closedCount) ?></div>
        <div class="stat-desc">Successfully resolved</div>
        <div class="stat-sparkline">
            <div class="spark-bar amber" style="height:30%"></div><div class="spark-bar amber" style="height:50%"></div>
            <div class="spark-bar amber" style="height:60%"></div><div class="spark-bar amber" style="height:45%"></div>
            <div class="spark-bar amber" style="height:70%"></div><div class="spark-bar amber" style="height:90%"></div>
            <div class="spark-bar amber" style="height:100%"></div>
        </div>
    </div>
</div>

<!-- Summary Mini Row -->
<div class="summary-row">
    <div class="summary-mini dash-animate">
        <div class="summary-mini-icon">📅</div>
        <div class="summary-mini-val"><?= date('d M') ?></div>
        <div class="summary-mini-label">Today</div>
    </div>
    <div class="summary-mini dash-animate">
        <div class="summary-mini-icon">⚡</div>
        <div class="summary-mini-val"><?= $total > 0 ? round((($contactedCount + $closedCount) / $total) * 100) : 0 ?>%</div>
        <div class="summary-mini-label">Response Rate</div>
    </div>
    <div class="summary-mini dash-animate">
        <div class="summary-mini-icon">🎯</div>
        <div class="summary-mini-val"><?= $total > 0 ? round(($closedCount / $total) * 100) : 0 ?>%</div>
        <div class="summary-mini-label">Resolution Rate</div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="dash-grid">
    <!-- Left: Recent Inquiries Table -->
    <div class="dash-card dash-animate">
        <div class="dash-card-header">
            <h3>Recent Inquiries <span class="count-badge"><?= count($recentInquiries) ?> latest</span></h3>
            <a href="?page=inquiries" class="btn-view-all">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php if (empty($recentInquiries)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                <div class="empty-text">No inquiries yet. They'll appear here once submitted.</div>
            </div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="dash-table">
                    <thead><tr><th>ID</th><th>Name</th><th>Service</th><th>Status</th><th>Date</th><th>Edit</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentInquiries as $inq):
                            $status = $inq['status'] ?? 'new';
                            $sc = match($status) { 'new'=>'status-new','contacted'=>'status-contacted','closed'=>'status-closed',default=>'status-closed' };
                        ?>
                        <tr>
                            <td class="cell-id">#<?= htmlspecialchars($inq['id']) ?></td>
                            <td class="cell-name"><?= htmlspecialchars($inq['full_name'] ?? $inq['name'] ?? '') ?></td>
                            <td class="cell-subject"><?= htmlspecialchars($inq['service'] ?? $inq['subject'] ?? '') ?></td>
                            <td><span class="status-badge <?= $sc ?>"><?= ucfirst($status) ?></span></td>
                            <td class="cell-date"><?= date('d M', strtotime($inq['created_at'] ?? date('Y-m-d'))) ?></td>
                            <td><a href="?page=edit&id=<?= $inq['id'] ?>" class="action-btn action-edit" title="Edit"><i class="fas fa-pencil"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right: Sidebar widgets -->
    <div style="display:flex; flex-direction:column; gap:1.5rem;">
        <!-- Donut Chart -->
        <div class="dash-card dash-animate">
            <div class="dash-card-header">
                <h3><i class="fas fa-chart-pie" style="color:#059669;font-size:0.875rem;"></i> Distribution</h3>
            </div>
            <div class="donut-card-inner">
                <div class="donut-wrap">
                    <svg width="160" height="160" viewBox="0 0 42 42" style="transform:rotate(-90deg);">
                        <circle cx="21" cy="21" r="15.91549431" fill="transparent" stroke="#F1F5F9" stroke-width="4"/>
                        <?php
                        $circumference = 2 * pi() * 15.91549431;
                        $newDash = ($newPct / 100) * $circumference;
                        $contactedDash = ($contactedPct / 100) * $circumference;
                        $closedDash = ($closedPct / 100) * $circumference;
                        $newOffset = 0;
                        $contactedOffset = -$newDash;
                        $closedOffset = -($newDash + $contactedDash);
                        ?>
                        <circle cx="21" cy="21" r="15.91549431" fill="transparent"
                                stroke="#10B981" stroke-width="4"
                                stroke-dasharray="<?= $newDash ?> <?= $circumference - $newDash ?>"
                                stroke-dashoffset="<?= $newOffset ?>"
                                stroke-linecap="round" class="donut-seg"/>
                        <circle cx="21" cy="21" r="15.91549431" fill="transparent"
                                stroke="#3B82F6" stroke-width="4"
                                stroke-dasharray="<?= $contactedDash ?> <?= $circumference - $contactedDash ?>"
                                stroke-dashoffset="<?= $contactedOffset ?>"
                                stroke-linecap="round" class="donut-seg"/>
                        <circle cx="21" cy="21" r="15.91549431" fill="transparent"
                                stroke="#94A3B8" stroke-width="4"
                                stroke-dasharray="<?= $closedDash ?> <?= $circumference - $closedDash ?>"
                                stroke-dashoffset="<?= $closedOffset ?>"
                                stroke-linecap="round" class="donut-seg"/>
                    </svg>
                    <div class="donut-center">
                        <div class="donut-center-value"><?= $total ?></div>
                        <div class="donut-center-label">Total</div>
                    </div>
                </div>
                <div class="donut-legend">
                    <div class="legend-item">
                        <div class="legend-left"><div class="legend-dot" style="background:#10B981;"></div><span class="legend-name">New</span></div>
                        <div><span class="legend-count"><?= $newCount ?></span><span class="legend-pct">(<?= $newPct ?>%)</span></div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left"><div class="legend-dot" style="background:#3B82F6;"></div><span class="legend-name">Contacted</span></div>
                        <div><span class="legend-count"><?= $contactedCount ?></span><span class="legend-pct">(<?= $contactedPct ?>%)</span></div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left"><div class="legend-dot" style="background:#94A3B8;"></div><span class="legend-name">Closed</span></div>
                        <div><span class="legend-count"><?= $closedCount ?></span><span class="legend-pct">(<?= $closedPct ?>%)</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dash-card dash-animate">
            <div class="dash-card-header">
                <h3><i class="fas fa-bolt" style="color:#F59E0B;font-size:0.875rem;"></i> Quick Actions</h3>
            </div>
            <div class="quick-grid">
                <a href="?page=inquiries" class="quick-tile">
                    <div class="quick-tile-icon green"><i class="fas fa-inbox"></i></div>
                    <div class="quick-tile-label">All Inquiries</div>
                </a>
                <a href="?page=inquiries&status=new" class="quick-tile">
                    <div class="quick-tile-icon blue"><i class="fas fa-sparkles"></i></div>
                    <div class="quick-tile-label">New Only</div>
                </a>
                <a href="?page=inquiries&status=contacted" class="quick-tile">
                    <div class="quick-tile-icon purple"><i class="fas fa-phone"></i></div>
                    <div class="quick-tile-label">Contacted</div>
                </a>
                <a href="?page=landing" class="quick-tile" target="_blank">
                    <div class="quick-tile-icon amber"><i class="fas fa-globe"></i></div>
                    <div class="quick-tile-label">Visit Website</div>
                </a>
            </div>
        </div>

        <!-- Bar Chart Breakdown -->
        <div class="dash-card dash-animate">
            <div class="dash-card-header">
                <h3><i class="fas fa-chart-bar" style="color:#8B5CF6;font-size:0.875rem;"></i> Breakdown</h3>
            </div>
            <div class="bar-chart">
                <div class="bar-row">
                    <div class="bar-label-row">
                        <span class="bar-label">New</span>
                        <span class="bar-value"><?= $newCount ?></span>
                    </div>
                    <div class="bar-track"><div class="bar-fill green" data-width="<?= $newPct ?>"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label-row">
                        <span class="bar-label">Contacted</span>
                        <span class="bar-value"><?= $contactedCount ?></span>
                    </div>
                    <div class="bar-track"><div class="bar-fill blue" data-width="<?= $contactedPct ?>"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label-row">
                        <span class="bar-label">Closed</span>
                        <span class="bar-value"><?= $closedCount ?></span>
                    </div>
                    <div class="bar-track"><div class="bar-fill gray" data-width="<?= $closedPct ?>"></div></div>
                </div>
            </div>
        </div>

        <!-- Performance Ring -->
        <?php
        $responseRate = $total > 0 ? round((($contactedCount + $closedCount) / $total) * 100) : 0;
        $ringCircumference = 2 * pi() * 48;
        $ringDash = ($responseRate / 100) * $ringCircumference;
        ?>
        <div class="dash-card dash-animate">
            <div class="dash-card-header">
                <h3><i class="fas fa-bullseye" style="color:#059669;font-size:0.875rem;"></i> Response Score</h3>
            </div>
            <div class="perf-card-inner">
                <div class="perf-ring-wrap">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="48" fill="none" stroke="#F1F5F9" stroke-width="8"/>
                        <circle cx="60" cy="60" r="48" fill="none" stroke="url(#perfGrad)" stroke-width="8"
                                stroke-dasharray="<?= $ringDash ?> <?= $ringCircumference - $ringDash ?>"
                                stroke-dashoffset="0" stroke-linecap="round"
                                style="transform:rotate(-90deg);transform-origin:center;transition:stroke-dasharray 1.5s ease;"/>
                        <defs><linearGradient id="perfGrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#059669"/><stop offset="100%" stop-color="#34D399"/></linearGradient></defs>
                    </svg>
                    <div class="perf-ring-label">
                        <div class="perf-score"><?= $responseRate ?></div>
                        <div class="perf-unit">out of 100</div>
                    </div>
                </div>
                <div class="perf-desc">Inquiries responded to or resolved</div>
                <div class="perf-indicator good"><i class="fas fa-arrow-trend-up"></i> <?= $responseRate >= 70 ? 'Excellent' : ($responseRate >= 40 ? 'Good' : 'Needs Attention') ?></div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="dash-card dash-animate">
            <div class="dash-card-header">
                <h3><i class="fas fa-clock-rotate-left" style="color:#3B82F6;font-size:0.875rem;"></i> Recent Activity</h3>
            </div>
            <div class="timeline">
                <?php
                $recentActivity = array_slice($allInquiries, 0, 5);
                if (empty($recentActivity)): ?>
                    <div style="text-align:center;padding:1rem;color:#94A3B8;font-size:0.875rem;">No activity yet</div>
                <?php else:
                    foreach ($recentActivity as $act):
                        $dotClass = match($act['status'] ?? 'new') { 'new'=>'green','contacted'=>'blue','closed'=>'gray',default=>'green' };
                        $icon = match($act['status'] ?? 'new') { 'new'=>'fa-plus','contacted'=>'fa-phone','closed'=>'fa-check',default=>'fa-plus' };
                        $verb = match($act['status'] ?? 'new') { 'new'=>'submitted an inquiry','contacted'=>'was contacted','closed'=>'inquiry closed',default=>'submitted an inquiry' };
                ?>
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $dotClass ?>"><i class="fas <?= $icon ?>"></i></div>
                        <div>
                            <div class="timeline-text"><strong><?= htmlspecialchars($act['full_name'] ?? $act['name'] ?? 'Unknown') ?></strong> <?= $verb ?></div>
                            <div class="timeline-time"><?= date('d M Y · h:i A', strtotime($act['created_at'] ?? date('Y-m-d'))) ?></div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Animate bar fills
    setTimeout(() => {
        document.querySelectorAll('.bar-fill[data-width]').forEach(bar => {
            bar.style.width = bar.dataset.width + '%';
        });
    }, 500);
</script>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
