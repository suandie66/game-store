<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$order_id = (int)($_GET['id'] ?? 0);
$new_status = $_GET['status'] ?? '';

if ($order_id > 0 && ($new_status === 'confirmed' || $new_status === 'rejected')) {
    
    // First, get the listing_id from the order
    $stmt_order = $pdo->prepare("SELECT listing_id FROM orders WHERE id = ?");
    $stmt_order->execute([$order_id]);
    $listing_id = $stmt_order->fetchColumn();

    if ($listing_id) {
        $pdo->beginTransaction();
        try {
            // Update the order status
            $stmt_update_order = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt_update_order->execute([$new_status, $order_id]);

            // Update the listing status based on the new order status
            if ($new_status === 'confirmed') {
                $listing_status = 'sold';
            } else { // rejected
                $listing_status = 'active';
            }
            $stmt_update_listing = $pdo->prepare("UPDATE listings SET status = ? WHERE id = ?");
            $stmt_update_listing->execute([$listing_status, $listing_id]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            // Optionally, log the error
            // error_log('Order update failed: ' . $e->getMessage());
            // For simplicity, we just won't update
        }
    }
}

header("Location: orders.php");
exit;
