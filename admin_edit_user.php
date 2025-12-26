<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_users.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: admin_users.php");
    exit;
}

$name = $user['name'];
$email = $user['email'];
$is_admin = $user['is_admin'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($name)) {
        $errors[] = 'Nama harus diisi.';
    }
    if (empty($email)) {
        $errors[] = 'Email harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar untuk pengguna lain.';
        }
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, is_admin = ? WHERE id = ?");
            $success = $stmt->execute([$name, $email, $hashed_password, $is_admin, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, is_admin = ? WHERE id = ?");
            $success = $stmt->execute([$name, $email, $is_admin, $id]);
        }

        if ($success) {
            header("Location: admin_users.php");
            exit;
        } else {
            $errors[] = 'Gagal memperbarui pengguna.';
        }
    }
}

include 'header.php';
?>

<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 2rem;
        min-height: calc(100vh - 150px); /* Adjust based on header/footer height */
    }
    .auth-card {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
        width: 100%;
        max-width: 550px;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }
    .auth-header p {
        font-size: 1rem;
        color: var(--text-muted);
    }
    .auth-form .form-group {
        margin-bottom: 1.5rem;
    }
    .auth-form label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.75rem;
        color: var(--text-color);
    }
    .auth-form input[type="text"],
    .auth-form input[type="email"],
    .auth-form input[type="password"] {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--input-bg);
        font-size: 1rem;
        color: var(--text-color);
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .auth-form input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.2);
    }
    .auth-form small {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }
    .form-group .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: 500;
    }
    .form-group .checkbox-label input {
        margin-right: 10px;
        height: 18px;
        width: 18px;
    }
    .btn-block {
        width: 100%;
        padding: 0.9rem;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1.5rem;
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
        transition: text-decoration 0.3s;
    }
    .auth-footer a:hover {
        text-decoration: underline;
    }
    .errors {
        background-color: rgba(255, 0, 0, 0.1);
        border: 1px solid red;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .errors p {
        margin: 0;
        color: red;
        font-weight: 500;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Edit Pengguna</h1>
            <p>Perbarui detail pengguna di bawah ini.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password">
                <small>Kosongkan jika Anda tidak ingin mengubah password.</small>
            </div>
            <div class="form-group">
                <label class="checkbox-label" for="is_admin">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" <?= $is_admin ? 'checked' : '' ?>>
                    <span>Jadikan sebagai Admin</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
        </form>
        <div class="auth-footer">
            <a href="admin_users.php">Kembali ke Daftar Pengguna</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
