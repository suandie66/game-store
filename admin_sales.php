<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);
include 'header.php';

// --- Filter Logic ---
$selected_month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$selected_year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Query untuk mengambil rekap penjualan
$sql = "
    SELECT 
        o.id AS order_id,
        u.name AS customer_name,
        u.email AS customer_email,
        l.title AS item_name,
        l.price AS item_price,
        o.status AS order_status,
        o.order_date AS order_date
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN listings l ON o.listing_id = l.id
    WHERE (o.status = 'approved' OR o.status = 'confirmed')
      AND MONTH(o.order_date) = :month
      AND YEAR(o.order_date) = :year
    ORDER BY o.order_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['month' => $selected_month, 'year' => $selected_year]);
$sales = $stmt->fetchAll();

// Menghitung total pendapatan
$total_revenue = 0;
foreach ($sales as $sale) {
    $total_revenue += $sale['item_price'];
}
?>

<style>
    /* --- ADMIN/SHARED --- */
    .admin-page .page-header {
        text-align: center;
        margin-bottom: 2rem;
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
    .text-center { text-align: center; }

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

    /* --- ADMIN SALES PAGE --- */
    .summary-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .summary-card {
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }
    .summary-card-icon {
        font-size: 2.5rem;
        margin-right: 1.5rem;
        background-color: var(--primary-color);
        color: var(--text-on-primary);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .summary-card-info h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: #666;
    }
    .summary-card-info p {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-color);
    }

    /* Filter Form */
    .filter-container {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }
    .filter-form > div {
        flex-grow: 1;
    }
    .filter-form label {
        display: block;
        margin-bottom: .5rem;
        font-weight: 500;
        font-size: 0.9em;
    }
    .filter-form select, .filter-form input {
        width: 100%;
        padding: 0.6rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--input-bg);
        color: var(--text-color);
        box-sizing: border-box;
    }
    .filter-form button {
        background: var(--primary-color);
        color: var(--text-on-primary);
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s;
    }
    .filter-form button:hover {
        opacity: 0.9;
    }
</style>

<div class="container admin-page">
    <div class="page-header">
        <h1>Rekap Penjualan</h1>
        <p>Ringkasan semua transaksi yang telah berhasil di platform.</p>
    </div>

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="GET" action="" class="filter-form">
            <div>
                <label for="month">Pilih Bulan</label>
                <select name="month" id="month">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="year">Pilih Tahun</label>
                <select name="year" id="year">
                    <?php 
                    $current_year = date('Y');
                    for ($y = $current_year; $y >= $current_year - 5; $y--): 
                    ?>
                        <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <button type="submit">Filter</button>
            </div>

        </form>
    </div>

    <!-- Sales Summary -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-icon">
                <span>💰</span>
            </div>
            <div class="summary-card-info">
                <h4>Total Pendapatan (<?= date('F Y', mktime(0, 0, 0, $selected_month, 1, $selected_year)) ?>)</h4>
                <p>Rp <?= number_format($total_revenue, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon">
                <span>🛒</span>
            </div>
            <div class="summary-card-info">
                <h4>Transaksi Berhasil (<?= date('F Y', mktime(0, 0, 0, $selected_month, 1, $selected_year)) ?>)</h4>
                <p><?= count($sales) ?></p>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Harga</th>
                    <th class="text-center">Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 2rem;">Tidak ada data penjualan untuk periode yang dipilih.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td>#<?= $sale['order_id'] ?></td>
                            <td>
                                <div><?= htmlspecialchars($sale['customer_name']) ?></div>
                                <small><?= htmlspecialchars($sale['customer_email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($sale['item_name']) ?></td>
                            <td>Rp <?= number_format($sale['item_price'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="status-badge status-<?= strtolower(htmlspecialchars($sale['order_status'])) ?>"><?= htmlspecialchars($sale['order_status']) ?></span>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($sale['order_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
