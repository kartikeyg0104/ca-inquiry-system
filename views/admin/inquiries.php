<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
requireLogin();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../src/models/Inquiry.php';

$inquiry = new Inquiry($pdo);

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

$inquiries = $inquiry->getAll($search, $status);
$stats = $inquiry->getStats();

$pageTitle = 'Inquiry Management';
require_once __DIR__ . '/../partials/admin_header.php';
?>

<style>
    /* Inquiries Page Styles */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.875rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.03em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .page-header h1 i {
        color: #059669;
        font-size: 1.5rem;
        background: #ECFDF5;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .total-count {
        font-size: 0.6875rem;
        background: #ECFDF5;
        color: #059669;
        padding: 0.25rem 0.625rem;
        border-radius: 999px;
        font-weight: 700;
        vertical-align: middle;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #F3F4F6;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .filter-row {
        display: grid;
        grid-template-columns: 1fr 200px auto;
        gap: 1rem;
        align-items: end;
    }

    .filter-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.375rem;
    }

    .filter-input {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #111827;
        background: #FAFAFA;
        outline: none;
        transition: all 0.2s;
    }

    .filter-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
        background: white;
    }

    .filter-input::placeholder { color: #9CA3AF; }

    .filter-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-search {
        background: linear-gradient(135deg, #059669, #10B981);
        color: white;
        box-shadow: 0 2px 8px rgba(5,150,105,0.2);
    }

    .btn-search:hover {
        box-shadow: 0 4px 14px rgba(5,150,105,0.3);
        transform: translateY(-1px);
    }

    .btn-clear {
        background: #F3F4F6;
        color: #6B7280;
        border: 1px solid #E5E7EB;
    }

    .btn-clear:hover {
        background: #E5E7EB;
        color: #374151;
    }

    .filter-active {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.75rem;
        color: #9CA3AF;
        font-weight: 500;
    }

    .filter-active i { color: #059669; }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .inq-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inq-table thead th {
        background: #FAFAFA;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.875rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid #F3F4F6;
        white-space: nowrap;
    }

    .inq-table tbody td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #FAFAFA;
        font-size: 0.875rem;
        color: #6B7280;
        vertical-align: middle;
    }

    .inq-table tbody tr { transition: background 0.15s; }
    .inq-table tbody tr:hover { background: #FAFFFE; }
    .inq-table tbody tr:last-child td { border-bottom: none; }

    .cell-id { font-weight: 700; color: #059669; font-size: 0.8125rem; }
    .cell-name { font-weight: 600; color: #111827; }

    .cell-contact {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .cell-contact-email {
        color: #374151;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .cell-contact-email a {
        color: #374151;
        text-decoration: none;
    }

    .cell-contact-email a:hover { color: #059669; }

    .cell-contact-phone {
        font-size: 0.75rem;
        color: #9CA3AF;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .cell-date { font-size: 0.8125rem; color: #9CA3AF; white-space: nowrap; }

    /* Status Select */
    .status-select {
        appearance: none;
        border: 1.5px solid;
        border-radius: 999px;
        padding: 0.3125rem 1.75rem 0.3125rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        outline: none;
        text-transform: capitalize;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1em 1em;
        transition: all 0.2s;
    }

    .status-select:focus {
        box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
    }

    .select-new { background-color: #ECFDF5; color: #065F46; border-color: #A7F3D0; }
    .select-contacted { background-color: #EFF6FF; color: #1E40AF; border-color: #93C5FD; }
    .select-closed { background-color: #F3F4F6; color: #374151; border-color: #D1D5DB; }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.375rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-edit { background: #ECFDF5; color: #059669; }
    .action-edit:hover { background: #059669; color: white; }
    .action-delete { background: #FEF2F2; color: #EF4444; }
    .action-delete:hover { background: #EF4444; color: white; }

    /* Table Footer */
    .table-footer {
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #F3F4F6;
        background: #FAFAFA;
        text-align: right;
        font-size: 0.8125rem;
        color: #9CA3AF;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: #F3F4F6;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: #D1D5DB;
    }

    .empty-title { font-size: 1.0625rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem; }
    .empty-text { font-size: 0.875rem; color: #9CA3AF; }

    /* Toast */
    .toast-wrap {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 2000;
    }

    .toast-msg {
        background: #065F46;
        color: white;
        padding: 0.875rem 1.25rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .toast-msg.show {
        transform: translateY(0);
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .inq-table thead th,
        .inq-table tbody td {
            padding: 0.625rem 0.75rem;
            font-size: 0.75rem;
        }
    }
</style>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" style="border-radius: 12px; margin-bottom: 1rem;">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 1rem;">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="fas fa-inbox"></i>
        Inquiry Management
        <span class="total-count"><?= count($inquiries) ?> total</span>
    </h1>
</div>

<!-- Search & Filter -->
<div class="filter-card">
    <form method="GET" action="">
        <input type="hidden" name="page" value="inquiries">
        <div class="filter-row">
            <div>
                <label class="filter-label">Search Keyword</label>
                <input type="text" class="filter-input" name="search" placeholder="Search by name, email, or phone..."
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div>
                <label class="filter-label">Filter by Status</label>
                <select name="status" class="filter-input filter-select">
                    <option value="">All Status (<?= $stats['total_inquiries'] ?? 0 ?>)</option>
                    <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>New (<?= $stats['new_inquiries'] ?? 0 ?>)</option>
                    <option value="contacted" <?= $status === 'contacted' ? 'selected' : '' ?>>Contacted (<?= $stats['contacted_inquiries'] ?? 0 ?>)</option>
                    <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>Closed (<?= $stats['closed_inquiries'] ?? 0 ?>)</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="?page=inquiries" class="btn-filter btn-clear">
                    <i class="fas fa-rotate-left"></i> Clear
                </a>
            </div>
        </div>
        <?php if (!empty($search) || !empty($status)): ?>
            <div class="filter-active">
                <i class="fas fa-filter"></i> Filters active — showing <?= count($inquiries) ?> result(s)
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Inquiries Table -->
<div class="table-card">
    <div style="overflow-x: auto;">
        <table class="inq-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Contact</th>
                    <th>City</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($inquiries) === 0): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-search"></i></div>
                                <div class="empty-title">No inquiries found</div>
                                <div class="empty-text">Try adjusting your search or filters.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inquiries as $row):
                        $selectClass = match($row['status']) {
                            'new' => 'select-new',
                            'contacted' => 'select-contacted',
                            'closed' => 'select-closed',
                            default => 'select-closed'
                        };
                    ?>
                        <tr>
                            <td class="cell-id">#<?= htmlspecialchars($row['id']) ?></td>
                            <td class="cell-name"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td>
                                <div class="cell-contact">
                                    <div class="cell-contact-email">
                                        <i class="far fa-envelope" style="color: #D1D5DB; font-size: 0.6875rem;"></i>
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a>
                                    </div>
                                    <div class="cell-contact-phone">
                                        <i class="fas fa-phone" style="font-size: 0.5625rem;"></i>
                                        <?= htmlspecialchars($row['mobile']) ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['city']) ?></td>
                            <td><?= htmlspecialchars($row['service']) ?></td>
                            <td>
                                <select class="status-select <?= $selectClass ?>"
                                        data-id="<?= $row['id'] ?>"
                                        data-csrf="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <option value="new" <?= $row['status']==='new' ? 'selected' : '' ?>>New</option>
                                    <option value="contacted" <?= $row['status']==='contacted' ? 'selected' : '' ?>>Contacted</option>
                                    <option value="closed" <?= $row['status']==='closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                            </td>
                            <td class="cell-date"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="?page=edit&id=<?= $row['id'] ?>" class="action-btn action-edit" title="Edit">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <form action="?page=process_delete" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete inquiry from <?= htmlspecialchars($row['full_name'], ENT_QUOTES) ?>?\n\nThis cannot be undone.');">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="action-btn action-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        Showing <?= count($inquiries) ?> of <?= $stats['total_inquiries'] ?? count($inquiries) ?> inquiries
    </div>
</div>

<!-- Toast -->
<div class="toast-wrap">
    <div class="toast-msg" id="statusToast">
        <i class="fas fa-check-circle"></i>
        <span id="toastText">Status updated successfully</span>
    </div>
</div>

<script>
// Status Update AJAX
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const el = this;
        const id = el.dataset.id;
        const status = el.value;
        const csrf = el.dataset.csrf;

        el.disabled = true;

        fetch('?page=process_status', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status) + '&csrf_token=' + encodeURIComponent(csrf)
        })
        .then(r => r.json())
        .then(data => {
            el.disabled = false;
            if (data.success) {
                // Update select styling
                el.className = 'status-select';
                if (status === 'new') el.classList.add('select-new');
                else if (status === 'contacted') el.classList.add('select-contacted');
                else el.classList.add('select-closed');

                // Show toast
                showToast('Status updated to ' + status);
            } else {
                alert(data.message || 'Error updating status');
            }
        })
        .catch(() => {
            el.disabled = false;
            alert('Network error occurred.');
        });
    });
});

function showToast(msg) {
    const toast = document.getElementById('statusToast');
    const text = document.getElementById('toastText');
    text.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
