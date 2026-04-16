<?php
// public/setup.php
session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

$lockFile = __DIR__ . '/../storage/setup.lock';

if (file_exists($lockFile)) {
    die("Setup is locked. Please remove storage/setup.lock to run again.");
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Admin already exists.";
            } else {
                $hash = password_hash($password, PASSWORD_ARGON2ID);
                $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hash, $email]);

                file_put_contents($lockFile, "Setup completed on " . date('Y-m-d H:i:s'));
                $message = "Admin account created successfully! You can now <a href='index.php?page=login'>Login</a>.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaxSafar - Initial Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .setup-container { max-width: 500px; margin: 5rem auto; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header { background-color: #0c4a6e; color: white; border-bottom: none; }
        .btn-primary { background-color: #0284c7; border-color: #0284c7; }
        .btn-primary:hover { background-color: #0369a1; border-color: #0369a1; }
    </style>
</head>
<body>
    <div class="container setup-container">
        <div class="card">
            <div class="card-header text-center py-3">
                <h4 class="mb-0">TaxSafar Setup</h4>
            </div>
            <div class="card-body p-4">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <p class="text-muted mb-4">Create your first administrator account.</p>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
