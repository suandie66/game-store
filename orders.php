<?php
require_once 'db.php';
require_once 'auth.php';
require_login($pdo);
include 'header.php';

$user = current_user($pdo);

if($user['is_admin']){
    // Admin query doesn't need image, kept as is.
    $stmt = $pdo->query("SELECT orders.*, listings.title AS nama, users.name 
        FROM orders 
        JOIN listings ON orders.listing_id=listings.id
        JOIN users ON orders.user_id=users.id
        ORDER BY orders.order_date DESC");
}else{
    // User query now fetches the primary image from listing_images table
    $stmt = $pdo->prepare("SELECT orders.*, l.title AS nama, l.price, 
        (SELECT image_path FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as primary_image
        FROM orders 
        JOIN listings l ON orders.listing_id=l.id
        WHERE orders.user_id=? ORDER BY orders.order_date DESC");
    $stmt->execute([$user['id']]);
}
$orders = $stmt->fetchAll();
?>

<style>
    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .page-header h1 { font-size: 2.5rem; }

    /* --- ADMIN (TABLE) VIEW --- */
    .table-container {
        background: var(--card-bg);
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .data-table th, .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .data-table th {
        background-color: #fafafa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85em;
    }
    .data-table tbody tr:hover { background-color: #fafafa; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .action-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    .btn-sm {
        padding: 0.3rem 0.7rem;
        font-size: 0.8em;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
     .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }


    /* --- USER (GRID) VIEW --- */
    .order-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 1rem;
    }
    .order-card {
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    @media (min-width: 768px) { .order-card { flex-direction: row; } }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    .order-card-img-wrapper {
        flex-shrink: 0;
        width: 100%;
        height: 180px;
    }
    @media (min-width: 768px) {
        .order-card-img-wrapper {
            width: 180px;
            height: auto;
        }
    }
    .order-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .order-card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .order-card-body h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.3rem;
    }
    .order-card-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    .order-card-details p {
        font-size: 0.9rem;
        color: #555;
        margin: 0.25rem 0;
    }
    .order-card-status {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
        border-top: 1px solid var(--border-color);
        width: 150px;
        margin-left: auto;
    }
    @media (min-width: 768px) {
        .order-card-status {
            border-top: none;
            border-left: 1px solid var(--border-color);
            background-color: transparent;
            width: 200px;
            margin-left: 0;
        }
    }
    .no-results-container {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--card-bg);
        border-radius: 16px;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
    }

    /* --- SHARED --- */
    .status-badge {
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 600;
        text-transform: capitalize;
        color: #fff;
    }
    .status-badge.status-active { background-color: #28a745; }
    .status-badge.status-inactive { background-color: #6c757d; }
    .status-badge.status-sold { background-color: #dc3545; }
    .status-badge.status-pending { background-color: #ffc107; color: var(--text-color); }
    .status-badge.status-confirmed { background-color: #28a745; }
    .status-badge.status-rejected { background-color: #dc3545; }
</style>

<div class="container page-container">
    <div class="page-header">
        <h1><?= $user['is_admin'] ? 'Kelola Pesanan Masuk' : 'Riwayat Pesanan Saya' ?></h1>
        <p><?= $user['is_admin'] ? 'Approve atau reject pesanan yang dibuat oleh pengguna.' : 'Lacak semua pesanan yang pernah Anda buat.' ?></p>
    </div>

    <?php if($user['is_admin']): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Pengguna</th>
                        <th>Metode Bayar</th>
                        <th class="text-center">Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                        <tr><td colspan="7" style="text-align: center; padding: 2rem;">Belum ada pesanan masuk.</td></tr>
                    <?php else: ?>
                        <?php foreach($orders as $o): ?>
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['nama']) ?></td>
                            <td><?= htmlspecialchars($o['name']) ?></td>
                            <td><?= htmlspecialchars($o['payment_method']) ?></td>
                            <td class="text-center"><span class="status-badge status-<?= strtolower(htmlspecialchars($o['status'])) ?>"><?= htmlspecialchars($o['status']) ?></span></td>
                            <td><?= date('d M Y, H:i', strtotime($o['order_date'])) ?></td>
                            <td class="action-cell text-right">
                                <?php if($o['status']=='pending'): ?>
                                    <a href="admin_order_update.php?id=<?= $o['id'] ?>&status=confirmed" class="btn btn-sm btn-success">Approve</a>
                                    <a href="admin_order_update.php?id=<?= $o['id'] ?>&status=rejected" class="btn btn-sm btn-danger">Reject</a>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="order-grid">
            <?php if (count($orders) > 0): ?>
                <?php foreach($orders as $o): ?>
                <div class="order-card">
                    <div class="order-card-img-wrapper">
                        <img src="<?= htmlspecialchars($o['primary_image'] ?: 'uploads/noimage.jpg') ?>" alt="<?= htmlspecialchars($o['nama']) ?>" class="order-card-img">
                    </div>
                    <div class="order-card-body">
                        <h3><?= htmlspecialchars($o['nama']) ?></h3>
                        <p class="order-card-price">Rp <?= number_format($o['price'], 0, ',', '.') ?></p>
                        <div class="order-card-details">
                            <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($o['payment_method']) ?></p>
                            <p><strong>Tanggal Order:</strong> <?= date('d M Y, H:i', strtotime($o['order_date'])) ?></p>
                        </div>
                    </div>
                    <div class="order-card-status">
                         <span class="status-badge status-<?= strtolower(htmlspecialchars($o['status'])) ?>"><?= htmlspecialchars($o['status']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results-container">
                    <h3>Anda belum punya pesanan</h3>
                    <p>Semua pesanan yang Anda buat akan muncul di halaman ini.</p>
                    <a href="listing.php" class="btn">Mulai Belanja</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>