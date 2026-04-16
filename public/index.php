<?php
session_start();

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page = $_GET['page'] ?? 'landing';

switch ($page) {
    case 'process_login':
        require_once __DIR__ . '/../process/admin_login.php';
        break;
    case 'process_status':
        require_once __DIR__ . '/../process/update_status.php';
        break;
    case 'process_update':
        require_once __DIR__ . '/../process/update_inquiry.php';
        break;
    case 'process_delete':
        require_once __DIR__ . '/../process/delete_inquiry.php';
        break;
    case 'process_submit':
        require_once __DIR__ . '/../process/submit_inquiry.php';
        break;
    case 'admin':
        require_once __DIR__ . '/../src/helpers/auth.php';
        requireLogin();
        require_once __DIR__ . '/../views/admin/dashboard.php';
        break;
    case 'inquiries':
        require_once __DIR__ . '/../src/helpers/auth.php';
        requireLogin();
        require_once __DIR__ . '/../views/admin/inquiries.php';
        break;
    case 'edit':
        require_once __DIR__ . '/../src/helpers/auth.php';
        requireLogin();
        require_once __DIR__ . '/../views/admin/edit.php';
        break;
    case 'login':
        require_once __DIR__ . '/../views/admin/login.php';
        break;
    case 'logout':
        require_once __DIR__ . '/../process/admin_logout.php';
        break;
    case '404':
        require_once __DIR__ . '/../views/errors/404.php';
        break;
    case '500':
        require_once __DIR__ . '/../views/errors/500.php';
        break;
    default:
        require_once __DIR__ . '/../views/landing/index.php';
        break;
}