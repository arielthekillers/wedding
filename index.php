<?php
require_once __DIR__ . '/config/config.php';

use Models\Wedding;
use Helpers\ImageHelper;

// Base path: e.g. "/wedding/" for http://localhost/wedding/
$base = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/') . '/';

$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$parts = explode('/', $url);

// Admin Routing
if ($parts[0] === 'admin') {
    $action = $parts[1] ?? 'index';
    requireAuth();
    handleAdmin($action, $parts);
    exit;
}

// Auth Routing
if ($parts[0] === 'login') {
    if (isset($_SESSION['user_id'])) {
        header('Location: ' . $base . 'admin');
        exit;
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $user = \Models\User::authenticate($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: ' . $base . 'admin');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
    include 'app/Views/login.php';
    exit;
}

if ($parts[0] === 'logout') {
    session_destroy();
    header('Location: ' . $base . 'login');
    exit;
}

// API: Saweria Webhook Receiver
if ($parts[0] === 'api' && ($parts[1] ?? '') === 'saweria-webhook') {
    header('Content-Type: application/json');
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true);

    if (!$payload) {
        $payload = $_POST;
    }

    // Saweria Webhook Structure:
    // { "version": "1.0", "id": "...", "donator_name": "...", "donator_email": "...", "amount": 100000, "message": "... [WED:12] ..." }
    $saweriaId    = $payload['id'] ?? null;
    $donatorName  = trim($payload['donator_name'] ?? 'Hamba Allah');
    $donatorEmail = trim($payload['donator_email'] ?? '');
    $amount       = (float)($payload['amount'] ?? 0);
    $rawMessage   = trim($payload['message'] ?? '');

    $weddingId = 0;
    $cleanMessage = $rawMessage;

    // Filter and parse hidden Tag [WED:12] or [WID:12]
    if (preg_match('/\[(?:WED|WID):(\d+)\]/i', $rawMessage, $matches)) {
        $weddingId = (int)$matches[1];
        // Strip the hidden wedding tag from public message display
        $cleanMessage = trim(preg_replace('/\[(?:WED|WID):\d+\]/i', '', $rawMessage));
    }

    // Fallback: If tag [WED:id] is missing from message, match by unique nominal amount
    if ($weddingId === 0 && $amount > 0) {
        $weddingId = Models\Wedding::findWeddingIdByUniqueAmount($amount);
    }

    if ($weddingId > 0 && $amount > 0) {
        $wedding = Models\Wedding::getById($weddingId);
        // Only record if wedding exists and Saweria gift option is enabled
        if ($wedding && !empty($wedding['saweria_enabled'])) {
            Models\Wedding::addSaweriaGift([
                'wedding_id'    => $weddingId,
                'saweria_id'    => $saweriaId,
                'donator_name'  => $donatorName,
                'donator_email' => $donatorEmail,
                'amount'        => $amount,
                'message'       => $cleanMessage
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Gift recorded for wedding #' . $weddingId]);
            exit;
        }
    }

    echo json_encode(['status' => 'ignored', 'message' => 'No matching active wedding ID found in message or amount']);
    exit;
}

// API: Store Saweria Intent (for Unique Nominal Matching)
if ($parts[0] === 'api' && ($parts[1] ?? '') === 'saweria-intent') {
    header('Content-Type: application/json');
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true) ?? $_POST;

    $weddingId    = (int)($data['wedding_id'] ?? 0);
    $uniqueAmount = (float)($data['unique_amount'] ?? 0);
    $donatorName  = trim($data['donator_name'] ?? 'Hamba Allah');
    $message      = trim($data['message'] ?? '');

    if ($weddingId > 0 && $uniqueAmount > 0) {
        Models\Wedding::createSaweriaIntent($weddingId, $uniqueAmount, $donatorName, $message);
        echo json_encode(['status' => 'success', 'message' => 'Intent registered']);
        exit;
    }
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

// API: Get Saweria Live Gifts for UI Feed
if ($parts[0] === 'api' && ($parts[1] ?? '') === 'saweria-gifts') {
    header('Content-Type: application/json');
    $wid = (int)($_GET['wedding_id'] ?? 0);
    if ($wid) {
        $gifts = Models\Wedding::getSaweriaGifts($wid);
        echo json_encode(['status' => 'success', 'data' => $gifts]);
    } else {
        echo json_encode(['status' => 'error', 'data' => []]);
    }
    exit;
}

// Guestbook Submission
if ($parts[0] === 'api' && ($parts[1] ?? '') === 'guestbook') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $slug    = htmlspecialchars(trim($_POST['slug'] ?? ''));
        $baseUrl = $base . $slug;
        $wid     = (int)($_POST['wedding_id'] ?? 0);
        $ip      = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // 1. Honeypot — bots fill the hidden field, humans don't
        if (!empty($_POST['website'])) {
            header("Location: {$baseUrl}?msg=sent#guestbook");
            exit;
        }

        // 2. Rate limiting — 1 submission per IP per 5 minutes per wedding
        if (Wedding::isRateLimited($wid, $ip, 300)) {
            header("Location: {$baseUrl}?msg=error&reason=ratelimit#guestbook");
            exit;
        }

        // 3. Input validation
        $name    = trim(strip_tags($_POST['name'] ?? ''));
        $email   = trim($_POST['email'] ?? '');
        $circle  = trim(strip_tags($_POST['circle'] ?? ''));
        $message = trim(strip_tags($_POST['message'] ?? ''));

        if (strlen($name) < 2 || strlen($name) > 100) {
            header("Location: {$baseUrl}?msg=error&reason=name#guestbook");
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: {$baseUrl}?msg=error&reason=email#guestbook");
            exit;
        }
        if (Wedding::hasEmailSubmitted($wid, $email)) {
            header("Location: {$baseUrl}?msg=error&reason=duplicate_email#guestbook");
            exit;
        }
        if (strlen($message) < 5 || strlen($message) > 500) {
            header("Location: {$baseUrl}?msg=error&reason=message#guestbook");
            exit;
        }

        // 4. Save and record rate limit
        if ($wid) {
            $data = [
                'wedding_id' => $wid,
                'name'       => htmlspecialchars($name),
                'email'      => htmlspecialchars($email),
                'circle'     => htmlspecialchars($circle),
                'message'    => htmlspecialchars($message),
                'attendance' => (int)($_POST['attendance'] ?? 1),
                'ip_address' => $ip,
            ];
            Wedding::addMessage($data);
            Wedding::recordSubmission($wid, $ip);
        }
        header("Location: {$baseUrl}?msg=sent#guestbook");
    }
    exit;
}


// Wedding Preview Routing
if ($parts[0] === 'preview' && !empty($parts[1])) {
    $slug = $parts[1];
    $wedding = Wedding::getBySlug($slug);
    if ($wedding) {
        requireAuth();
        if ($_SESSION['role'] !== 'admin' && $wedding['user_id'] !== $_SESSION['user_id']) {
            die("Akses ditolak. Anda tidak memiliki izin untuk melihat preview ini.");
        }
        renderWedding($wedding);
        exit;
    }
}

// Wedding Slug Routing
if (!empty($url)) {
    $wedding = Wedding::getBySlug($url);
    if ($wedding) {
        if ($wedding['is_enabled']) {
            renderWedding($wedding);
        } else {
            $errorHtml = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Tidak Aktif</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Outfit', sans-serif;
            background-color: #f8fafc; color: #1e293b;
            display: flex; align-items: center; justify-content: center; height: 100vh;
        }
        .container {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            text-align: center; max-width: 450px; margin: 20px; border: 1px solid #e2e8f0;
        }
        .icon {
            font-size: 4rem; margin-bottom: 15px;
        }
        h1 {
            font-size: 1.5rem; margin: 0 0 10px; color: #1e293b; font-weight: 600;
        }
        p {
            color: #64748b; margin: 0; line-height: 1.6; font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🚧</div>
        <h1>Undangan Tidak Aktif</h1>
        <p>Mohon maaf, halaman undangan pernikahan ini sedang dalam mode draf (tidak aktif) dan belum dapat diakses oleh publik.</p>
    </div>
</body>
</html>
HTML;
            die($errorHtml);
        }
        exit;
    }
}

// Default Landing — show Palovite promotional page
include 'app/Views/landing.php';
exit;

// ═══════════════════════════════════════════════════════════════════
// AUTH MIDDLEWARE
// ═══════════════════════════════════════════════════════════════════
function requireAuth() {
    global $base;
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . $base . 'login');
        exit;
    }
}

