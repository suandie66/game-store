<?php
require_once 'db.php';
require_once 'auth.php';

// Logic to handle the POST request must be at the very top, before any HTML output.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Authenticate the user for this action
    require_admin($pdo);

    $title = $_POST['title'];
    $game = $_POST['game'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    
    $pdo->beginTransaction();

    try {
        // 1. Insert the listing
        $stmt = $pdo->prepare("INSERT INTO listings (title, game, description, price, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $game, $description, $price, $status]);
        $listing_id = $pdo->lastInsertId();

        // 2. Handle image uploads
        if (isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
            $image_paths = [];
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file_extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $file_name = uniqid('img_', true) . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $image_paths[] = $target_file;
                    } else {
                        throw new Exception("Gagal memindahkan file yang di-upload.");
                    }
                }
            }
            
            // 3. Insert image paths into the database
            if (!empty($image_paths)) {
                $stmt_img = $pdo->prepare("INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)");
                foreach ($image_paths as $path) {
                    $stmt_img->execute([$listing_id, $path]);
                }
            }
        }
        
        $pdo->commit();

        // Redirect on success
        header("Location: admin_panel.php?status=listing_created");
        exit(); // Stop script execution after redirect

    } catch (Exception $e) {
        $pdo->rollBack();
        // If an error occurs, stop everything and show the error.
        die("Error: Tidak dapat membuat listing. " . $e->getMessage());
    }
}

// If it's a GET request, show the form.
// First, make sure the user is an admin.
require_admin($pdo);

// Then, include the HTML header.
include 'header.php';
?>
<style>
    /* --- AUTH/FORM PAGE STYLING --- */
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 0;
        min-height: 80vh;
    }
    .auth-card {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 800px;
        border: 1px solid var(--border-color);
    }
    @media (min-width: 576px) { .auth-card { padding: 2.5rem; } }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .auth-header p { color: #666; }

    .auth-form .form-group {
        margin-bottom: 1.5rem;
    }
    form label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }
    form input[type="text"],
    form input[type="number"],
    form input[type="email"],
    form input[type="password"],
    form input[type="file"], 
    form textarea,
    form select {
        width: 100%; 
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: #fafafa;
        color: var(--text-color);
        font-size: 1em;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    form input:focus,
    form textarea:focus,
    form select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--shadow-color);
        outline: none;
    }
    form textarea {
        resize: vertical; 
        min-height: 120px;
    }
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
    .help-text {
        font-size: 0.9em;
        color: #666;
        display: block;
        margin-top: 0.5rem;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Buat Listing Baru</h1>
            <p>Isi detail akun yang akan dijual di bawah ini.</p>
        </div>

        <form method="post" enctype="multipart/form-data" class="auth-form">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" placeholder="Cth: Akun Genshin Impact AR 58" required>
            </div>
            
            <div class="form-group">
                <label for="game">Game</label>
                <input type="text" name="game" id="game" placeholder="Cth: Genshin Impact, Valorant" required>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="5" placeholder="Jelaskan detail akun, server, item, dll." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="text" name="price" id="price" inputmode="numeric" placeholder="Cth: 2.000.000" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="active">Active (Langsung tampil di toko)</option>
                    <option value="inactive">Inactive (Simpan sebagai draft)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="images">Gambar</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*">
                <small class="help-text">Gambar pertama akan menjadi gambar utama. Anda bisa memilih lebih dari satu.</small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Simpan Item</button>
        </form>
         <div class="auth-footer">
            <a href="admin_panel.php">Kembali ke Admin Panel</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>