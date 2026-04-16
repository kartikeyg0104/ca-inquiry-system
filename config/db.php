<?php
require_once __DIR__ . '/app.php';

$host = DB_HOST;
$db   = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$port = DB_PORT;

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    if (APP_ENV === 'development') {
        die("<strong>Database Connection Failed!</strong><br><br>Error: " . $e->getMessage());
    } else {
        // Prevent credentials leaking in production
        error_log("Database connection error: " . $e->getMessage());
        header('Location: ' . BASE_URL . '/index.php?page=500');
        exit();
    }
}
