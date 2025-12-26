<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_panel.php");
    exit;
}

// --- IMAGE ACTIONS ---
// Handle setting a new primary image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_primary'])) {
    $image_to_promote_id = $_POST['set_primary'];

    // Get the current primary image ID from the main query
    $stmt_current_primary = $pdo->prepare("SELECT id FROM listing_images WHERE listing_id = ? ORDER BY id ASC LIMIT 1");
    $stmt_current_primary->execute([$id]);
    $current_primary_id = $stmt_current_primary->fetchColumn();

    if ($image_to_promote_id && $current_primary_id && $image_to_promote_id != $current_primary_id) {
        
        // To "promote" an image, we make its ID the lowest. The easiest way without changing all IDs
        // is to swap the image paths between the current primary and the one to be promoted.
        
        $pdo->beginTransaction();
        try {
            // Get paths of both images
            $stmt_paths = $pdo->prepare("SELECT id, image_path FROM listing_images WHERE id IN (?, ?)");
            $stmt_paths->execute([$current_primary_id, $image_to_promote_id]);
            $images = $stmt_paths->fetchAll(PDO::FETCH_KEY_PAIR);

            $path_of_current_primary = $images[$current_primary_id];
            $path_of_image_to_promote = $images[$image_to_promote_id];

            // Swap the paths
            $stmt_swap1 = $pdo->prepare("UPDATE listing_images SET image_path = ? WHERE id = ?");
            $stmt_swap1->execute([$path_of_image_to_promote, $current_primary_id]);

            $stmt_swap2 = $pdo->prepare("UPDATE listing_images SET image_path = ? WHERE id = ?");
            $stmt_swap2->execute([$path_of_current_primary, $image_to_promote_id]);
            
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Error swapping images: " . $e->getMessage());
        }
    }
    header("Location: edit_listing.php?id=$id");
    exit;
}

// Handle image deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $image_id_to_delete = $_POST['delete_image'];

    // To prevent deleting the last image, count images first.
    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM listing_images WHERE listing_id = ?");
    $stmt_count->execute([$id]);
    $image_count = $stmt_count->fetchColumn();

    if ($image_count > 1) {
        $stmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE id = ? AND listing_id = ?");
        $stmt->execute([$image_id_to_delete, $id]);
        $image_path = $stmt->fetchColumn();

        if ($image_path) {
            // Delete file from server
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM listing_images WHERE id = ?");
            $stmt->execute([$image_id_to_delete]);
        }
    }
    // If it's the last image, do nothing.
    header("Location: edit_listing.php?id=$id");
    exit;
}

// --- FORM PROCESSING & DATA FETCHING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['set_primary']) && !isset($_POST['delete_image'])) {
    // Process form submission
    $title = trim($_POST['title'] ?? '');
    $game = trim($_POST['game'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? 0);
    $status = $_POST['status'] ?? 'inactive';

    // Update the listing
    $stmt = $pdo->prepare("UPDATE listings SET title = ?, game = ?, description = ?, price = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $game, $description, $price, $status, $id]);

    // Handle new image uploads
    if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
        $images = $_FILES['additional_images'];
        $upload_dir = 'uploads/';

        foreach ($images['name'] as $key => $name) {
            if ($images['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $images['tmp_name'][$key];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $new_filename = uniqid() . '_' . time() . '.' . $ext;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($tmp_name, $destination)) {
                    $stmt = $pdo->prepare("INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)");
                    $stmt->execute([$id, $destination]);
                }
            }
        }
    }
    header("Location: edit_listing.php?id=$id");
    exit;
}


// Fetch item details along with the primary image
$stmt = $pdo->prepare("
    SELECT 
        l.*, 
        (SELECT image_path FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as primary_image,
        (SELECT id FROM listing_images WHERE listing_id = l.id ORDER BY id ASC LIMIT 1) as primary_image_id
    FROM listings l 
    WHERE l.id = ?
");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: admin_panel.php");
    exit;
}

// Fetch additional images (excluding the primary one)
$stmt_images = $pdo->prepare("SELECT * FROM listing_images WHERE listing_id = ? AND id != ? ORDER BY id ASC");
$stmt_images->execute([$id, $item['primary_image_id']]);
$additional_images = $stmt_images->fetchAll();

include 'header.php';
?>

