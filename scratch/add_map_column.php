<?php
require 'config/config.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE weddings ADD COLUMN akad_map_link VARCHAR(500) AFTER reception_map_link");
    echo "Column akad_map_link added successfully.\n";
} catch (Exception $e) {
    echo "Error or already exists: " . $e->getMessage() . "\n";
}
