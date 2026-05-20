<?php
require_once __DIR__ . '/../config/config.php';
$db = getDB();

try {
    $tables = [];
    $q = $db->query("SHOW TABLES");
    while ($row = $q->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlDump = "-- Database Schema Export\n";
    $sqlDump .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
    $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    foreach ($tables as $table) {
        $sqlDump .= "-- --------------------------------------------------------\n";
        $sqlDump .= "-- Table structure for table `$table`\n";
        $sqlDump .= "-- --------------------------------------------------------\n\n";
        
        $createQ = $db->query("SHOW CREATE TABLE `$table`");
        $createRow = $createQ->fetch(PDO::FETCH_NUM);
        $sqlDump .= $createRow[1] . ";\n\n";

        // Let's also export data for the 'users' table so the default admin login is preserved!
        if ($table === 'users') {
            $sqlDump .= "-- Dumping data for table `$table`\n";
            $dataQ = $db->query("SELECT * FROM `$table`");
            while ($dataRow = $dataQ->fetch(PDO::FETCH_ASSOC)) {
                $cols = array_map(function($c) { return "`$c`"; }, array_keys($dataRow));
                $vals = array_map(function($v) use ($db) {
                    if ($v === null) return 'NULL';
                    return $db->quote($v);
                }, array_values($dataRow));
                $sqlDump .= "INSERT INTO `$table` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
            }
            $sqlDump .= "\n";
        }
    }

    $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

    file_put_contents(__DIR__ . '/../database.sql', $sqlDump);
    echo "Database schema and default admin user exported successfully to database.sql\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
