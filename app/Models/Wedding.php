<?php
namespace Models;

use PDO;

class Wedding {
    public static function initDB() {
        static $initialized = false;
        if ($initialized) return;
        $db = getDB();
        
        // Add saweria_enabled column to weddings table if missing
        try {
            $check = $db->query("SHOW COLUMNS FROM weddings LIKE 'saweria_enabled'");
            if ($check->rowCount() === 0) {
                $db->exec("ALTER TABLE weddings ADD COLUMN saweria_enabled TINYINT(1) DEFAULT 0");
            }
        } catch (\PDOException $e) {}

        // Create saweria_gifts table for incoming Saweria webhook donations
        $db->exec("CREATE TABLE IF NOT EXISTS saweria_gifts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            wedding_id INT NOT NULL,
            saweria_id VARCHAR(100) NULL,
            donator_name VARCHAR(150) NOT NULL,
            donator_email VARCHAR(150) NULL,
            amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            message TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY (wedding_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Create saweria_intents table for pending unique nominal matching
        $db->exec("CREATE TABLE IF NOT EXISTS saweria_intents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            wedding_id INT NOT NULL,
            unique_amount DECIMAL(12,2) NOT NULL,
            donator_name VARCHAR(150) NOT NULL,
            message TEXT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY (unique_amount),
            KEY (wedding_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $initialized = true;
    }

    public static function getAll($user_id = null) {
        self::initDB();
        $db = getDB();
        if ($user_id) {
            $stmt = $db->prepare("SELECT * FROM weddings WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        } else {
            $stmt = $db->query("SELECT * FROM weddings ORDER BY created_at DESC");
            return $stmt->fetchAll();
        }
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
        $stmt = $db->prepare("INSERT INTO guestbook (wedding_id, name, email, circle, message, attendance, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['wedding_id'],
            $data['name'],
            $data['email'],
            $data['circle'] ?? null,
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
        
        // Ambil info pesan terlebih dahulu untuk mendapatkan wedding_id dan ip_address
        $stmt = $db->prepare("SELECT wedding_id, ip_address FROM guestbook WHERE id = ?");
        $stmt->execute([$id]);
        $msg = $stmt->fetch();
        
        if ($msg && !empty($msg['ip_address'])) {
            // Hapus record rate limit agar pengirim dengan IP ini bisa mengirim ulang segera
            $db->prepare("DELETE FROM guestbook_rate_limit WHERE wedding_id = ? AND ip_address = ?")->execute([
                $msg['wedding_id'],
                $msg['ip_address']
            ]);
        }
        
        $stmt = $db->prepare("DELETE FROM guestbook WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function replyMessage($id, $replyText) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE guestbook SET reply = ?, replied_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$replyText, $id]);
    }

    public static function hasEmailSubmitted($wedding_id, $email) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM guestbook WHERE wedding_id = ? AND LOWER(email) = ?");
        $stmt->execute([$wedding_id, strtolower(trim($email))]);
        return $stmt->fetchColumn() > 0;
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
        $stmt = $db->prepare("INSERT INTO gallery (wedding_id, image_url, thumb_url) VALUES (?, ?, '')");
        foreach ($urls as $url) {
            $url = trim($url);
            if (!empty($url)) {
                $stmt->execute([$wedding_id, $url]);
            }
        }
    }

    /**
     * Add a single uploaded image (with thumb) to the gallery.
     */
    public static function addGalleryImage($wedding_id, string $imageUrl, string $thumbUrl, int $sortOrder = 0): bool {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO gallery (wedding_id, image_url, thumb_url, sort_order) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$wedding_id, $imageUrl, $thumbUrl, $sortOrder]);
    }

    /**
     * Get all gallery rows (full row, including id and thumb_url).
     */
    public static function getGallery($wedding_id): array {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM gallery WHERE wedding_id = ? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll();
    }

    /**
     * Get only image_url strings (legacy compat used by theme).
     */
    public static function getGalleryUrls($wedding_id): array {
        $db = getDB();
        $stmt = $db->prepare("SELECT image_url FROM gallery WHERE wedding_id = ?");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Delete a single gallery image by its row ID.
     * Returns ['image_url'=>..., 'thumb_url'=>...] so caller can delete files.
     */
    public static function deleteGalleryImage(int $id): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT image_url, thumb_url FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $db->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
        return $row;
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

    // ── GUEST CRUD METHODS ──

    public static function addCategory($wedding_id, $name) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO invitation_categories (wedding_id, name) VALUES (?, ?)");
        return $stmt->execute([$wedding_id, trim($name)]);
    }

    public static function deleteCategory($id) {
        $db = getDB();
        $db->prepare("DELETE FROM invitation_guests WHERE category_id = ?")->execute([$id]);
        $stmt = $db->prepare("DELETE FROM invitation_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function addGuest($wedding_id, $category_id, $name) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO invitation_guests (wedding_id, category_id, name) VALUES (?, ?, ?)");
        return $stmt->execute([$wedding_id, $category_id, trim($name)]);
    }

    public static function updateGuest($id, $category_id, $name) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE invitation_guests SET category_id = ?, name = ? WHERE id = ?");
        return $stmt->execute([$category_id, trim($name), $id]);
    }

    public static function deleteGuest($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM invitation_guests WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getGuestStats($wedding_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM invitation_guests WHERE wedding_id = ?");
        $stmt->execute([$wedding_id]);
        $total = $stmt->fetchColumn();

        $stmt = $db->prepare("
            SELECT c.id, c.name as category_name, COUNT(g.id) as total
            FROM invitation_categories c
            LEFT JOIN invitation_guests g ON c.id = g.category_id
            WHERE c.wedding_id = ?
            GROUP BY c.id
        ");
        $stmt->execute([$wedding_id]);
        $by_category = $stmt->fetchAll();

        return ['total' => $total, 'by_category' => $by_category];
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

    // ---- Love Story Methods ----
    public static function getLoveStories($wedding_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM love_stories WHERE wedding_id = ? ORDER BY story_date ASC, id ASC");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll();
    }

    public static function getLoveStory($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM love_stories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function addLoveStory($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO love_stories (wedding_id, title, story_date, content, image_url, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['wedding_id'],
            $data['title'],
            $data['story_date'],
            $data['content'],
            $data['image_url'] ?? '',
            $data['sort_order'] ?? 0,
        ]);
    }

    public static function updateLoveStory($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE love_stories SET title=?, story_date=?, content=?, image_url=?, sort_order=? WHERE id=?");
        return $stmt->execute([
            $data['title'],
            $data['story_date'],
            $data['content'],
            $data['image_url'] ?? '',
            $data['sort_order'] ?? 0,
            $id,
        ]);
    }

    public static function getById($id) {
        self::initDB();
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM weddings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ---- Saweria Gift Methods ----
    public static function addSaweriaGift($data) {
        self::initDB();
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO saweria_gifts (wedding_id, saweria_id, donator_name, donator_email, amount, message) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['wedding_id'],
            $data['saweria_id'] ?? null,
            $data['donator_name'],
            $data['donator_email'] ?? null,
            $data['amount'],
            $data['message'] ?? ''
        ]);
    }

    public static function getSaweriaGifts($wedding_id) {
        self::initDB();
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM saweria_gifts WHERE wedding_id = ? ORDER BY created_at DESC");
        $stmt->execute([$wedding_id]);
        return $stmt->fetchAll();
    }

    public static function createSaweriaIntent($weddingId, $uniqueAmount, $donatorName, $message) {
        self::initDB();
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO saweria_intents (wedding_id, unique_amount, donator_name, message) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$weddingId, $uniqueAmount, $donatorName, $message]);
    }

    public static function findWeddingIdByUniqueAmount($amount) {
        self::initDB();
        $db = getDB();
        // Look for matching pending intent created within last 24 hours
        $stmt = $db->prepare("SELECT wedding_id, id FROM saweria_intents WHERE unique_amount = ? AND status = 'pending' AND created_at >= NOW() - INTERVAL 24 HOUR ORDER BY id DESC LIMIT 1");
        $stmt->execute([$amount]);
        $intent = $stmt->fetch();
        if ($intent) {
            // Mark intent as matched
            $db->prepare("UPDATE saweria_intents SET status = 'matched' WHERE id = ?")->execute([$intent['id']]);
            return (int)$intent['wedding_id'];
        }
        return 0;
    }

    public static function deleteLoveStory($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM love_stories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
