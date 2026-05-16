<?php
require_once __DIR__ . '/config/config.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE weddings ADD COLUMN story_intro TEXT NULL AFTER theme_path");
    $db->exec("ALTER TABLE weddings ADD COLUMN story_outro TEXT NULL AFTER story_intro");
    echo "Columns added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
