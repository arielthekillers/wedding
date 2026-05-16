<?php
require 'config/config.php';
$db = getDB();
$res = $db->query("SELECT id, slug, start_date, akad_date FROM weddings WHERE slug = 'antodanita'")->fetch();
print_r($res);
echo "\nCurrent Server Time: " . date('Y-m-d H:i:s') . "\n";
