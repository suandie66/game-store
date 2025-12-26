<?php
require_once 'auth.php';
$user = current_user($pdo);
$current_page = basename($_SERVER['PHP_SELF']);

function is_active($page_name, $current_page) {
    return $page_name === $current_page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Akun Game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --bg-color: #f9f9f9; /* Very light gray */
            --primary-color: #E57399; /* Pink */
            --secondary-color: #C25A7C; /* Darker Pink */
            --text-color: #121212; /* Nearly Black */
            --text-on-primary: #FFFFFF; /* White */
            --card-bg: #FFFFFF; /* White */
            --border-color: #eeeeee; /* Light gray for borders */
            --shadow-color: rgba(229, 115, 153, 0.2); /* Pink shadow */
            --danger-color: #dc3545; /* Keep red for danger */
            --font-family: 'Poppins', sans-serif;
        }

        /* --- Global & Animation --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: var(--font-family);
            background: var(--bg-color);
            color: var(--text-color);
            animation: fadeIn 0.5s ease-out;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 1.5rem;
            flex: 1;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        a { 
            color: var(--primary-color); 
            text-decoration: none; 
            transition: color 0.3s;
            font-weight: 600;
        }
        a:hover { 
            color: var(--secondary-color);
        }

        .btn {
            background: var(--primary-color);
            color: var(--text-on-primary);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px var(--shadow-color);
            background-color: var(--secondary-color);
        }
        .btn-secondary {
            background: var(--secondary-color);
        }
        .btn.reject, .btn-danger {
            background: var(--danger-color);
        }

        /* --- NEW HEADER --- */
        .main-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0.5rem 0;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
        }

        .brand-logo {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-color);
            margin-right: 1.5rem;
        }

        .main-nav {
            display: none;
        }

        @media (min-width: 768px) {
            .main-nav {
                display: flex;
                gap: 1.5rem;
            }
        }

        .nav-link {
            padding: 0.5rem 1rem;
            color: var(--text-color);
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s, color 0.3s;
            white-space: nowrap;
        }

        .nav-link:hover {
            background-color: #f4f4f4;
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: var(--text-on-primary);
        }


        .header-right {
            display: flex;
            align-items: center;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-actions .btn {
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="header-left">
                <a href="index.php" class="brand-logo">GameStore</a>
                <nav class="main-nav">
                    <a href="index.php" class="nav-link <?= is_active('index.php', $current_page) ?>">Beranda</a>
                    <a href="listing.php" class="nav-link <?= is_active('listing.php', $current_page) ?>">Jelajahi</a>
                </nav>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <?php if($user): ?>
                        <span class="header-user-name">Halo, <?= htmlspecialchars($user['name']) ?></span>
                        <?php if($user['is_admin']): ?>
                            <a href="admin_panel.php" class="nav-link <?= is_active('admin_panel.php', $current_page) ?>">Listings</a>
                            <a href="admin_sales.php" class="nav-link <?= is_active('admin_sales.php', $current_page) ?>">Laporan</a>
                            <a href="admin_users.php" class="nav-link <?= is_active('admin_users.php', $current_page) ?>">Pengguna</a>
                        <?php endif; ?>
                        <a href="orders.php" class="nav-link <?= is_active('orders.php', $current_page) ?>">Pesanan Saya</a>
                        <a href="logout.php" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary btn-sm">Login</a>
                        <a href="register.php" class="btn btn-primary btn-sm">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">

