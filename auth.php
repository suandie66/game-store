<?php
// WARNING: This file uses plaintext passwords, which is highly insecure.
// This is for local testing or debugging only.

require_once 'db.php';
session_start();

function register_user($pdo, $name, $email, $password, $is_admin = 0) {
    // Plaintext password insertion
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $password, $is_admin]);
}

function login_user($pdo, $email, $password) {
    // Plaintext password check
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    
    return false;
}

function current_user($pdo) {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function require_login($pdo) {
    if (!current_user($pdo)) {
        header("Location: login.php");
        exit;
    }
}

function require_admin($pdo) {
    $u = current_user($pdo);
    if (!$u || !$u['is_admin']) {
        http_response_code(403);
        echo "403 Forbidden - Admin only";
        exit;
    }
}
