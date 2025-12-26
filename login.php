<?php
require_once 'db.php';
require_once 'auth.php';

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=$_POST['email'];
    $password=$_POST['password'];
    if(login_user($pdo,$email,$password)){
        $user = current_user($pdo);
        if($user['is_admin']){
            header("Location: admin_panel.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $errors[]="Email atau password salah";
    }
}
include 'header.php';
?>

<style>
    /* --- AUTH PAGES (LOGIN/REGISTER) --- */
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem;
        min-height: 80vh;
    }
    .auth-card {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 450px;
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
    
    .errors {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border: 1px solid var(--danger-color);
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }
    .errors p { margin: 0; }

    .auth-form .form-group { margin-bottom: 1.25rem; }
    .auth-form label {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .auth-form input {
        width: 100%;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background-color: #f9f9f9;
        font-size: 1rem;
    }
     .auth-form input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--shadow-color);
        outline: none;
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
    .auth-footer p { color: #666; }
    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Selamat Datang</h1>
            <p>Masuk ke akun Anda untuk melanjutkan.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach($errors as $e): ?>
                    <p><?= $e ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="contoh@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        
        <div class="auth-footer">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>