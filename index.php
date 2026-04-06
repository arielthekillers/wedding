<?php
require_once __DIR__ . '/config/config.php';

use Models\Wedding;

// Base path: e.g. "/wedding/" for http://localhost/wedding/
$base = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/') . '/';

$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$parts = explode('/', $url);

// Admin Routing
if ($parts[0] === 'admin') {
    $action = $parts[1] ?? 'index';
    handleAdmin($action, $parts);
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
        $message = trim(strip_tags($_POST['message'] ?? ''));

        if (strlen($name) < 2 || strlen($name) > 100) {
            header("Location: {$baseUrl}?msg=error&reason=name#guestbook");
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: {$baseUrl}?msg=error&reason=email#guestbook");
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


// Wedding Slug Routing
if (!empty($url)) {
    $wedding = Wedding::getBySlug($url);
    if ($wedding) {
        if ($wedding['is_enabled'] || (isset($_GET['preview']) && $_GET['preview'] === '1')) {
            renderWedding($wedding);
        } else {
            die("Sorry, this wedding invitation is currently disabled.");
        }
        exit;
    }
}

// Default Landing — redirect to admin dashboard
header('Location: ' . $base . 'admin');
exit;

// Handle Admin Actions
function handleAdmin($action, $parts) {
    switch ($action) {
        case 'index':
            $weddings = Wedding::getAll();
            include 'app/Views/admin_dashboard.php';
            break;
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = filterInput($_POST);
                if (Wedding::slugExists($data['slug'])) {
                    $error = "Slug already exists. Choose another.";
                    include 'app/Views/wedding_form.php';
                } else {
                    $urls = explode("\n", $data['gallery_urls'] ?? '');
                    unset($data['gallery_urls']);
                    $id = Wedding::create($data);
                    if ($id) Wedding::setGallery($id, $urls);
                    header('Location: ' . $GLOBALS['base'] . 'admin');
                }
            } else {
                include 'app/Views/wedding_form.php';
            }
            break;
        case 'edit':
            $id = $parts[2] ?? 0;
            $wedding = null;
            if ($id) {
                // Fetch wedding by ID
                $db = getDB();
                $stmt = $db->prepare("SELECT * FROM weddings WHERE id = ?");
                $stmt->execute([$id]);
                $wedding = $stmt->fetch();
            }
            if (!$wedding) die("Wedding not found.");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = filterInput($_POST);
                if (Wedding::slugExists($data['slug'], $id)) {
                    $error = "Slug already exists.";
                    include 'app/Views/wedding_form.php';
                } else {
                    $urls = explode("\n", $data['gallery_urls'] ?? '');
                    unset($data['gallery_urls']);
                    Wedding::update($id, $data);
                    Wedding::setGallery($id, $urls);
                    header('Location: ' . $GLOBALS['base'] . 'admin');
                }
            } else {
                include 'app/Views/wedding_form.php';
            }
            break;
        case 'delete':
            $id = $parts[2] ?? 0;
            if ($id) Wedding::delete($id);
            header('Location: ' . $GLOBALS['base'] . 'admin');
            break;
        case 'guests':
            $id = $parts[2] ?? 0;
            if (!$id) { header('Location: ' . $GLOBALS['base'] . 'admin'); exit; }
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM weddings WHERE id = ?");
            $stmt->execute([$id]);
            $wedding = $stmt->fetch();
            if (!$wedding) die("Wedding not found.");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $catNames  = $_POST['cat_name']  ?? [];
                $catGuests = $_POST['cat_guests'] ?? [];
                $toSave = [];
                foreach ($catNames as $i => $catName) {
                    $names = explode(',', $catGuests[$i] ?? '');
                    $toSave[$catName] = $names;
                }
                Wedding::saveGuestList($id, $toSave);
                header('Location: ' . $GLOBALS['base'] . 'admin/guests/' . $id . '?saved=1');
                exit;
            }
            $categories = Wedding::getGuestCategories($id);
            include 'app/Views/guest_list.php';
            break;
        case 'guestbook':
            // Handle guestbook delete: /admin/guestbook/delete/{id}
            if (($parts[2] ?? '') === 'delete') {
                $msgId = (int)($parts[3] ?? 0);
                if ($msgId) Wedding::deleteMessage($msgId);
            }
            $ref = $_SERVER['HTTP_REFERER'] ?? $GLOBALS['base'] . 'admin';
            header("Location: $ref");
            break;
        default:
            die("Admin action not found.");

    }
}

// Render Wedding with selected theme
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

// Basic input filtering
function filterInput($post) {
    $data = [];
    foreach ($post as $key => $val) {
        $data[$key] = htmlspecialchars(trim($val));
    }
    // Handle checkbox
    $data['is_enabled'] = isset($post['is_enabled']) ? 1 : 0;
    return $data;
}
