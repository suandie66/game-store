<?php
require_once 'db.php';
require_once 'auth.php';
require_login($pdo);
include 'header.php';

$id = (int)($_GET['id'] ?? 0);

// Fetch item details along with the primary image path
$stmt = $pdo->prepare("
    SELECT 
        l.*, 
        (SELECT image_path FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as primary_image
    FROM listings l 
    WHERE l.id = ?
");
$stmt->execute([$id]);
$item = $stmt->fetch();

if(!$item){
    echo "<div class='container'><p class='error-message'>Listing tidak ditemukan.</p></div>";
    include 'footer.php';
    exit;
}

// Fetch all images for this listing, ordered so the primary is first
$stmt_images = $pdo->prepare("SELECT * FROM listing_images WHERE listing_id = ? ORDER BY id ASC");
$stmt_images->execute([$id]);
$all_images = $stmt_images->fetchAll();
?>

<style>
    /* --- NEW LISTING DETAIL PAGE --- */
    .listing-detail-card {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 16px;
        margin-top: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 8px 30px rgba(0,0,0,0.07);
    }
    .listing-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 992px) {
        .listing-detail-grid {
            grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
        }
    }
    /* Gallery Styles */
    .listing-gallery-container {
        display: flex;
        flex-direction: column;
    }
    .main-image-wrapper {
        width: 100%;
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
    }
    .main-image-item {
        width: 100%;
        height: auto;
        object-fit: cover;
        aspect-ratio: 16 / 10;
        display: block;
    }
    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 0.75rem;
    }
    .thumbnail-item .thumbnail-img {
        width: 100%;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.3s, transform 0.3s;
    }
    .thumbnail-item .thumbnail-img:hover {
        transform: scale(1.05);
    }
    .thumbnail-item .thumbnail-img.active {
        border-color: var(--primary-color);
        box-shadow: 0 0 10px var(--shadow-color);
    }
    /* Info & Action Styles */
    .listing-info-container {
        display: flex;
        flex-direction: column;
    }
    .listing-header .game-badge {
        background-color: var(--secondary-color);
        color: var(--text-on-primary);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 600;
        display: inline-block;
    }
    .listing-header .listing-title {
        font-size: 2.2rem;
        margin: 0.75rem 0;
        font-weight: 700;
    }
    .listing-header .listing-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }
    .listing-description {
        line-height: 1.7;
        color: #4a5568; 
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }
    .listing-description h3 {
        margin-bottom: 0.5rem;
    }
    .listing-action-box {
        background: #fafafa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: auto; 
    }
    .listing-action-box .form-group { margin-bottom: 1.5rem; }
    .listing-action-box label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }
    .listing-action-box select {
        width: 100%; 
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: var(--card-bg);
        font-size: 1em;
    }
    .listing-action-box .btn-buy-now {
        width: 100%;
        padding: 0.8rem;
        font-size: 1.1em;
    }
    .status-sold-out {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--danger-color);
        background-color: rgba(220, 53, 69, 0.1);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }
    .admin-actions { text-align: center; }
    .admin-notice {
        margin-bottom: 1rem;
        font-weight: 600;
        font-size: 1.1em;
    }
    .admin-actions .btn { margin: 0.25rem; }
    .back-link-wrapper {
        margin-top: 1.5rem;
        text-align: center;
    }
    .confirmation-message {
        text-align: center;
        padding: 2rem 1rem;
    }
    .confirmation-message p { font-weight: 600; }
    .hidden { display: none; }
    .spinner {
        width: 36px;
        height: 36px;
        margin: 0 auto 1rem;
        border: 4px solid #e0e0e0;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .error-message {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border: 1px solid var(--danger-color);
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
    }
</style>

<div class="container">
    <div class="listing-detail-card">
        <div class="listing-detail-grid">
            
            <!-- Kolom Kiri: Galeri Gambar -->
            <div class="listing-gallery-container">
                <div class="main-image-wrapper">
                    <img id="mainImage" src="<?= htmlspecialchars(empty($item['primary_image']) ? 'uploads/noimage.jpg' : $item['primary_image']) ?>" alt="Gambar utama" class="main-image-item">
                </div>
                <?php if (count($all_images) > 1): ?>
                <div class="thumbnail-grid">
                    <?php foreach ($all_images as $index => $img): ?>
                        <div class="thumbnail-item">
                            <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Thumbnail <?= $index + 1 ?>" class="thumbnail-img <?= $index === 0 ? 'active' : '' ?>" onclick="changeMainImage(this, '<?= htmlspecialchars($img['image_path']) ?>')">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Kolom Kanan: Info & Aksi -->
            <div class="listing-info-container">
                <div class="listing-header">
                    <span class="game-badge"><?= htmlspecialchars($item['game']) ?></span>
                    <h1 class="listing-title"><?= htmlspecialchars($item['title']) ?></h1>
                    <p class="listing-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                </div>

                <div class="listing-description">
                    <h3>Deskripsi Akun</h3>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                </div>

                <div class="listing-action-box">
                    <?php if(isset($user) && $user['is_admin']): ?>
                        <div class="admin-actions">
                            <p class="admin-notice">Anda melihat halaman ini sebagai admin.</p>
                            <a href="edit_listing.php?id=<?= $item['id'] ?>" class="btn btn-primary">Edit</a>
                            <a href="delete_listing.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus listing ini?')">Hapus</a>
                        </div>
                    <?php elseif($item['status']=='active'): ?>
                        <form id="buyForm" method="post" action="order_submit.php">
                            <input type="hidden" name="listing_id" value="<?= $item['id'] ?>">
                            <div class="form-group">
                                <label for="payment_method">Pilih Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" required>
                                    <option value="" disabled selected>--Pilih Opsi--</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="OVO">OVO</option>
                                    <option value="Gopay">Gopay</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-buy-now">Beli Sekarang</button>
                        </form>
                        <div id="confirmationMessage" class="confirmation-message hidden">
                            <div class="spinner"></div>
                            <p>Pesanan berhasil! Mohon tunggu konfirmasi dari admin.</p>
                        </div>
                    <?php else: ?>
                        <p class="status-sold-out"><strong>Status:</strong> Akun sudah terjual.</p>
                    <?php endif; ?>
                </div>
                 <div class="back-link-wrapper">
                    <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(thumbElement, newSrc) {
    document.getElementById('mainImage').src = newSrc;
    
    // Handle active thumbnail
    document.querySelectorAll('.thumbnail-img.active').forEach(el => el.classList.remove('active'));
    thumbElement.classList.add('active');
}

const form = document.getElementById('buyForm');
if (form) {
    const msg = document.getElementById('confirmationMessage');
    const container = form.parentElement;

    form.addEventListener('submit', function(e){
        e.preventDefault();
        
        // Hide form and show spinner
        form.classList.add('hidden');
        msg.classList.remove('hidden');

        fetch('order_submit.php', {
            method: 'POST',
            body: new FormData(form)
        }).then(res => res.text()).then(data => {
            msg.querySelector('p').textContent = 'Pesanan Anda telah berhasil dikirim! Silakan tunggu konfirmasi dari admin.';
        }).catch(error => {
            console.error('Error:', error);
            container.appendChild(form);
            form.classList.remove('hidden');
            msg.classList.add('hidden');
            alert('Terjadi kesalahan saat mengirim pesanan.');
        });
    });
}
</script>

<?php include 'footer.php'; ?>
