<?php

if ($argc < 2) {
    echo "Usage: php generate_hash.php <password>\n";
    exit(1);
}

$password = $argv[1];

$hash = password_hash($password, PASSWORD_ARGON2ID, [
    'memory_cost' => 65536,
    'time_cost' => 4,
    'threads' => 1
]);

echo "Argon2id Hash generated successfully:\n";
echo "Password: $password\n";
echo "Hash: $hash\n";
echo "\nStore this hash safely in the database.sql script.\n";
