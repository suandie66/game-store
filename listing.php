<?php
require_once 'db.php';
require_once 'auth.php';
include 'header.php';

// --- Filter Logic ---
$game_filter = $_GET['game'] ?? null;
$where_clause = 'WHERE status = :status';
$params = ['status' => 'active'];

if ($game_filter) {
    $where_clause .= ' AND l.game = :game';
    $params['game'] = $game_filter;
}

// --- Fetch Unique Game Names for Filtering ---
$stmt_games = $pdo->query("SELECT DISTINCT game FROM listings WHERE status='active' ORDER BY game ASC");
$games = $stmt_games->fetchAll(PDO::FETCH_COLUMN);

// --- Pagination Logic ---
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 28; // 7 rows * 4 columns
$offset = ($page - 1) * $items_per_page;

// --- Count Total Items for Pagination ---
$count_stmt = $pdo->prepare("SELECT COUNT(id) FROM listings l " . $where_clause);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// --- Fetch Listings for the Current Page ---
$sql = "SELECT l.*, (SELECT image_path FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as image FROM listings l " . $where_clause . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
// Bind other params
foreach ($params as $key => &$val) {
    $stmt->bindParam(':' . $key, $val);
}
$stmt->execute();
$listings = $stmt->fetchAll();

?>

<style>
    /* --- LISTING PAGE --- */
    .listing-page .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .listing-page .page-header h1 {
        font-size: 2.8rem;
        font-weight: 700;
    }
    .listing-page .page-header p {
        font-size: 1.1rem;
        color: #555;
        max-width: 500px;
        margin: 0.5rem auto 0;
    }

    /* Filter Menu */
    .filter-menu-container {
        margin-bottom: 2.5rem;
        display: flex;
        justify-content: center;
    }
    .filter-menu {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
        padding-bottom: 1rem;
    }
    .filter-item {
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-color);
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
    }
    .filter-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .filter-item.active {
        background: var(--primary-color);
        color: var(--text-on-primary);
        border-color: var(--primary-color);
        font-weight: 600;
        box-shadow: 0 4px 15px var(--shadow-color);
    }
    
    /* --- GRID & CARD --- */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .card {
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid var(--border-color);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .card .body {
        padding: 1rem;
        flex: 1;
        display: flex; 
        flex-direction: column; 
    }

    /* Card Overrides for Listing Page */
    .card-image-link {
        display: block;
        overflow: hidden;
    }
    .card-image-link img {
        width: 100%;
        height: auto;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        transition: transform 0.4s ease;
    }
    .card:hover .card-image-link img {
        transform: scale(1.05);
    }
    .card .game-tag {
        font-size: 0.85em;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    .card .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        flex-grow: 1;
    }
    .card .card-title a {
        color: var(--text-color);
        font-weight: 600;
        text-decoration: none;
    }
    .card .card-title a:hover {
        color: var(--primary-color);
    }
    .card .price {
        text-align: left;
        font-size: 1.4rem;
        margin-top: auto;
        padding-top: 0.5rem;
    }
    .btn.btn-primary-outline {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        width: 100%;
        margin-top: 1rem;
        text-decoration: none;
    }
    .btn.btn-primary-outline:hover {
        background: var(--primary-color);
        color: var(--text-on-primary);
    }

    /* No Results */
    .no-results-container {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--card-bg);
        border-radius: 16px;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
    }
    .no-results-container h3 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }
    .no-results-container p {
        color: #666;
        margin-bottom: 1.5rem;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }
    .pagination {
        list-style: none;
        display: flex;
        gap: 0.5rem;
        padding: 0;
    }
    .page-item .page-link {
        display: block;
        padding: 0.75rem 1.1rem;
        border-radius: 8px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-color);
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
    }
    .page-item .page-link:hover {
        background: #f4ece2;
        border-color: #d8cbb5;
    }
    .page-item.active .page-link {
        background: var(--primary-color);
        color: var(--text-on-primary);
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px var(--shadow-color);
        transform: translateY(-2px);
    }
</style>

<div class="container listing-page">
    <div class="page-header">
        <h1>Jelajahi Semua Akun</h1>
        <p>Temukan akun game impianmu dari koleksi pilihan kami.</p>
    </div>

    <!-- Filter Menu -->
    <div class="filter-menu-container">
        <div class="filter-menu">
            <a href="listing.php" class="filter-item <?= !$game_filter ? 'active' : '' ?>">Semua Game</a>
            <?php foreach ($games as $game_name): ?>
                <a href="listing.php?game=<?= urlencode($game_name) ?>" class="filter-item <?= ($game_filter === $game_name) ? 'active' : '' ?>">
                    <?= htmlspecialchars($game_name) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Listings Grid -->
    <?php if (count($listings) > 0): ?>
        <div class="grid listing-grid">
            <?php foreach($listings as $item): ?>
            <div class="card">
                <a href="listing_detail.php?id=<?= $item['id'] ?>" class="card-image-link">
                    <img src="<?= htmlspecialchars($item['image'] ?: 'uploads/noimage.jpg') ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                </a>
                <div class="body">
                    <span class="game-tag"><?= htmlspecialchars($item['game']) ?></span>
                    <h3 class="card-title">
                        <a href="listing_detail.php?id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a>
                    </h3>
                    <p class="price">Rp <?= number_format($item['price'],0,',','.') ?></p>
                    <a href="listing_detail.php?id=<?= $item['id'] ?>" class="btn btn-primary-outline">Lihat Detail</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-results-container">
            <h3>Tidak ada hasil</h3>
            <p>Sayangnya, tidak ada akun yang cocok dengan filter "<?= htmlspecialchars($game_filter) ?>".</p>
            <a href="listing.php" class="btn">Lihat Semua Game</a>
        </div>
    <?php endif; ?>


    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav class="pagination-container" aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                    <a class="page-link" href="listing.php?page=<?= $i ?><?= $game_filter ? '&game=' . urlencode($game_filter) : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>