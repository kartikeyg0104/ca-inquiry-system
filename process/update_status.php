<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../src/helpers/auth.php';

// Manual JSON auth guard since its Ajax
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken) || $csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit();
}

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
$status = trim($_POST['status'] ?? '');
$allowedStatuses = ['new', 'contacted', 'closed'];

if (!$id || !in_array($status, $allowedStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../src/models/Inquiry.php';

    $inquiryModel = new Inquiry($pdo);

    if (!$inquiryModel->getById($id)) {
        echo json_encode(['success' => false, 'message' => 'Inquiry not found']);
        exit();
    }

    $updated = $inquiryModel->updateStatus($id, $status);

    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query failed']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
