<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);
include 'header.php';
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
        min-width: 600px;
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
    .text-right { text-align: right; }
    .text-center { text-align: center; }

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

    /* Role Badges */
    .role-badge {
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 600;
        text-transform: capitalize;
        color: #fff;
    }
    .role-badge.role-admin { background-color: var(--primary-color); }
    .role-badge.role-user { background-color: var(--secondary-color); }

    /* --- MESSAGES --- */
    .success-message, .error-message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        font-weight: 600;
    }
    .success-message {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .error-message {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border: 1px solid var(--danger-color);
    }
</style>

<div class="container admin-page">
    <div class="page-header">
        <h1>Manajemen Pengguna</h1>
        <p>Tambah, edit, atau hapus data pengguna sistem.</p>
    </div>

    <?php
    // Display and clear session messages
    if (isset($_SESSION['success_message'])) {
        echo '<div class="success-message">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    
    // Ambil semua user
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();
    ?>

    <div class="admin-actions-header">
        <a href="admin_add_user.php" class="btn btn-primary">+ Tambah Pengguna</a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th class="text-center">Role</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td class="text-center">
                        <?php if ($u['is_admin']): ?>
                            <span class="role-badge role-admin">Admin</span>
                        <?php else: ?>
                            <span class="role-badge role-user">User</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-cell text-right">
                        <a href="admin_edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="admin_delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
