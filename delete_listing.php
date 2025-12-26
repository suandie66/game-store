<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$id = (int)($_GET['id'] ?? 0);

// Cek apakah ada order untuk listing ini
$stmt_check = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE listing_id=?");
$stmt_check->execute([$id]);
$order_count = $stmt_check->fetchColumn();

if ($order_count > 0) {
    include 'header.php';
    echo '
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Operasi Gagal</h1>
                <p>Listing ini tidak dapat dihapus.</p>
            </div>
            <div class="errors">
                <p>Alasan: Listing ini sudah memiliki riwayat transaksi dan tidak dapat dihapus untuk menjaga integritas data.</p>
            </div>
            <div class="auth-footer">
                <a href="admin_panel.php" class="btn btn-primary">Kembali ke Admin Panel</a>
            </div>
        </div>
    </div>
    ';
    include 'footer.php';
    exit;
}

// Ambil semua path gambar yang berelasi dengan listing ini
$pdo->beginTransaction();
try {
    // 1. Ambil gambar utama
    $stmt_primary = $pdo->prepare("SELECT image FROM listings WHERE id = ?");
    $stmt_primary->execute([$id]);
    $primary_image = $stmt_primary->fetchColumn();

    // 2. Ambil gambar tambahan
    $stmt_additional = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ?");
    $stmt_additional->execute([$id]);
    $additional_images = $stmt_additional->fetchAll(PDO::FETCH_COLUMN);

    // 3. Hapus listing dari DB (akan mentrigger ON DELETE CASCADE untuk listing_images)
    $stmt_delete = $pdo->prepare("DELETE FROM listings WHERE id=?");
    $stmt_delete->execute([$id]);
    
    $pdo->commit();

    // 4. Hapus file dari server setelah transaksi DB berhasil
    if ($primary_image && $primary_image !== 'uploads/noimage.jpg' && file_exists($primary_image)) {
        unlink($primary_image);
    }
    foreach ($additional_images as $img_path) {
        if ($img_path && file_exists($img_path)) {
            unlink($img_path);
        }
    }

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error saat menghapus listing: " . $e->getMessage());
}

header("Location: admin_panel.php?status=deleted");
exit;
