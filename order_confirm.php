<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$id = (int)($_GET['id']);
$reject = isset($_GET['reject']);

$stmt = $pdo->prepare("SELECT listing_id FROM orders WHERE id=?");
$stmt->execute([$id]);
$listing_id = $stmt->fetchColumn();

if($reject){
    // reject order, kembalikan listing jadi active
    $pdo->prepare("UPDATE orders SET status='rejected' WHERE id=?")->execute([$id]);
    $pdo->prepare("UPDATE listings SET status='active' WHERE id=?")->execute([$listing_id]);
}else{
    // approve, tandai listing sold
    $pdo->prepare("UPDATE orders SET status='approved' WHERE id=?")->execute([$id]);
    $pdo->prepare("UPDATE listings SET status='sold' WHERE id=?")->execute([$listing_id]);
}

header("Location: orders.php");
exit;
