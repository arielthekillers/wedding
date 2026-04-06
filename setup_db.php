<?php
/**
 * Database Setup Script (Updated)
 * Use this to initialize/update the wedding_db and tables.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS wedding_db");
    $pdo->exec("USE wedding_db");

    // Create Weddings Table with Bank Info
    $sql_weddings = "CREATE TABLE IF NOT EXISTS weddings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(255) NOT NULL UNIQUE,
        title VARCHAR(255) NOT NULL,
        is_enabled TINYINT(1) DEFAULT 1,
        start_date DATE,
        end_date DATE,
        theme_path VARCHAR(255) DEFAULT 'default',
        
        -- Bride Details
        bride_name VARCHAR(255),
        bride_nickname VARCHAR(100),
        bride_order VARCHAR(50),
        bride_father VARCHAR(255),
        bride_mother VARCHAR(255),
        
        -- Groom Details
        groom_name VARCHAR(255),
        groom_nickname VARCHAR(100),
        groom_order VARCHAR(50),
        groom_father VARCHAR(255),
        groom_mother VARCHAR(255),
        
        -- Event Details
        akad_date VARCHAR(100),
        akad_time VARCHAR(100),
        akad_place TEXT,
        
        reception_date VARCHAR(100),
        reception_time VARCHAR(100),
        reception_place TEXT,
        reception_map_link TEXT,

        -- Gift Info (New)
        bride_bank_name VARCHAR(100),
        bride_bank_acc VARCHAR(100),
        groom_bank_name VARCHAR(100),
        groom_bank_acc VARCHAR(100),
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_weddings);

    // Add/update bank columns
    $cols = $pdo->query("SHOW COLUMNS FROM weddings")->fetchAll(PDO::FETCH_COLUMN);

    $toAdd = [
        'bank1_owner' => 'VARCHAR(255)',
        'bank1_name'  => 'VARCHAR(100)',
        'bank1_acc'   => 'VARCHAR(100)',
        'bank2_owner' => 'VARCHAR(255)',
        'bank2_name'  => 'VARCHAR(100)',
        'bank2_acc'   => 'VARCHAR(100)',
    ];
    foreach ($toAdd as $col => $type) {
        if (!in_array($col, $cols)) {
            $pdo->exec("ALTER TABLE weddings ADD COLUMN $col $type");
            echo "Column $col added.\n";
        }
    }

    // Create Guestbook Table
    $sql_guestbook = "CREATE TABLE IF NOT EXISTS guestbook (
        id INT AUTO_INCREMENT PRIMARY KEY,
        wedding_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL DEFAULT '',
        message TEXT NOT NULL,
        attendance TINYINT(1) DEFAULT 1,
        ip_address VARCHAR(45) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (wedding_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_guestbook);

    // Add new guestbook columns if missing (for existing installs)
    $gbCols = $pdo->query("SHOW COLUMNS FROM guestbook")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('email', $gbCols))      $pdo->exec("ALTER TABLE guestbook ADD COLUMN email VARCHAR(255) NOT NULL DEFAULT '' AFTER name");
    if (!in_array('ip_address', $gbCols)) $pdo->exec("ALTER TABLE guestbook ADD COLUMN ip_address VARCHAR(45) DEFAULT NULL AFTER attendance");

    // Rate limit table: one record per IP per wedding per window
    $sql_ratelimit = "CREATE TABLE IF NOT EXISTS guestbook_rate_limit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        wedding_id INT NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        last_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_ip_wedding (wedding_id, ip_address)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_ratelimit);

    // Create Gallery Table
    $sql_gallery = "CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        wedding_id INT NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        caption VARCHAR(255),
        INDEX (wedding_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_gallery);

    // Invitation Categories
    $pdo->exec("CREATE TABLE IF NOT EXISTS invitation_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        wedding_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        INDEX (wedding_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Invitation Guests
    $pdo->exec("CREATE TABLE IF NOT EXISTS invitation_guests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        wedding_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        INDEX (wedding_id),
        INDEX (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo "Database structure initialized/updated successfully!\n";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
