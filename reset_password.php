<?php
/**
 * One-time script to reset admin credentials.
 * ACCESS THIS FILE VIA BROWSER, THEN DELETE IT IMMEDIATELY!
 * URL: https://wedding.sintesacorp.id/wedding/reset_password.php
 */

require_once __DIR__ . '/app/bootstrap.php';

$db = Models\Database::connect();

$newUsername = 'admin123';
$newPassword = '123admin';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the first admin user
$stmt = $db->prepare("UPDATE users SET username = ?, password = ? WHERE id = 1");
$stmt->execute([$newUsername, $hash]);

if ($stmt->rowCount() > 0) {
    echo "✅ Berhasil! Username dan password sudah diperbarui.<br>";
    echo "Username: <b>{$newUsername}</b><br>";
    echo "Password: <b>{$newPassword}</b><br><br>";
    echo "⚠️ <b style='color:red;'>HAPUS FILE INI SEGERA SETELAH DIGUNAKAN!</b>";
} else {
    echo "❌ Tidak ada user dengan id=1. Coba cek tabel users di database.";
}
