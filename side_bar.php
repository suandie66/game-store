<aside class="sidebar" id="sidebar">
    <!-- Tombol close -->
    <button class="sidebar-close" id="sidebar-close">&times;</button>

    <div class="sidebar-header">
        <a href="index.php" class="brand-logo">GameStore</a>
    </div>

    <nav class="sidebar-nav">
        <a href="index.php" class="nav-link <?= is_active('index.php', $current_page) ?>">
            <i class="icon-home"></i> <span>Beranda</span>
        </a>

        <a href="listing.php" class="nav-link <?= is_active('listing.php', $current_page) ?>">
            <i class="icon-tag"></i> <span>Semua Akun</span>
        </a>

        <?php if($user): ?>
            <div class="sidebar-divider"></div>
            <div class="sidebar-user-menu">
                <span class="sidebar-user-name">Halo, <?= htmlspecialchars($user['name']) ?></span>

                <?php if($user['is_admin']): ?>
                    <a href="admin_panel.php" class="nav-link <?= is_active('admin_panel.php', $current_page) ?>">
                        <i class="icon-dashboard"></i> <span>Admin: Listings</span>
                    </a>
                    <a href="admin_sales.php" class="nav-link <?= is_active('admin_sales.php', $current_page) ?>">
                        <i class="icon-cart"></i> <span>Admin: Penjualan</span>
                    </a>
                    <a href="admin_users.php" class="nav-link <?= is_active('admin_users.php', $current_page) ?>">
                        <i class="icon-users"></i> <span>Admin: Pengguna</span>
                    </a>
                <?php endif; ?>

                <a href="orders.php" class="nav-link <?= is_active('orders.php', $current_page) ?>">
                    <i class="icon-box"></i> <span>Pesanan Saya</span>
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="icon-logout"></i> <span>Logout</span>
                    
                </a>
            </div>
        <?php endif; ?>
    </nav>
</aside>