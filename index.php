<?php
require_once 'db.php';
require_once 'auth.php';
// require_login($pdo); // Menonaktifkan login wajib untuk halaman utama
include 'header.php';

$stmt = $pdo->query("
    SELECT
        l.*,
        (SELECT image_path FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as image
    FROM
        listings l
    WHERE
        l.status = 'active'
    ORDER BY
        l.created_at DESC
    LIMIT 8
");
$listings = $stmt->fetchAll();
$user = current_user($pdo);
?>

<style>
    /* --- INDEX PAGE HERO --- */
    .hero-section {
        position: relative;
        background-size: cover;
        background-position: center;
        color: var(--text-on-primary);
        padding: 4rem 2rem;
        border-radius: 16px;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
        overflow: hidden;
        min-height: 400px;
        z-index: 1;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5); 
        z-index: -1;
        border-radius: 16px;
    }

    @media (min-width: 768px) {
        .hero-section {
            padding: 5rem 4rem;
        }
    }

    .hero-text {
        animation: fadeIn 1s ease-out;
    }

    .hero-text h1 {
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--text-on-primary);
        line-height: 1.2;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
    }

    .hero-text p {
        font-size: 1.1rem;
        margin: 1rem 0 2rem;
        max-width: 500px;
        opacity: 0.95;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .hero-buttons .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .hero-buttons .btn-secondary {
        background-color: transparent;
        border: 2px solid var(--text-on-primary);
        color: var(--text-on-primary);
    }
    .hero-buttons .btn:hover {
        transform: translateY(-3px) scale(1.05);
    }


    /* Recent Listings Section */
    .recent-listings-section {
        margin-bottom: 3rem;
    }
    .section-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .section-header h2 {
        font-size: 2.2rem;
    }
    .section-header p {
        font-size: 1.1rem;
        color: #555;
    }
    .section-footer {
        text-align: center;
        margin-top: 2.5rem;
    }

    /* --- GRID & CARD --- */
    .grid, .listing-grid {
        display: grid;
        grid-template-columns: 1fr; /* Default to 1 column for mobile */
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    /* Medium devices (tablets, 576px and up) */
    @media (min-width: 576px) {
        .listing-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) {
        .listing-grid {
            grid-template-columns: repeat(4, 1fr);
        }
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
    .card img {
        width: 100%;
        height: auto;
        aspect-ratio: 4 / 3;
        object-fit: cover;
    }
    .card .body {
        padding: 1rem;
        flex: 1;
        display: flex; 
        flex-direction: column; 
    }
    .card .body .card-title {
        flex-grow: 1;
    }
    .card .body a.btn {
        margin-top: auto;
    }
    /* Card Overrides for Listing Page */
    .listing-grid .card {
        display: flex;
        flex-direction: column;
    }
    .card-image-link {
        display: block;
        overflow: hidden;
    }
    .card-image-link img {
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
    }
    .card .card-title a:hover {
        color: var(--primary-color);
    }
    .card .price {
        text-align: left;
        font-size: 1.4rem;
    }
    .btn.btn-primary-outline {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        width: 100%;
        margin-top: 1rem;
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

    /* --- See More Button --- */
    .see-more {
        text-align: center;
        margin-top: 2rem;
    }
    .see-more .btn {
        padding: 0.6rem 1.2rem;
        font-size: 1em;
    }
</style>
<div class="container">
    <!-- Hero Section -->
    <div class="hero-section" style="background-image: url('uploads/cover.png');">
        <div class="hero-text fade-in">
            <h1>Pasar Akun Game #1</h1>
            <p>Temukan dan beli akun game favoritmu dengan aman, cepat, dan terpercaya.</p>
            <div class="hero-buttons">
                <a href="listing.php" class="btn btn-primary">Jelajahi Akun</a>
                <?php if (!$user): ?>
                    <a href="register.php" class="btn btn-secondary">Daftar Sekarang</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Listings Section -->
    <div class="recent-listings-section" id="listings">
        <div class="section-header">
            <h2>Baru Ditambahkan</h2>
            <p>Cek akun-akun terbaru yang siap untuk kamu miliki.</p>
        </div>

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

            <div class="section-footer">
                <a href="listing.php" class="btn btn-primary">Lihat Semua Akun</a>
            </div>
        <?php else: ?>
            <div class="no-results-container">
                <h3>Belum Ada Akun</h3>
                <p>Saat ini belum ada akun yang tersedia. Silakan cek kembali nanti.</p>
                 <?php if (isset($user) && $user['is_admin']): ?>
                    <a href="create_listing.php" class="btn">Tambah Listing</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>