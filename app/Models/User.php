<?php
namespace Models;

use PDO;

class User {
    public static function authenticate($username, $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public static function create($username, $password, $role = 'client') {
        $db = getDB();
        $hash = password_hash($username, PASSWORD_DEFAULT); // Default password same as username
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
        }
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $role]);
    }
    
    public static function updatePassword($id, $new_password) {
        $db = getDB();
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }
    
    public static function delete($id) {
        $db = getDB();
        // The weddings table has ON DELETE SET NULL for user_id, so this is safe
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public static function exists($username) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
