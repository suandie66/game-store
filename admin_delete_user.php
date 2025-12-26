<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$id = $_GET['id'] ?? null;
$current_user_id = $_SESSION['user_id'] ?? null;

if (!$id) {
    header("Location: admin_users.php");
    exit;
}

if ($id == $current_user_id) {
    // Optional: Add a message to the session to inform the user
    $_SESSION['error_message'] = "Anda tidak dapat menghapus akun Anda sendiri.";
    header("Location: admin_users.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

// Optional: Add a success message
$_SESSION['success_message'] = "Pengguna berhasil dihapus.";

header("Location: admin_users.php");
exit;
