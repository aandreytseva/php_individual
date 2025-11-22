<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

function getCurrentAdminUsername() {
    return $_SESSION['admin_username'] ?? null;
}

function loginAdmin($adminId, $username) {
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['admin_username'] = $username;
    $_SESSION['login_time'] = time();
    
    session_regenerate_id(true);
}

function logoutAdmin() {
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

function requireAuth($redirectUrl = '/public/login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit;
    }
}

function verifyAdminCredentials($username, $password) {
    $sql = "SELECT id, username, password_hash, email FROM admins WHERE username = ? LIMIT 1";
    $stmt = executeQuery($sql, [$username]);
    
    if (!$stmt) {
        return false;
    }
    
    $admin = $stmt->fetch();
    
    if (!$admin) {
        return false;
    }
    
    if (password_verify($password, $admin['password_hash'])) {
        return $admin;
    }
    
    return false;
}

function createAdmin($username, $password, $email) {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $sql = "INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)";
    $result = executeQuery($sql, [$username, $passwordHash, $email]);
    
    return $result !== false;
}

function usernameExists($username) {
    $sql = "SELECT COUNT(*) as count FROM admins WHERE username = ?";
    $stmt = executeQuery($sql, [$username]);
    
    if (!$stmt) {
        return false;
    }
    
    $result = $stmt->fetch();
    return $result['count'] > 0;
}

