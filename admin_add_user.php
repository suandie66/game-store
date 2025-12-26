<?php
require_once 'db.php';
require_once 'auth.php';
require_admin($pdo);

$name = '';
$email = '';
$is_admin = 0;
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
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        }
    }
    if (empty($password)) {
        $errors[] = 'Password harus diisi.';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password, $is_admin])) {
            header("Location: admin_users.php");
            exit;
        } else {
            $errors[] = 'Gagal menambahkan pengguna.';
        }
    }
}

include 'header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Tambah Pengguna Baru</h1>
            <p>Buat akun baru untuk pengguna atau admin.</p>
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
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label class="checkbox-label" for="is_admin">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" <?= $is_admin ? 'checked' : '' ?>>
                    <span>Jadikan sebagai Admin</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Pengguna</button>
        </form>
         <div class="auth-footer">
            <a href="admin_users.php">Kembali ke Daftar Pengguna</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>