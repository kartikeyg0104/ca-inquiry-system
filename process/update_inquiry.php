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
    header('Location: index.php?page=inquiries');
    exit();
}

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    $_SESSION['flash_error'] = "Invalid inquiry ID.";
    header('Location: index.php?page=inquiries');
    exit();
}

// Server-side validation
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$city = trim($_POST['city'] ?? '');
$service = trim($_POST['service'] ?? '');
$status = trim($_POST['status'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if (empty($full_name) || strlen($full_name) < 2 || strlen($full_name) > 150) $errors[] = "Name must be between 2 and 150 characters";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
if (empty($mobile) || !preg_match('/^[6-9]\d{9}$/', $mobile)) $errors[] = "Valid 10-digit Indian mobile number is required";
if (empty($city) || strlen($city) > 100) $errors[] = "City must be provided and under 100 characters";

$allowed_services = ['GST Registration', 'Income Tax Return Filing', 'Company Incorporation', 'TDS Return Filing', 'Accounting & Bookkeeping', 'ROC/MCA Compliance', 'Virtual CFO Services', 'Audit & Assurance'];
if (empty($service) || !in_array($service, $allowed_services)) $errors[] = "Please select a valid service";

$allowed_statuses = ['new', 'contacted', 'closed'];
if (empty($status) || !in_array($status, $allowed_statuses)) $errors[] = "Invalid status selected";

if (!empty($message) && strlen($message) > 1000) $errors[] = "Message cannot exceed 1000 characters";

if (!empty($errors)) {
    $_SESSION['flash_error'] = "Fix errors: " . implode(', ', $errors);
    $_SESSION['form_data'] = [
        'id' => $id, 'full_name' => $full_name, 'email' => $email, 'mobile' => $mobile, 'city' => $city, 'service' => $service, 'status' => $status, 'message' => $message
    ];
    header("Location: index.php?page=edit&id={$id}");
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/models/Inquiry.php';

try {
    $inquiry = new Inquiry($pdo);
    
    // Validate existence before updating
    $existing = $inquiry->getById($id);
    if (!$existing) {
        $_SESSION['flash_error'] = "Inquiry not found.";
        header('Location: index.php?page=inquiries');
        exit();
    }

    $inquiry->update($id, [
        'full_name' => htmlspecialchars($full_name),
        'email' => htmlspecialchars($email),
        'mobile' => htmlspecialchars($mobile),
        'city' => htmlspecialchars($city),
        'service' => htmlspecialchars($service),
        'status' => $status, // ENUM validated strictly above
        'message' => htmlspecialchars($message)
    ]);

    $_SESSION['flash_success'] = "Inquiry #{$id} updated successfully!";
    header('Location: index.php?page=inquiries');
    exit();

} catch (Exception $e) {
    $_SESSION['flash_error'] = "Database error. Please try again.";
    header("Location: index.php?page=edit&id={$id}");
    exit();
}
