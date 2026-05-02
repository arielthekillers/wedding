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

    // Create Users Table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'client') DEFAULT 'client',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_users);

    // Insert Default Admin (if none exists)
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password, role) VALUES ('admin', '$hash', 'admin')");
        echo "Default admin user created (admin/admin).\n";
    }

    // Create Weddings Table with Bank Info
    $sql_weddings = "CREATE TABLE IF NOT EXISTS weddings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
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
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_weddings);

    // Add/update missing columns for existing installs
    $cols = $pdo->query("SHOW COLUMNS FROM weddings")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('user_id', $cols)) {
        $pdo->exec("ALTER TABLE weddings ADD COLUMN user_id INT DEFAULT NULL AFTER id");
        $pdo->exec("ALTER TABLE weddings ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
        echo "Column user_id added to weddings.\n";
    }

    $toAdd = [
        'bank1_owner' => 'VARCHAR(255)',
        'bank1_name'  => 'VARCHAR(100)',
        'bank1_acc'   => 'VARCHAR(100)',
        'bank2_owner' => 'VARCHAR(255)',
        'bank2_name'  => 'VARCHAR(100)',
        'bank2_acc'   => 'VARCHAR(100)',
        'music_url'   => 'VARCHAR(500)',
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
        image_url VARCHAR(500) NOT NULL,
        thumb_url VARCHAR(500) NOT NULL DEFAULT '',
        caption VARCHAR(255),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (wedding_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql_gallery);

    // Migrate existing gallery table — add thumb_url and sort_order if missing
    $galCols = $pdo->query("SHOW COLUMNS FROM gallery")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('thumb_url',   $galCols)) $pdo->exec("ALTER TABLE gallery ADD COLUMN thumb_url VARCHAR(500) NOT NULL DEFAULT '' AFTER image_url");
    if (!in_array('sort_order',  $galCols)) $pdo->exec("ALTER TABLE gallery ADD COLUMN sort_order INT DEFAULT 0 AFTER thumb_url");
    if (!in_array('created_at',  $galCols)) $pdo->exec("ALTER TABLE gallery ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

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

    // Love Stories Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS love_stories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        wedding_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        story_date DATE NOT NULL,
        content TEXT NOT NULL,
        image_url VARCHAR(500) DEFAULT '',
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (wedding_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo "Database structure initialized/updated successfully!\n";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
