<?php

function requireLogin() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        $_SESSION['flash_error'] = 'Please login to continue';
        header('Location: index.php?page=login');
        exit();
    }
}

function isLoggedIn() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function flashMessage() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    $html = '';
    
    if (isset($_SESSION['flash_success'])) {
        $html = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d1f0db; border-color: #00B140; color: #00B140;">' . 
                htmlspecialchars($_SESSION['flash_success']) . 
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['flash_success']);
    }
    
    if (isset($_SESSION['flash_error'])) {
        $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . 
                htmlspecialchars($_SESSION['flash_error']) . 
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['flash_error']);
    }
    
    return $html;
}
