<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);
include 'header.php';

$stmt = $pdo->query("SELECT id, title, game, price, status, created_at FROM listings ORDER BY created_at DESC");
$listings = $stmt->fetchAll();
?>

<style>
    /* --- ADMIN PANEL --- */
    .admin-page .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .admin-actions-header {
        margin-bottom: 1.5rem;
        text-align: right;
    }
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
        min-width: 800px;
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
    .data-table tbody tr:hover {
        background-color: #fafafa;
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    .text-right {
        text-align: right;
    }
    .btn-sm {
        padding: 0.3rem 0.7rem;
        font-size: 0.8em;
    }
    .action-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    .action-cell .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.9em;
    }

    /* Status Badges */
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

<div class="container admin-page">
    <div class="page-header">
        <h1>Admin Panel</h1>
        <p>Kelola daftar akun yang dijual di platform.</p>
    </div>

    <div class="admin-actions-header">
        <a href="create_listing.php" class="btn btn-primary">+ Buat Listing Baru</a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Game</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listings as $l): ?>
                <tr>
                    <td><?= $l['id'] ?></td>
                    <td><?= htmlspecialchars($l['title']) ?></td>
                    <td><?= htmlspecialchars($l['game']) ?></td>
                    <td>Rp <?= number_format($l['price'], 0, ',', '.') ?></td>
                    <td><span class="status-badge status-<?= strtolower(htmlspecialchars($l['status'])) ?>"><?= htmlspecialchars($l['status']) ?></span></td>
                    <td class="action-cell text-right">
                        <?php if($l['status'] == 'active' || $l['status'] == 'inactive'): ?>
                            <a href="edit_listing.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="delete_listing.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus listing ini?')">Hapus</a>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
