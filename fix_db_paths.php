<?php
/**
 * Utility script to fix database image paths after deploying to hosting subdomain
 */
require_once __DIR__ . '/config/config.php';
$db = getDB();

header('Content-Type: text/html; charset=utf-8');

echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 30px; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>";
echo "<h2 style='color: #1e293b; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-top: 0;'>Database Path Fix Utility</h2>";

try {
    $db->beginTransaction();

    // 1. Fix gallery table
    $stmt1 = $db->prepare("UPDATE gallery SET 
        image_url = REPLACE(image_url, '/wedding/uploads/', '/uploads/'),
        thumb_url = REPLACE(thumb_url, '/wedding/uploads/', '/uploads/')");
    $stmt1->execute();
    $affected1 = $stmt1->rowCount();

    // 2. Fix love_stories table
    $stmt2 = $db->prepare("UPDATE love_stories SET 
        image_url = REPLACE(image_url, '/wedding/uploads/', '/uploads/')");
    $stmt2->execute();
    $affected2 = $stmt2->rowCount();

    // 3. Fix weddings table columns
    $stmt3 = $db->prepare("UPDATE weddings SET 
        bride_image = REPLACE(bride_image, '/wedding/uploads/', '/uploads/'),
        groom_image = REPLACE(groom_image, '/wedding/uploads/', '/uploads/'),
        music_url = REPLACE(music_url, '/wedding/uploads/', '/uploads/')");
    $stmt3->execute();
    $affected3 = $stmt3->rowCount();

    // 4. Fix weddings section_settings JSON column (handles escaped JSON slashes \/)
    $stmtSettings = $db->query("SELECT id, section_settings FROM weddings");
    $affectedSettings = 0;
    while ($row = $stmtSettings->fetch()) {
        $settings = $row['section_settings'];
        if (!empty($settings)) {
            $original = $settings;
            // Handle multiple forms of paths including escaped JSON slashes
            $settings = str_replace('\/wedding\/uploads\/', '\/uploads\/', $settings);
            $settings = str_replace('/wedding/uploads/', '/uploads/', $settings);
            $settings = str_replace('http:\/\/localhost\/wedding\/uploads\/', '\/uploads\/', $settings);
            $settings = str_replace('http://localhost/wedding/uploads/', '/uploads/', $settings);
            
            if ($settings !== $original) {
                $up = $db->prepare("UPDATE weddings SET section_settings = ? WHERE id = ?");
                $up->execute([$settings, $row['id']]);
                $affectedSettings++;
            }
        }
    }

    $db->commit();

    echo "<div style='background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 15px; border-radius: 6px; margin: 20px 0;'>";
    echo "<h3 style='margin: 0 0 8px 0;'>✓ Success! Database updated successfully.</h3>";
    echo "<p style='margin: 0; font-size: 0.9rem;'>All local path prefixes ('/wedding/uploads/') have been converted to live server paths ('/uploads/').</p>";
    echo "</div>";

    echo "<h4>Execution details:</h4>";
    echo "<ul>";
    echo "<li><strong>gallery</strong> table: $affected1 row(s) updated.</li>";
    echo "<li><strong>love_stories</strong> table: $affected2 row(s) updated.</li>";
    echo "<li><strong>weddings (images)</strong> table: $affected3 row(s) updated.</li>";
    echo "<li><strong>weddings (section settings JSON)</strong> table: $affectedSettings row(s) updated.</li>";
    echo "</ul>";
    
    echo "<p style='color: #475569; font-size: 0.9rem; margin-top: 20px;'>👉 You can now delete this file (<code>fix_db_paths.php</code>) from your hosting for security.</p>";

} catch (Exception $e) {
    $db->rollBack();
    echo "<div style='background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 15px; border-radius: 6px; margin: 20px 0;'>";
    echo "<h3 style='margin: 0 0 8px 0;'>❌ Error occurred:</h3>";
    echo "<p style='margin: 0; font-size: 0.9rem;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "</div>";
