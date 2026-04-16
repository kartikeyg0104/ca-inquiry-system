<?php
session_start();

// Unset all variables
$_SESSION = array();

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Terminate Session
session_destroy();
session_unset();

// Start entirely new session for flash messaging
session_start();
session_regenerate_id(true);

$_SESSION['flash_success'] = "You have been logged out successfully.";
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

header('Location: index.php?page=login');
exit();