<style>
    /* --- ADMIN/FORM SHARED --- */
    .admin-page .page-header { text-align: center; margin-bottom: 2rem; }
    .auth-card {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
        width: 100%;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    form label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }
    form input[type="text"], form input[type="number"], form textarea, form select, form input[type="file"] {
        width: 100%; 
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: #fafafa;
        font-size: 1em;
    }
    form textarea { resize: vertical; min-height: 120px; }
    .form-group { margin-bottom: 1.5rem; }
    .btn-block {
        width: 100%;
        padding: 0.8rem;
        font-size: 1rem;
        margin-top: 1rem;
    }
     .auth-footer {
        text-align: center;
        margin-top: 2rem;
        font-size: 0.95rem;
    }
    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    /* --- EDIT LISTING PAGE --- */
    .edit-listing-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 1024px) {
        .edit-listing-grid {
            grid-template-columns: 2fr 1fr;
        }
    }
    .image-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }
    .image-gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 3px solid transparent;
    }
    .image-gallery-item.primary {
        border-color: var(--primary-color);
    }
    .image-gallery-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        display: block;
    }
    .image-label {
        position: absolute;
        top: 0;
        left: 0;
        background: var(--primary-color);
        color: white;
        padding: 0.2rem 0.5rem;
        font-size: 0.8em;
        font-weight: 600;
        border-bottom-right-radius: 8px;
    }
    .image-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        justify-content: center;
        gap: 5px;
        padding: 5px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .image-gallery-item:hover .image-actions {
        opacity: 1;
    }
    .image-actions form {
        margin: 0;
    }
    .image-actions .btn-action {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 0.75em;
        padding: 0.3rem 0.5rem;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
    }
    .image-actions .btn-action.delete { background-color: var(--danger-color); }
    .image-actions .btn-action.primary { background-color: var(--secondary-color); }
</style>

<div class="container admin-page">
    <div class="page-header">
        <h1>Edit Listing: <?= htmlspecialchars($item['title']) ?></h1>
        <p>Perbarui detail item dan kelola gambar galeri.</p>
    </div>

    <div class="edit-listing-grid">
        <!-- Form Card -->
        <div class="auth-card">
            <div class="auth-header">
                <h3>Detail Item</h3>
            </div>
            <form method="post" enctype="multipart/form-data" class="auth-form">
                <div class="form-group"><label for="title">Judul</label><input type="text" name="title" id="title" value="<?= htmlspecialchars($item['title']) ?>" required></div>
                <div class="form-group"><label for="game">Game</label><input type="text" name="game" id="game" value="<?= htmlspecialchars($item['game']) ?>" required></div>
                <div class="form-group"><label for="description">Deskripsi</label><textarea name="description" id="description" rows="5" required><?= htmlspecialchars($item['description']) ?></textarea></div>
                <div class="form-group"><label for="price">Harga (Rp)</label><input type="text" name="price" id="price" inputmode="numeric" value="<?= htmlspecialchars(number_format($item['price'], 0, ',', '.')) ?>" required></div>
                <div class="form-group"><label for="status">Status</label><select name="status" id="status"><option value="active" <?= $item['status']=='active'?'selected':'' ?>>Active</option><option value="inactive" <?= $item['status']=='inactive'?'selected':'' ?>>Inactive</option></select></div>
                <div class="form-group"><label for="additional_images">Tambah Gambar Baru</label><input type="file" name="additional_images[]" id="additional_images" multiple accept="image/*"><small>Anda bisa memilih lebih dari satu gambar.</small></div>
                <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan & Upload</button>
            </form>
             <div class="auth-footer">
                <a href="admin_panel.php">Kembali ke Admin Panel</a>
            </div>
        </div>

        <!-- Image Gallery Card -->
        <div class="auth-card">
             <div class="auth-header">
                <h3>Galeri Gambar</h3>
            </div>
            <div class="image-gallery-grid">
                <!-- Gambar Utama -->
                <div class="image-gallery-item primary">
                    <span class="image-label">Utama</span>
                    <img src="<?= htmlspecialchars($item['primary_image'] ?: 'uploads/noimage.jpg') ?>" alt="Gambar Utama">
                </div>

                <!-- Gambar Tambahan -->
                <?php foreach ($additional_images as $img): ?>
                    <div class="image-gallery-item">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Gambar Tambahan">
                        <div class="image-actions">
                            <form method="post" onsubmit="return confirm('Jadikan gambar ini sebagai gambar utama?');">
                                <button type="submit" name="set_primary" value="<?= $img['id'] ?>" class="btn-action primary" title="Jadikan Gambar Utama">Utama</button>
                            </form>
                            <form method="post" onsubmit="return confirm('Yakin ingin menghapus gambar ini?');">
                                <button type="submit" name="delete_image" value="<?= $img['id'] ?>" class="btn-action delete" title="Hapus Gambar">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>