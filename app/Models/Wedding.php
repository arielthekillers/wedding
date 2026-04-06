<?php
namespace Models;

use PDO;

class Wedding {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM weddings ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function getBySlug($slug) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM weddings WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = getDB();
        $fields = array_keys($data);
        $placeholders = array_map(function($f) { return ":$f"; }, $fields);
        $sql = "INSERT INTO weddings (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $sets = [];
        foreach ($data as $key => $val) {
            $sets[] = "$key = :$key";
        }
        $sql = "UPDATE weddings SET " . implode(',', $sets) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

    public static function delete($id) {
        $db = getDB();
        $db->prepare("DELETE FROM guestbook WHERE wedding_id = ?")->execute([$id]);
        $db->prepare("DELETE FROM gallery WHERE wedding_id = ?")->execute([$id]);
        $stmt = $db->prepare("DELETE FROM weddings WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Guestbook Methods
    public static function addMessage($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO guestbook (wedding_id, name, email, message, attendance, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['wedding_id'],
            $data['name'],
            $data['email'],
            $data['message'],
            $data['attendance'],
            $data['ip_address']
        ]);
    }

    public static function getMessages($wedding_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM guestbook WHERE wedding_id = ? ORDER BY created_at DESC");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll();
    }

    public static function deleteMessage($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM guestbook WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function isRateLimited($wedding_id, $ip, $seconds = 300) {
        $db = getDB();
        $stmt = $db->prepare("SELECT last_submitted FROM guestbook_rate_limit WHERE wedding_id = ? AND ip_address = ?");
        $stmt->execute([$wedding_id, $ip]);
        $row = $stmt->fetch();
        if (!$row) return false;
        $elapsed = time() - strtotime($row['last_submitted']);
        return $elapsed < $seconds;
    }

    public static function recordSubmission($wedding_id, $ip) {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO guestbook_rate_limit (wedding_id, ip_address)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE last_submitted = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$wedding_id, $ip]);
    }

    public static function gravatarUrl($email, $size = 60) {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=identicon";
    }

    // Gallery Methods
    public static function setGallery($wedding_id, $urls) {
        $db = getDB();
        $db->prepare("DELETE FROM gallery WHERE wedding_id = ?")->execute([$wedding_id]);
        $stmt = $db->prepare("INSERT INTO gallery (wedding_id, image_url) VALUES (?, ?)");
        foreach ($urls as $url) {
            if (!empty(trim($url))) {
                $stmt->execute([$wedding_id, trim($url)]);
            }
        }
    }

    public static function getGallery($wedding_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT image_url FROM gallery WHERE wedding_id = ?");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function slugExists($slug, $excludeId = 0) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM weddings WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $excludeId]);
        return $stmt->fetchColumn() > 0;
    }

    // ---- Guest List Methods ----
    public static function getGuestCategories($wedding_id) {
        $db = getDB();
        $cats = $db->prepare("SELECT * FROM invitation_categories WHERE wedding_id = ? ORDER BY id ASC");
        $cats->execute([$wedding_id]);
        $categories = $cats->fetchAll();

        foreach ($categories as &$cat) {
            $gs = $db->prepare("SELECT * FROM invitation_guests WHERE category_id = ? ORDER BY id ASC");
            $gs->execute([$cat['id']]);
            $cat['guests'] = $gs->fetchAll();
        }
        return $categories;
    }

    public static function saveGuestList($wedding_id, $categories) {
        $db = getDB();
        // Clear existing
        $db->prepare("DELETE FROM invitation_guests WHERE wedding_id = ?")->execute([$wedding_id]);
        $db->prepare("DELETE FROM invitation_categories WHERE wedding_id = ?")->execute([$wedding_id]);

        $catStmt   = $db->prepare("INSERT INTO invitation_categories (wedding_id, name) VALUES (?,?)");
        $guestStmt = $db->prepare("INSERT INTO invitation_guests (category_id, wedding_id, name) VALUES (?,?,?)");

        foreach ($categories as $catName => $names) {
            $catName = trim($catName);
            if (empty($catName)) continue;
            $catStmt->execute([$wedding_id, $catName]);
            $catId = $db->lastInsertId();
            foreach ($names as $name) {
                $name = trim($name);
                if ($name !== '') $guestStmt->execute([$catId, $wedding_id, $name]);
            }
        }
    }

    public static function getAllGuests($wedding_id) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT g.*, c.name AS category_name
            FROM invitation_guests g
            JOIN invitation_categories c ON g.category_id = c.id
            WHERE g.wedding_id = ?
            ORDER BY c.id ASC, g.id ASC
        ");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll();
    }
}
