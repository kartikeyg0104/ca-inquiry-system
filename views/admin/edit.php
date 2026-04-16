<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
requireLogin();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../src/models/Inquiry.php';

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['flash_error'] = "Invalid inquiry ID.";
    header('Location: ?page=inquiries');
    exit();
}

$inquiryModel = new Inquiry($pdo);
$data = $inquiryModel->getById($id);

if (!$data) {
    $_SESSION['flash_error'] = "Requested inquiry not found.";
    header('Location: ?page=inquiries');
    exit();
}

$pageTitle = "Edit Inquiry #{$id}";
require_once __DIR__ . '/../partials/admin_header.php';

$old = $_SESSION['form_data'] ?? $data;
unset($_SESSION['form_data']);
?>

<style>
    /* Edit Page Styles */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .breadcrumb-nav a {
        color: #059669;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover { color: #047857; }

    .breadcrumb-nav .separator {
        color: #D1D5DB;
        font-size: 0.75rem;
    }

    .breadcrumb-nav .current {
        color: #6B7280;
        font-weight: 600;
    }

    .edit-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .edit-card-header {
        background: linear-gradient(135deg, #059669, #10B981);
        padding: 1.25rem 1.75rem;
        color: white;
    }

    .edit-card-header h2 {
        font-size: 1.125rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .edit-card-body {
        padding: 2rem 1.75rem;
    }

    .edit-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .edit-group {
        margin-bottom: 1.25rem;
    }

    .edit-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4375rem;
        letter-spacing: 0.01em;
    }

    .edit-label .req { color: #EF4444; }

    .edit-input {
        width: 100%;
        padding: 0.6875rem 1rem;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.9375rem;
        font-family: 'Inter', sans-serif;
        color: #111827;
        background: #FAFAFA;
        outline: none;
        transition: all 0.2s;
    }

    .edit-input:hover { border-color: #D1D5DB; }

    .edit-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
        background: white;
    }

    textarea.edit-input {
        resize: vertical;
        min-height: 100px;
    }

    .edit-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    /* Status select color */
    .status-edit-select {
        font-weight: 700;
    }

    .status-edit-select.text-green { color: #059669; border-color: #A7F3D0; }
    .status-edit-select.text-blue { color: #3B82F6; border-color: #93C5FD; }
    .status-edit-select.text-gray { color: #6B7280; border-color: #D1D5DB; }

    /* Action Bar */
    .edit-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 1px solid #F3F4F6;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #F3F4F6;
        color: #6B7280;
        border: 1px solid #E5E7EB;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #E5E7EB;
        color: #374151;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.5rem;
        background: linear-gradient(135deg, #059669, #10B981);
        color: white;
        border: none;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(5,150,105,0.25);
        transition: all 0.25s;
    }

    .btn-save:hover {
        box-shadow: 0 6px 20px rgba(5,150,105,0.35);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-row { grid-template-columns: 1fr; }
        .edit-card-body { padding: 1.5rem; }
        .edit-actions { flex-direction: column-reverse; gap: 0.75rem; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-nav">
    <a href="?page=admin">Dashboard</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <a href="?page=inquiries">Inquiries</a>
    <span class="separator"><i class="fas fa-chevron-right"></i></span>
    <span class="current">Edit #<?= $id ?></span>
</div>

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

<!-- Edit Card -->
<div class="edit-card">
    <div class="edit-card-header">
        <h2><i class="fas fa-pen-to-square"></i> Edit Inquiry Details</h2>
    </div>

    <div class="edit-card-body">
        <form action="?page=process_update" method="POST" id="editForm">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="edit-row">
                <div class="edit-group">
                    <label class="edit-label">Full Name <span class="req">*</span></label>
                    <input type="text" class="edit-input" name="full_name"
                           value="<?= htmlspecialchars($old['full_name']) ?>" required>
                </div>
                <div class="edit-group">
                    <label class="edit-label">Email Address <span class="req">*</span></label>
                    <input type="email" class="edit-input" name="email"
                           value="<?= htmlspecialchars($old['email']) ?>" required>
                </div>
            </div>

            <div class="edit-row">
                <div class="edit-group">
                    <label class="edit-label">Mobile Number <span class="req">*</span></label>
                    <input type="tel" class="edit-input" name="mobile" pattern="[6-9][0-9]{9}"
                           value="<?= htmlspecialchars($old['mobile']) ?>" required>
                </div>
                <div class="edit-group">
                    <label class="edit-label">City <span class="req">*</span></label>
                    <input type="text" class="edit-input" name="city"
                           value="<?= htmlspecialchars($old['city']) ?>" required>
                </div>
            </div>

            <div class="edit-row">
                <div class="edit-group">
                    <label class="edit-label">Service Required <span class="req">*</span></label>
                    <select class="edit-input edit-select" name="service" required>
                        <?php
                        $services = ['GST Registration', 'Income Tax Return Filing', 'Company Incorporation', 'TDS Return Filing', 'Accounting & Bookkeeping', 'ROC/MCA Compliance', 'Virtual CFO Services', 'Audit & Assurance'];
                        foreach ($services as $srv):
                        ?>
                            <option value="<?= htmlspecialchars($srv) ?>" <?= ($old['service'] == $srv) ? 'selected' : '' ?>><?= htmlspecialchars($srv) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="edit-group">
                    <label class="edit-label">Workflow Status <span class="req">*</span></label>
                    <select class="edit-input edit-select status-edit-select" name="status" id="statusField" required>
                        <option value="new" <?= ($old['status'] == 'new') ? 'selected' : '' ?>>New</option>
                        <option value="contacted" <?= ($old['status'] == 'contacted') ? 'selected' : '' ?>>Contacted</option>
                        <option value="closed" <?= ($old['status'] == 'closed') ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>
            </div>

            <div class="edit-group">
                <label class="edit-label">Client Message / Notes</label>
                <textarea class="edit-input" name="message" placeholder="Optional notes..."><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            </div>

            <div class="edit-actions">
                <a href="?page=inquiries" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update Inquiry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Dynamic status field coloring
const statusField = document.getElementById('statusField');
function applyStatusColor() {
    statusField.classList.remove('text-green', 'text-blue', 'text-gray');
    if (statusField.value === 'new') statusField.classList.add('text-green');
    else if (statusField.value === 'contacted') statusField.classList.add('text-blue');
    else statusField.classList.add('text-gray');
}
statusField.addEventListener('change', applyStatusColor);
applyStatusColor();
</script>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>
