<?php
require_once __DIR__ . '/config/config.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE weddings ADD COLUMN section_settings TEXT NULL");
    echo "Columns added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
