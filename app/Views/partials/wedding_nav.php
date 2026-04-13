<?php
/**
 * Shared Wedding Management Navigation
 * Include at the TOP of every wedding management view.
 */

// Bring $base into scope (included from within handleAdmin function)
global $base;
if (empty($base)) $base = $GLOBALS['base'] ?? '/';

$wid  = $wedding['id'];
$slug = $wedding['slug'];

// Compute counts if not passed
if (!isset($galCount))   $galCount   = count(Models\Wedding::getGallery($wid));
if (!isset($storyCount)) $storyCount = count(Models\Wedding::getLoveStories($wid));
if (!isset($gbCount))    $gbCount    = count(Models\Wedding::getMessages($wid));

$tabs = [
    'settings'  => ['icon' => '⚙️',  'label' => 'Pengaturan'],
    'profile'   => ['icon' => '👰',  'label' => 'Pengantin'],
    'event'     => ['icon' => '📅',  'label' => 'Acara'],
    'gift'      => ['icon' => '🎁',  'label' => 'Hadiah'],
    'gallery'   => ['icon' => '🖼️', 'label' => 'Galeri', 'count' => $galCount],
    'story'     => ['icon' => '💕',  'label' => 'Cerita', 'count' => $storyCount],
    'guests'    => ['icon' => '📋',  'label' => 'Daftar Undangan'],
    'guestbook' => ['icon' => '📝',  'label' => 'Buku Tamu', 'count' => $gbCount],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tabs[$activeTab]['label']) ?> — <?= htmlspecialchars($wedding['title']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════════════════════
           GLOBAL DESIGN TOKENS  (shared across all management pages)
        ═══════════════════════════════════════════════════════════════ */
        :root {
            --primary:   #8b5cf6;
            --secondary: #ec4899;
            --grad:      linear-gradient(135deg, #8b5cf6, #ec4899);
            --bg:        #f4f6fb;
            --card:      #ffffff;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --radius:    14px;
            --shadow:    0 4px 16px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 32px rgba(0,0,0,0.12);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg); color: var(--text);
            margin: 0; padding: 0;
        }

        /* ─── TOPBAR ─── */
        .mgmt-topbar {
            background: var(--grad);
            color: white;
            padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            height: 62px;
            box-shadow: 0 4px 20px rgba(139,92,246,0.3);
            position: sticky; top: 0; z-index: 200;
        }
        .mgmt-topbar .wedding-title {
            display: flex; align-items: center; gap: 12px;
        }
        .mgmt-topbar h1 {
            margin: 0; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.3px;
        }
        .status-pill {
            font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .status-on  { background: rgba(255,255,255,0.25); color: white; }
        .status-off { background: rgba(0,0,0,0.2); color: rgba(255,255,255,0.7); }
        .mgmt-topbar .actions { display: flex; align-items: center; gap: 8px; }
        .topbar-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 14px; border-radius: 8px;
            font-size: 0.82rem; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            transition: all 0.2s;
        }
        .tb-preview { background: rgba(255,255,255,0.2); color: white; }
        .tb-preview:hover { background: rgba(255,255,255,0.35); color: white; }
        .tb-back    { background: rgba(0,0,0,0.18); color: white; }
        .tb-back:hover { background: rgba(0,0,0,0.3); color: white; }

        /* ─── TAB NAV ─── */
        .mgmt-tabs {
            background: var(--card);
            border-bottom: 2px solid var(--border);
            display: flex; align-items: stretch;
            overflow-x: auto;
            position: sticky; top: 62px; z-index: 199;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            scrollbar-width: none;
        }
        .mgmt-tabs::-webkit-scrollbar { display: none; }
        .mgmt-tab {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0 20px; height: 48px;
            font-size: 0.84rem; font-weight: 600;
            text-decoration: none; color: var(--muted);
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            transition: color 0.2s, border-color 0.2s;
            flex-shrink: 0;
        }
        .mgmt-tab:hover { color: var(--primary); }
        .mgmt-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        .tab-badge {
            background: #ede9fe; color: var(--primary);
            font-size: 0.68rem; font-weight: 700;
            padding: 1px 6px; border-radius: 20px; min-width: 18px; text-align: center;
        }
        .mgmt-tab.active .tab-badge { background: var(--primary); color: white; }

        /* ─── CONTENT WRAPPER ─── */
        .mgmt-content { max-width: 960px; margin: 32px auto; padding: 0 20px 60px; }

        /* ─── CARDS ─── */
        .section-card {
            background: var(--card); border-radius: var(--radius);
            box-shadow: var(--shadow); border: 1px solid var(--border);
            margin-bottom: 20px; overflow: hidden;
        }
        .section-card-header {
            padding: 18px 24px 14px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .section-card-header .icon {
            font-size: 1.2rem;
            width: 36px; height: 36px;
            background: #ede9fe; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .section-card-header h2 { margin: 0; font-size: 1rem; font-weight: 700; }
        .section-card-header p  { margin: 2px 0 0; font-size: 0.8rem; color: var(--muted); }
        .section-card-body { padding: 24px; }
        .section-card-footer {
            padding: 16px 24px;
            background: #fafbff;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }

        /* ─── FORM ELEMENTS ─── */
        .form-grid { display: grid; gap: 16px; }
        .form-grid.g2 { grid-template-columns: 1fr 1fr; }
        .form-grid.g3 { grid-template-columns: 1fr 1fr 1fr; }
        /* CRITICAL: prevent grid children from overflowing their column */
        .form-grid > *,
        .form-grid.g2 > *,
        .form-grid.g3 > * { min-width: 0; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-label {
            font-size: 0.82rem; font-weight: 600; color: #475569;
        }
        .form-label small { font-weight: 400; color: #94a3b8; }
        .form-control {
            width: 100%;
            max-width: 100%;
            padding: 10px 13px;
            border: 1.5px solid var(--border); border-radius: 9px;
            font-family: inherit; font-size: 0.9rem; color: var(--text);
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139,92,246,0.12);
            background: white;
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-check { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.9rem; }
        .form-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer; }

        /* ─── BUTTONS ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 9px;
            font-family: inherit; font-weight: 600; font-size: 0.88rem;
            cursor: pointer; border: none; text-decoration: none; transition: all 0.2s;
        }
        .btn-primary {
            background: var(--grad); color: white;
            box-shadow: 0 4px 12px rgba(139,92,246,0.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(139,92,246,0.4); }
        .btn-ghost { background: #f1f5f9; color: var(--muted); }
        .btn-ghost:hover { background: var(--border); }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        .btn-sm { padding: 7px 14px; font-size: 0.8rem; }

        /* ─── ALERTS ─── */
        .alert {
            padding: 12px 18px; border-radius: 10px;
            font-size: 0.88rem; font-weight: 600; margin-bottom: 20px;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error   { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; }

        /* ─── DIVIDER ─── */
        .section-divider {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #94a3b8;
            display: flex; align-items: center; gap: 10px;
            margin: 8px 0 14px;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .form-grid.g2, .form-grid.g3 { grid-template-columns: 1fr; }
            .mgmt-topbar h1 { font-size: 0.95rem; }
            .mgmt-content { padding: 0 14px 60px; }
        }
    </style>
</head>
<body>

<!-- ── TOPBAR ── -->
<div class="mgmt-topbar">
    <div class="wedding-title">
        <h1>🎊 <?= htmlspecialchars($wedding['title']) ?></h1>
        <span class="status-pill <?= $wedding['is_enabled'] ? 'status-on' : 'status-off' ?>">
            <?= $wedding['is_enabled'] ? 'Aktif' : 'Nonaktif' ?>
        </span>
    </div>
    <div class="actions">
        <a href="<?= $base . $slug ?>" target="_blank" class="topbar-btn tb-preview">🌐 Preview</a>
        <a href="<?= $base ?>admin" class="topbar-btn tb-back">← Dashboard</a>
    </div>
</div>

<!-- ── TAB NAVIGATION ── -->
<nav class="mgmt-tabs">
    <?php foreach ($tabs as $tabKey => $tab): ?>
    <a href="<?= $base ?>admin/detail/<?= $wid ?>/<?= $tabKey ?>"
       class="mgmt-tab <?= $activeTab === $tabKey ? 'active' : '' ?>">
        <?= $tab['icon'] ?> <?= $tab['label'] ?>
        <?php if (!empty($tab['count'])): ?>
        <span class="tab-badge"><?= $tab['count'] ?></span>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
</nav>

<!-- ── CONTENT START ── -->
<div class="mgmt-content">
