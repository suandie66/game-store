<?php
require_once 'db.php';
require_once 'auth.php';
require_login($pdo);

if($_SERVER['REQUEST_METHOD']!=='POST'){
    http_response_code(400);
    exit('Invalid request');
}

$user = current_user($pdo);
$listing_id = (int)($_POST['listing_id']);
$payment_method = $_POST['payment_method'] ?? '';

// cek listing masih active
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id=? AND status='active'");
$stmt->execute([$listing_id]);
$item = $stmt->fetch();
if(!$item){
    http_response_code(400);
    exit('Akun tidak tersedia');
}

// insert order
$stmt = $pdo->prepare("INSERT INTO orders (user_id, listing_id, payment_method, status) VALUES (?,?,?,?)");
$stmt->execute([$user['id'],$listing_id,$payment_method,'pending']);

// ubah listing jadi waiting (tidak muncul di daftar)
$stmt = $pdo->prepare("UPDATE listings SET status='waiting' WHERE id=?");
$stmt->execute([$listing_id]);

echo 'success'; // respon ajax
