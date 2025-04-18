<?php
/**
 * Authentication functions for Love View Estate admin
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Login user
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}