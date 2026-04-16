<?php
session_start();
require_once __DIR__ . '/../src/helpers/auth.php';
requireLogin();

// CSRF Validation 
$csrf_token = $_POST['csrf_token'] ?? '';
if (empty($csrf_token) || $csrf_token !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['flash_error'] = "Invalid security token.";
    header('Location: index.php?page=inquiries');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = "Method not allowed.";
    header('Location: index.php?page=inquiries');
    exit();
}

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    $_SESSION['flash_error'] = "Invalid inquiry ID.";
    header('Location: index.php?page=inquiries');
    exit();
}

try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../src/models/Inquiry.php';

    $inquiryModel = new Inquiry($pdo);

    // Verify existence (prevent phantom deletes)
    if (!$inquiryModel->getById($id)) {
        $_SESSION['flash_error'] = "Inquiry #{$id} does not exist.";
        header('Location: index.php?page=inquiries');
        exit();
    }

    $inquiryModel->delete($id);
    $_SESSION['flash_success'] = "Inquiry deleted successfully.";

} catch (Exception $e) {
    $_SESSION['flash_error'] = "Could not delete inquiry. Please try again.";
}

header('Location: index.php?page=inquiries');
exit();
