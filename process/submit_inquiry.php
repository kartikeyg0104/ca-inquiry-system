<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// CSRF Validation
$csrf_token = $_POST['csrf_token'] ?? '';
if (empty($csrf_token) || $csrf_token !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['flash_error'] = "Invalid security token. Please try again.";
    header('Location: index.php#contact');
    exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$city = trim($_POST['city'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if (empty($full_name) || strlen($full_name) < 2 || strlen($full_name) > 150) {
    $errors[] = "Name must be between 2 and 150 characters";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required";
}

if (empty($mobile) || !preg_match('/^[6-9]\d{9}$/', $mobile)) {
    $errors[] = "Valid 10-digit Indian mobile number is required";
}

if (empty($city) || strlen($city) > 100) {
    $errors[] = "City must be provided and under 100 characters";
}

$allowed_services = [
    'GST Registration',
    'Income Tax Return Filing',
    'Company Incorporation',
    'TDS Return Filing',
    'Accounting & Bookkeeping',
    'ROC/MCA Compliance',
    'Virtual CFO Services',
    'Audit & Assurance'
];

if (empty($service) || !in_array($service, $allowed_services)) {
    $errors[] = "Please select a valid service";
}

if (!empty($message) && strlen($message) > 1000) {
    $errors[] = "Message cannot exceed 1000 characters";
}

if (!empty($errors)) {
    $_SESSION['flash_error'] = "Please fix the errors: " . implode(', ', $errors);
    $_SESSION['form_data'] = [
        'full_name' => $full_name,
        'email' => $email,
        'mobile' => $mobile,
        'city' => $city,
        'service' => $service,
        'message' => $message
    ];
    header('Location: index.php#contact');
    exit();
}

try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../src/models/Inquiry.php';

    $inquiry = new Inquiry($pdo);

    $success = $inquiry->create([
        'full_name' => htmlspecialchars($full_name),
        'email' => htmlspecialchars($email),
        'mobile' => htmlspecialchars($mobile),
        'city' => htmlspecialchars($city),
        'service' => htmlspecialchars($service),
        'message' => htmlspecialchars($message),
        'status' => 'new'
    ]);

    if ($success) {
        $_SESSION['flash_success'] = "Thank you! Your inquiry has been submitted. We'll contact you within 24 hours.";
        unset($_SESSION['form_data']);
    } else {
        $_SESSION['flash_error'] = "Something went wrong. Please try again.";
        $_SESSION['form_data'] = $_POST;
    }

} catch (Exception $e) {
    $_SESSION['flash_error'] = "Server error. Please try again later.";
    $_SESSION['form_data'] = $_POST;
}

header('Location: index.php#contact');
exit();
