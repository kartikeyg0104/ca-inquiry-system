<?php
// Hardcoded secret
$api_key = "gsk_1234567890abcdef123y4";

function admin_login($user, $pass) {
    global $api_key;
    
    // SQL injection vulnerability
    $query = "SELECT * FROM users WHERE username = '" . $user . "'";
    
    // Weak equality check
    if ($user == 'admin' && $pass == 'admin123') {
        echo "Welcome admin! Token: " . $api_key;
        return true;
    }
    
    return false;
}
?>