function requireAdmin() {
    global $base;
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Akses ditolak. Halaman ini hanya untuk admin.");
    }
}

// ═══════════════════════════════════════════════════════════════════
// ADMIN HANDLER
// ═══════════════════════════════════════════════════════════════════

function handleAdmin($action, $parts) {
    switch ($action) {

        // ── DASHBOARD ──────────────────────────────────────────────────
        case 'index':
            $weddings = ($_SESSION['role'] === 'admin') 
                ? Models\Wedding::getAll() 
                : Models\Wedding::getAll($_SESSION['user_id']);
            include 'app/Views/admin_dashboard.php';
            break;

        // ── USERS (Admin Only) ─────────────────────────────────────────
        case 'users':
            requireAdmin();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $act = $_POST['action'] ?? '';
                if ($act === 'create') {
                    $u = trim($_POST['username'] ?? '');
                    $p = trim($_POST['password'] ?? '');
                    $r = $_POST['role'] ?? 'client';
                    if (\Models\User::exists($u)) {
                        header('Location: ' . $GLOBALS['base'] . 'admin/users?error=Username sudah ada');
                        exit;
                    }
                    \Models\User::create($u, $p, $r);
                    header('Location: ' . $GLOBALS['base'] . 'admin/users?success=1');
                    exit;
                }
            } elseif (isset($_GET['action']) && $_GET['action'] === 'delete') {
                $id = (int)$_GET['id'];
                if ($id !== $_SESSION['user_id']) {
                    \Models\User::delete($id);
                }
                header('Location: ' . $GLOBALS['base'] . 'admin/users?success=1');
                exit;
            }
            $users = \Models\User::getAll();
            include 'app/Views/users_list.php';
            break;

        // ── CREATE NEW WEDDING ─────────────────────────────────────────
        case 'create':
            requireAdmin();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = filterInput($_POST);
                if (Models\Wedding::slugExists($data['slug'])) {
                    $error = "Slug already exists. Choose another.";
                    include 'app/Views/wedding_form.php';
                } else {
                    unset($data['gallery_urls']);
                    $id = Models\Wedding::create($data);
                    header('Location: ' . $GLOBALS['base'] . 'admin/detail/' . $id . '/settings?saved=1');
                }
            } else {
                include 'app/Views/wedding_form.php';
            }
            break;

        // ── LEGACY REDIRECTS ───────────────────────────────────────────
        case 'edit':
            $id = (int)($parts[2] ?? 0);
            header('Location: ' . $GLOBALS['base'] . 'admin/detail/' . $id . '/settings');
            break;
        case 'guests':
            $id = (int)($parts[2] ?? 0);
            header('Location: ' . $GLOBALS['base'] . 'admin/detail/' . $id . '/guests');
            break;
        case 'love_story':
            $id = (int)($parts[2] ?? 0);
            header('Location: ' . $GLOBALS['base'] . 'admin/detail/' . $id . '/story');
            break;
        case 'gallery':
            $id = (int)($parts[2] ?? 0);
            header('Location: ' . $GLOBALS['base'] . 'admin/detail/' . $id . '/gallery');
            break;

        // ── DELETE WEDDING ─────────────────────────────────────────────
        case 'delete':
            requireAdmin();
            $id = $parts[2] ?? 0;
            if ($id) Models\Wedding::delete($id);
            header('Location: ' . $GLOBALS['base'] . 'admin');
            break;

        // ── DETAIL HUB (all management tabs) ─────────────────────────
        case 'detail':
            $id  = (int)($parts[2] ?? 0);
            $tab = $parts[3] ?? 'settings';
            if (!$id) { header('Location: ' . $GLOBALS['base'] . 'admin'); exit; }

            $db   = getDB();
            $stmt = $db->prepare("SELECT * FROM weddings WHERE id = ?");
            $stmt->execute([$id]);
            $wedding = $stmt->fetch();
            if (!$wedding) die("Wedding not found.");
            
            // RBAC check
            if ($_SESSION['role'] !== 'admin' && $wedding['user_id'] !== $_SESSION['user_id']) {
                die("Akses ditolak. Anda tidak memiliki izin untuk mengelola undangan ini.");
            }

            $detailBase = $GLOBALS['base'] . 'admin/detail/' . $id . '/';

            switch ($tab) {

                case 'settings':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $data = filterInput($_POST);
                        if ($_SESSION['role'] === 'admin' && isset($_POST['user_id'])) {
                            $data['user_id'] = empty($_POST['user_id']) ? null : (int)$_POST['user_id'];
                        } else {
                            // Prevent clients from changing the owner
                            unset($data['user_id']);
                        }
                        if (Models\Wedding::slugExists($data['slug'], $id)) {
                            header('Location: ' . $detailBase . 'settings?error=' . urlencode('Slug sudah dipakai.'));
                            exit;
                        }

                        // Handle deletion of custom OG image
                        if (!empty($_POST['delete_og_image'])) {
                            if (!empty($wedding['og_image'])) {
                                Helpers\ImageHelper::deleteFiles($wedding['og_image'], '');
                            }
                            $data['og_image'] = null;
                        }
                        unset($data['delete_og_image']);

                        // Handle upload of custom OG image
                        if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $uploadDir = __DIR__ . '/uploads/og/' . $id;
                            $res = Helpers\ImageHelper::processOgUpload($_FILES['og_image'], $uploadDir);
                            if (!isset($res['error'])) {
                                if (!empty($wedding['og_image'])) {
                                    Helpers\ImageHelper::deleteFiles($wedding['og_image'], '');
                                }
                                $data['og_image'] = $res['full'];
                            }
                        }

                        Models\Wedding::update($id, $data);
                        header('Location: ' . $detailBase . 'settings?saved=1');
                        exit;
                    }
                    $all_users = ($_SESSION['role'] === 'admin') ? Models\User::getAll() : [];
                    include 'app/Views/wedding_settings.php';
                    break;

                // ── Appearance ─────────────────────────────────────────
                case 'appearance':
                case 'appearance_countdown':
                case 'appearance_couple':
                case 'appearance_story':
                case 'appearance_event':
                case 'appearance_gallery':
                case 'appearance_gift':
                case 'appearance_guestbook':
                    $secMap = [
                        'appearance'           => 'hero',
                        'appearance_countdown' => 'countdown',
                        'appearance_couple'    => 'couple',
                        'appearance_story'     => 'love_story',
                        'appearance_event'     => 'events',
                        'appearance_gallery'   => 'gallery',
                        'appearance_gift'      => 'gifts',
                        'appearance_guestbook' => 'guestbook',
                    ];
                    $activeSec = $secMap[$tab] ?? 'hero';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $currentSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
                        $uploadDir = __DIR__ . '/uploads/sections/' . $id;
                        
                        $sec = $activeSec;
                        if (!isset($currentSettings[$sec])) {
                            $currentSettings[$sec] = ['bg_color' => '', 'bg_image' => '', 'bg_opacity' => '0.08'];
                        }
                        
                        $currentSettings[$sec]['bg_color'] = trim($_POST['bg_color'] ?? '');
                        $currentSettings[$sec]['bg_opacity'] = trim($_POST['bg_opacity'] ?? '0.08');
                        $currentSettings[$sec]['is_active'] = trim($_POST['is_active'] ?? '1');
                        $currentSettings[$sec]['text_color'] = trim($_POST['text_color'] ?? 'dark');
                        if (isset($_POST['btn_rounded'])) {
                            $currentSettings[$sec]['btn_rounded'] = trim($_POST['btn_rounded']);
                        }
                        if (isset($_POST['btn_color'])) {
                            $currentSettings[$sec]['btn_color'] = trim($_POST['btn_color']);
                        }
                        if (isset($_POST['btn_text_color'])) {
                            $currentSettings[$sec]['btn_text_color'] = trim($_POST['btn_text_color']);
                        }
                        if (isset($_POST['content_position'])) {
                            $currentSettings[$sec]['content_position'] = trim($_POST['content_position']);
                        }

                        // Specific countdown appearance settings
                        $countdownFields = [
                            'box_rounded', 'box_bg_style', 'box_num_color',
                            'show_btn_calendar', 'btn_style',
                            'countdown_title', 'countdown_subtitle', 'countdown_ended_text'
                        ];
                        foreach ($countdownFields as $cf) {
                            if (isset($_POST[$cf])) {
                                $currentSettings[$sec][$cf] = trim($_POST[$cf]);
                            }
                        }

                        // Check for image upload
                        if (isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $res = Helpers\ImageHelper::processUpload($_FILES['bg_image'], $uploadDir);
                            if (!isset($res['error'])) {
                                if (!empty($currentSettings[$sec]['bg_image'])) {
                                    Helpers\ImageHelper::deleteFiles($currentSettings[$sec]['bg_image'], '');
                                }
                                $currentSettings[$sec]['bg_image'] = $res['full'];
                            }
                        }
                        
                        // Check for image removal
                        if (isset($_POST['remove_bg']) && $_POST['remove_bg'] == '1') {
                            if (!empty($currentSettings[$sec]['bg_image'])) {
                                Helpers\ImageHelper::deleteFiles($currentSettings[$sec]['bg_image'], '');
                                $currentSettings[$sec]['bg_image'] = '';
                            }
                        }

                        Models\Wedding::update($id, ['section_settings' => json_encode($currentSettings)]);
                        header('Location: ' . $detailBase . $tab . '?saved=1');
                        exit;
                    }
                    
                    $sectionSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
                    include 'app/Views/wedding_appearance.php';
                    break;

                // ── Profile ────────────────────────────────────────────
                case 'profile':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $fields = ['bride_name','bride_nickname','bride_order','bride_father','bride_mother',
                                   'groom_name','groom_nickname','groom_order','groom_father','groom_mother'];
                        $data = [];
                        foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');
                        // Handle Photo Uploads
                        $uploadDir = __DIR__ . '/uploads/profile/' . $id;
                        if (isset($_FILES['bride_image']) && $_FILES['bride_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $res = Helpers\ImageHelper::processUpload($_FILES['bride_image'], $uploadDir);
                            if (!isset($res['error'])) {
                                if (!empty($wedding['bride_image'])) Helpers\ImageHelper::deleteFiles($wedding['bride_image'], '');
                                $data['bride_image'] = $res['full'];
                            }
                        }
                        if (isset($_FILES['groom_image']) && $_FILES['groom_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $res = Helpers\ImageHelper::processUpload($_FILES['groom_image'], $uploadDir);
                            if (!isset($res['error'])) {
                                if (!empty($wedding['groom_image'])) Helpers\ImageHelper::deleteFiles($wedding['groom_image'], '');
                                $data['groom_image'] = $res['full'];
                            }
                        }

                        Models\Wedding::update($id, $data);
                        header('Location: ' . $detailBase . 'profile?saved=1');
                        exit;
                    }
                    include 'app/Views/wedding_profile.php';
                    break;

                // ── Event ──────────────────────────────────────────────
                case 'event':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $fields = ['akad_date','akad_time','akad_place','akad_map_link',
                                   'reception_date','reception_time','reception_place','reception_map_link'];
                        $data = [];
                        foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');
                        Models\Wedding::update($id, $data);
                        header('Location: ' . $detailBase . 'event?saved=1');
                        exit;
                    }
                    include 'app/Views/wedding_event.php';
                    break;

                // ── Gift ───────────────────────────────────────────────
                case 'gift':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $fields = ['bank1_owner','bank1_name','bank1_acc','bank2_owner','bank2_name','bank2_acc'];
                        $data = [];
                        foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');
                        $data['saweria_enabled'] = isset($_POST['saweria_enabled']) ? 1 : 0;
                        Models\Wedding::update($id, $data);
                        header('Location: ' . $detailBase . 'gift?saved=1');
                        exit;
                    }
                    include 'app/Views/wedding_gift.php';
                    break;

                // ── Gallery ────────────────────────────────────────────
                case 'gallery':
                    $uploadDir = __DIR__ . '/uploads/gallery/' . $id;

                    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
                        $imgId = (int)($_GET['img_id'] ?? 0);
                        if ($imgId) {
                            $row = Models\Wedding::deleteGalleryImage($imgId);
                            if ($row) Helpers\ImageHelper::deleteFiles($row['image_url'], $row['thumb_url']);
                        }
                        header('Location: ' . $detailBase . 'gallery?deleted=1');
                        exit;
                    }

                    if (isset($_GET['action']) && $_GET['action'] === 'delete_all') {
                        $items = Models\Wedding::getGallery($id);
                        foreach ($items as $item) {
                            $row = Models\Wedding::deleteGalleryImage((int)$item['id']);
                            if ($row) Helpers\ImageHelper::deleteFiles($row['image_url'], $row['thumb_url']);
                        }
                        header('Location: ' . $detailBase . 'gallery?deleted_all=1');
                        exit;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
                        $files = $_FILES['photos'];
                        $count = count($files['name']);
                        $maxSort = $db->prepare("SELECT COALESCE(MAX(sort_order),0) FROM gallery WHERE wedding_id = ?");
                        $maxSort->execute([$id]);
                        $nextSort = (int)$maxSort->fetchColumn() + 1;
                        $uploadSuccess = 0;
                        for ($i = 0; $i < $count; $i++) {
                            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
                            $file = [
                                'name'     => $files['name'][$i],
                                'type'     => $files['type'][$i],
                                'tmp_name' => $files['tmp_name'][$i],
                                'error'    => $files['error'][$i],
                                'size'     => $files['size'][$i],
                            ];
                            $result = Helpers\ImageHelper::processUpload($file, $uploadDir);
                            if (!isset($result['error'])) {
                                Models\Wedding::addGalleryImage($id, $result['full'], $result['thumb'], $nextSort++);
                                $uploadSuccess++;
                            }
                        }
                        $qs = $uploadSuccess > 0 ? '?uploaded=' . $uploadSuccess : '?error=1';
                        header('Location: ' . $detailBase . 'gallery' . $qs);
                        exit;
                    }

                    $galleryItems = Models\Wedding::getGallery($id);
                    include 'app/Views/gallery_admin.php';
                    break;

                // ── Love Story ─────────────────────────────────────────
                case 'story':
                    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
                        $storyId = (int)($_GET['story_id'] ?? 0);
                        if ($storyId) {
                            $oldStory = Models\Wedding::getLoveStory($storyId);
                            if ($oldStory && !empty($oldStory['image_url'])) {
                                $thumbUrl = str_replace('/full/', '/thumb/', $oldStory['image_url']);
                                Helpers\ImageHelper::deleteFiles($oldStory['image_url'], $thumbUrl);
                            }
                            Models\Wedding::deleteLoveStory($storyId);
                        }
                        header('Location: ' . $detailBase . 'story?deleted=1');
                        exit;
                    }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['action']) && $_POST['action'] === 'save_texts') {
                            $data = [
                                'story_intro' => trim($_POST['story_intro'] ?? ''),
                                'story_outro' => trim($_POST['story_outro'] ?? ''),
                            ];
                            Models\Wedding::update($id, $data);
                            header('Location: ' . $detailBase . 'story?saved_texts=1');
                            exit;
                        } else {
                            $storyId = (int)($_POST['story_id'] ?? 0);
                            $data = [
                                'wedding_id' => $id,
                                'title'      => trim($_POST['title'] ?? ''),
                                'story_date' => $_POST['story_date'] ?? date('Y-m-d'),
                                'content'    => trim($_POST['content'] ?? ''),
                                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                            ];

                            // Retain existing image if editing
                            $existingStory = $storyId ? Models\Wedding::getLoveStory($storyId) : null;
                            $imageUrl = $existingStory['image_url'] ?? '';

                            // Remove image if requested
                            if (!empty($_POST['remove_image']) && $_POST['remove_image'] === '1') {
                                if (!empty($imageUrl)) {
                                    $thumbUrl = str_replace('/full/', '/thumb/', $imageUrl);
                                    Helpers\ImageHelper::deleteFiles($imageUrl, $thumbUrl);
                                    $imageUrl = '';
                                }
                            }

                            // Handle photo upload
                            $uploadDir = __DIR__ . '/uploads/story/' . $id;
                            if (isset($_FILES['story_image']) && $_FILES['story_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                                $res = Helpers\ImageHelper::processUpload($_FILES['story_image'], $uploadDir);
                                if (!isset($res['error'])) {
                                    if (!empty($imageUrl)) {
                                        $thumbUrl = str_replace('/full/', '/thumb/', $imageUrl);
                                        Helpers\ImageHelper::deleteFiles($imageUrl, $thumbUrl);
                                    }
                                    $imageUrl = $res['full'];
                                }
                            }

                            $data['image_url'] = $imageUrl;
                            $storyId ? Models\Wedding::updateLoveStory($storyId, $data) : Models\Wedding::addLoveStory($data);
                            header('Location: ' . $detailBase . 'story?saved_story=1');
                            exit;
                        }
                    }
                    $stories = Models\Wedding::getLoveStories($id);
                    include 'app/Views/love_story.php';
                    break;

                // ── Guests ─────────────────────────────────────────────
                case 'guests':
                    $actionType = $_GET['action'] ?? ($_POST['action'] ?? '');

                    if ($actionType === 'add_cat' && !empty($_POST['cat_name'])) {
                        Models\Wedding::addCategory($id, $_POST['cat_name']);
                        header('Location: ' . $detailBase . 'guests?saved=1'); exit;
                    }
                    if ($actionType === 'delete_cat' && !empty($_GET['cat_id'])) {
                        Models\Wedding::deleteCategory($_GET['cat_id']);
                        header('Location: ' . $detailBase . 'guests?deleted=1'); exit;
                    }
                    if ($actionType === 'add_guest' && !empty($_POST['guest_name']) && !empty($_POST['category_id'])) {
                        Models\Wedding::addGuest($id, $_POST['category_id'], $_POST['guest_name']);
                        header('Location: ' . $detailBase . 'guests?saved=1'); exit;
                    }
                    if ($actionType === 'edit_guest' && !empty($_POST['guest_id']) && !empty($_POST['guest_name'])) {
                        Models\Wedding::updateGuest($_POST['guest_id'], $_POST['category_id'], $_POST['guest_name']);
                        header('Location: ' . $detailBase . 'guests?saved=1'); exit;
                    }
                    if ($actionType === 'delete_guest' && !empty($_GET['guest_id'])) {
                        Models\Wedding::deleteGuest($_GET['guest_id']);
                        header('Location: ' . $detailBase . 'guests?deleted=1'); exit;
                    }

                    $stats = Models\Wedding::getGuestStats($id);
                    $allGuests = Models\Wedding::getAllGuests($id);
                    $categories = Models\Wedding::getGuestCategories($id);
                    include 'app/Views/guest_list.php';
                    break;

                // ── Guestbook ──────────────────────────────────────────
                case 'guestbook':
                    if (isset($_GET['action']) && $_GET['action'] === 'deletemsg') {
                        $msgId = (int)($_GET['msg_id'] ?? 0);
                        if ($msgId) Models\Wedding::deleteMessage($msgId);
                        header('Location: ' . $detailBase . 'guestbook?deleted=1');
                        exit;
                    }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'replymsg') {
                        $msgId = (int)($_POST['msg_id'] ?? 0);
                        $reply = trim($_POST['reply'] ?? '');
                        if ($msgId && $reply) {
                            Models\Wedding::replyMessage($msgId, htmlspecialchars($reply));
                            header('Location: ' . $detailBase . 'guestbook?replied=1');
                            exit;
                        }
                    }
                    include 'app/Views/wedding_guestbook.php';
                    break;

                default:
                    header('Location: ' . $detailBase . 'settings');
            }
            break;

        default:
            header('Location: ' . $GLOBALS['base'] . 'admin');
    }
}

// ═══════════════════════════════════════════════════════════════════
// RENDER THEME
// ═══════════════════════════════════════════════════════════════════

function renderWedding($wedding) {
    global $base;
    $theme = $wedding['theme_path'];
    $themeDir = __DIR__ . "/themes/$theme";
    if (file_exists("$themeDir/index.php")) {
        include "$themeDir/index.php";
    } else {
        die("Theme not found: $theme");
    }
}

// ═══════════════════════════════════════════════════════════════════
// INPUT FILTERING
// ═══════════════════════════════════════════════════════════════════

function filterInput($post) {
    $data = [];
    foreach ($post as $key => $val) {
        $data[$key] = is_array($val) ? $val : trim($val);
    }
    $data['is_enabled'] = isset($post['is_enabled']) ? 1 : 0;
    return $data;
}
