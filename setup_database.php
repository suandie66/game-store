<?php
// setup_database.php

// WARNING: This script will drop and recreate the database, deleting all existing data.
// Use with caution and only for initial setup.

require_once 'db.php';

echo <<<HTML
<div style="background-color: #fffbe6; border: 1px solid #ffe58f; padding: 15px; margin-bottom: 20px; border-radius: 4px; font-family: sans-serif;">
    <h3 style="margin-top: 0;">Important Notice for Existing Databases</h3>
    <p>The schema for the `listings` table has been updated. The dedicated `image` column is no longer used, as the application now correctly determines the primary image from the `listing_images` table.</p>
    <p>If you are running this script to set up a new database, no action is needed. If you have an existing database and are experiencing image issues, you should run the following SQL command to fix your schema:</p>
    <pre style="background-color: #f5f5f5; padding: 10px; border-radius: 4px;"><code>ALTER TABLE listings DROP COLUMN image;</code></pre>
</div>
HTML;

try {
    // --- 1. DROP & RECREATE DATABASE ---
    // Establish a connection without selecting a database
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db_name = 'game_store';

    $temp_pdo = new PDO("mysql:host=$host", $user, $pass);
    $temp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop the database if it exists, then create it
    $temp_pdo->exec("DROP DATABASE IF EXISTS `$db_name`");
    $temp_pdo->exec("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $temp_pdo->exec("USE `$db_name`");
    
    echo "Database 'game_store' created successfully.<br>";

    // --- 2. CREATE TABLES ---
    
    // a. Users Table
    $sql_users = "
    CREATE TABLE `users` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(255) NOT NULL,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL, -- Note: In a real app, use password_hash()
      `is_admin` TINYINT(1) DEFAULT 0,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    $temp_pdo->exec($sql_users);
    echo "Table 'users' created.<br>";

    // b. Listings Table
    $sql_listings = "
    CREATE TABLE `listings` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `game` VARCHAR(100),
      `description` TEXT NOT NULL,
      `price` DECIMAL(10, 2) NOT NULL,
      `status` VARCHAR(50) DEFAULT 'active', -- e.g., active, inactive, waiting, sold
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    $temp_pdo->exec($sql_listings);
    echo "Table 'listings' created.<br>";

    // c. Listing Images Table
    $sql_listing_images = "
    CREATE TABLE `listing_images` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `listing_id` INT NOT NULL,
      `image_path` VARCHAR(255) NOT NULL,
      FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    $temp_pdo->exec($sql_listing_images);
    echo "Table 'listing_images' created.<br>";

    // d. Orders Table
    $sql_orders = "
    CREATE TABLE `orders` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NOT NULL,
      `listing_id` INT NOT NULL,
      `payment_method` VARCHAR(100),
      `status` VARCHAR(50) DEFAULT 'pending', -- e.g., pending, completed, cancelled
      `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
      FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`)
    ) ENGINE=InnoDB;
    ";
    $temp_pdo->exec($sql_orders);
    echo "Table 'orders' created.<br>";

    // e. Auth Tokens Table
    $sql_auth_tokens = "
    CREATE TABLE `auth_tokens` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `selector` VARCHAR(255) NOT NULL,
      `hashed_validator` VARCHAR(255) NOT NULL,
      `user_id` INT NOT NULL,
      `expires` DATETIME NOT NULL,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    $temp_pdo->exec($sql_auth_tokens);
    echo "Table 'auth_tokens' created.<br>";

    // --- 3. SEED ADMIN USER ---
    // In a real scenario, use a more secure password.
    $admin_name = 'Admin';
    $admin_email = 'admin@gamestore.com';
    $admin_pass = 'admin123'; // Plaintext, for setup only.
    
    $stmt = $temp_pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, 1)");
    $stmt->execute([$admin_name, $admin_email, $admin_pass]);
    echo "Default admin user ('admin@gamestore.com', pass: 'admin123') created.<br>";


    echo "<hr><strong>Database setup complete!</strong> You can now use the application.";

} catch (PDOException $e) {
    // Use die() for critical errors during setup
    die("Database setup failed: " . $e->getMessage());
}

?>
<hr>
<p><b>SECURITY WARNING:</b> Please delete this file (<code>setup_database.php</code>) immediately after you are done.</p>
