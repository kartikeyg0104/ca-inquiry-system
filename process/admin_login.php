<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /?page=login');
    exit();
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (empty($csrf_token) || $csrf_token !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['flash_error'] = "Invalid security token. Please try again.";
    header('Location: /?page=login');
    exit();
}

// Rate Limiting (5 attempts / 15 minutes)
$max_attempts = 5;
$lockout_time = 15 * 60; 

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if ($_SESSION['login_attempts'] >= $max_attempts) {
    $time_passed = time() - $_SESSION['last_attempt_time'];
    if ($time_passed < $lockout_time) {
        $minutes_left = ceil(($lockout_time - $time_passed) / 60);
        $_SESSION['flash_error'] = "Too many failed attempts. Try again in {$minutes_left} minutes.";
        header('Location: /?page=login');
        exit();
    } else {
        // Reset after lockout period
        $_SESSION['login_attempts'] = 0;
    }
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$password = trim($_POST['password'] ?? '');

if (!$email || empty($password)) {
    $_SESSION['flash_error'] = "Valid email and password are required.";
    $_SESSION['login_email'] = $_POST['email'] ?? '';
    header('Location: /?page=login');
    exit();
}

try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../src/models/Admin.php';

    $adminModel = new Admin($pdo);
    $admin = $adminModel->findByEmail($email);

    if (!$admin) {
        sleep(1); // Timing attack defense
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
        $_SESSION['flash_error'] = "Invalid email or password.";
        header('Location: /?page=login');
        exit();
    }

    if (!$adminModel->verifyPassword($password, $admin['password'])) {
        sleep(1);
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
        $_SESSION['flash_error'] = "Invalid email or password.";
        header('Location: /?page=login');
        exit();
    }

    // SUCCESS
    session_regenerate_id(true); // Prevent Session Fixation

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Rotate CSRF on login

    unset($_SESSION['login_attempts']);
    unset($_SESSION['last_attempt_time']);

    $redirect = $_SESSION['redirect_after_login'] ?? '/?page=admin';
    unset($_SESSION['redirect_after_login']);

    header("Location: $redirect");
    exit();

} catch (Exception $e) {
    // Log error, don't expose to user
    $_SESSION['flash_error'] = "System error occurred. Try again later.";
    header('Location: /?page=login');
    exit();
}
