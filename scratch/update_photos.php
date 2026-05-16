<?php
require 'config/config.php';
$db = getDB();
$res = $db->query("SELECT image_url FROM gallery LIMIT 1")->fetch();
echo "Sample image URL: " . ($res['image_url'] ?? 'NONE') . "\n";

$bride_url = "/wedding/uploads/profile/1/full/bride.png";
$groom_url = "/wedding/uploads/profile/1/full/groom.png";

$db->prepare("UPDATE weddings SET bride_image = ?, groom_image = ? WHERE id = 1")
   ->execute([$bride_url, $groom_url]);

echo "Updated wedding ID 1 with dummy photos.\n";
