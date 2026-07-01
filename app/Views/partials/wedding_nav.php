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
    'settings'             => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>', 'label' => 'Pengaturan'],
    
    // Layout Category
    'appearance'           => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>', 'label' => 'Hero / Sampul'],
    'appearance_countdown' => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>', 'label' => 'Hitung Mundur'],
    'appearance_couple'    => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>', 'label' => 'Pasangan'],
    'appearance_story'     => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>', 'label' => 'Love Story'],
    'appearance_event'     => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>', 'label' => 'Acara'],
    'appearance_gallery'   => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>', 'label' => 'Galeri'],
    'appearance_gift'      => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>', 'label' => 'Hadiah'],
    'appearance_guestbook' => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>', 'label' => 'Buku Tamu'],

    'profile'              => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>', 'label' => 'Pengantin'],
    'event'                => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>', 'label' => 'Acara'],
    'gift'                 => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>', 'label' => 'Hadiah'],
    'gallery'              => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>', 'label' => 'Galeri', 'count' => $galCount],
    'story'                => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>', 'label' => 'Cerita', 'count' => $storyCount],
    'guests'               => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>', 'label' => 'Daftar Undangan'],
    'guestbook'            => ['icon' => '<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path></svg>', 'label' => 'Buku Tamu', 'count' => $gbCount],
];

// Mapping of sub-tabs to main-tabs / categories
$categoryMapping = [
    'settings'             => 'settings',
    'appearance'           => 'layout',
    'appearance_countdown' => 'layout',
    'appearance_couple'    => 'layout',
    'appearance_story'     => 'layout',
    'appearance_event'     => 'layout',
    'appearance_gallery'   => 'layout',
    'appearance_gift'      => 'layout',
    'appearance_guestbook' => 'layout',
    'profile'              => 'content',
    'event'                => 'content',
    'gift'                 => 'content',
    'gallery'              => 'content',
    'story'                => 'content',
    'guests'               => 'interaction',
    'guestbook'            => 'interaction',
];

$activeCategory = $categoryMapping[$activeTab] ?? 'settings';

// Filter $tabs to only show the ones belonging to the active category
$visibleTabs = [];
foreach ($tabs as $key => $tab) {
    if (($categoryMapping[$key] ?? '') === $activeCategory) {
        $visibleTabs[$key] = $tab;
    }
}

// ── Live Preview (appearance tabs) ──
$_sectionAnchors = [
    'appearance'           => 'hero',
    'appearance_countdown' => 'countdown-section',
    'appearance_couple'    => 'couple',
    'appearance_story'     => 'love-story',
    'appearance_event'     => 'events',
    'appearance_gallery'   => 'gallery',
    'appearance_gift'      => 'gifts',
    'appearance_guestbook' => 'guestbook',
];
$isAppearancePage = isset($_sectionAnchors[$activeTab]);
$_previewAnchor   = $_sectionAnchors[$activeTab] ?? '';
$_previewUrl      = $base . 'preview/' . $slug . '?section_preview=' . urlencode($_previewAnchor);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tabs[$activeTab]['label']) ?> — <?= htmlspecialchars($wedding['title']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* CSS resets or variables utilized by existing pages if they rely on custom components */
        :root {
            --primary:   #2563eb;
            --secondary: #475569;
            --grad:      linear-gradient(135deg, #1e293b, #0f172a);
            --bg:        #f8fafc;
            --card:      #ffffff;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --radius:    8px;
            --shadow:    0 1px 3px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        *, *::before, *::after { box-sizing: border-box; }
        
        /* Retain global class selectors for custom elements in detail forms (e.g. section-card, form-control, btn) */
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
            background: #eff6ff; border-radius: 6px;
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

        .form-grid { display: grid; gap: 16px; }
        .form-grid.g2 { grid-template-columns: 1fr 1fr; }
        .form-grid.g3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-grid > *, .form-grid.g2 > *, .form-grid.g3 > * { min-width: 0; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-label {
            font-size: 0.82rem; font-weight: 600; color: #475569;
        }
        .form-label small { font-weight: 400; color: #94a3b8; }
        .form-control {
            width: 100%;
            max-width: 100%;
            padding: 10px 13px;
            border: 1.5px solid var(--border); border-radius: 6px;
            font-family: inherit; font-size: 0.9rem; color: var(--text);
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            background: white;
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-check { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.9rem; }
        .form-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 6px;
            font-family: inherit; font-weight: 600; font-size: 0.88rem;
            cursor: pointer; border: none; text-decoration: none; transition: all 0.2s;
        }
        .btn-primary {
            background: var(--primary); color: white;
            box-shadow: 0 2px 4px rgba(37,99,235,0.15);
        }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-ghost { background: #f1f5f9; color: var(--muted); }
        .btn-ghost:hover { background: var(--border); }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        .btn-sm { padding: 7px 14px; font-size: 0.8rem; }

        .alert {
            padding: 12px 18px; border-radius: 6px;
            font-size: 0.88rem; font-weight: 600; margin-bottom: 20px;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error   { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; }

        .section-divider {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #94a3b8;
            display: flex; align-items: center; gap: 10px;
            margin: 8px 0 14px;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @media (max-width: 640px) {
            .form-grid.g2, .form-grid.g3 { grid-template-columns: 1fr; }
        }
    </style>
</head>

<?php if ($isAppearancePage): ?>
<!-- ════════════ SPLIT-SCREEN LAYOUT (Appearance) ════════════ -->
<body style="margin:0;padding:0;overflow:hidden;background:#0f172a;font-family:'Outfit',sans-serif;">
<style>
html,body{height:100%;margin:0;padding:0;}
body{display:flex;flex-direction:column;overflow:hidden;background:#0f172a;}
/* JS sets --vh for accurate mobile height */
:root{--vh:1vh;}
#app-layout{display:flex;flex-direction:column;height:calc(var(--vh,1vh)*100);overflow:hidden;}
/* ─ Topbar ─ */
#app-topbar{flex-shrink:0;background:#0f172a;color:white;padding:0 14px;height:50px;display:flex;align-items:center;justify-content:space-between;gap:10px;border-bottom:1px solid rgba(255,255,255,0.07);z-index:50;}
.atb-btn{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.85);padding:5px 11px;border-radius:7px;font-size:0.72rem;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;transition:background 0.2s;white-space:nowrap;}
.atb-btn:hover{background:rgba(255,255,255,0.16);color:white;}
/* ─ Preview pane ─ */
#preview-pane{flex:1;overflow:hidden;background:#0f172a;display:flex;justify-content:center;align-items:stretch;min-height:0;padding:0;}
#phone-wrapper{width:100%;max-width:430px;height:100%;overflow:hidden;border-radius:20px 20px 0 0;box-shadow:0 0 60px rgba(0,0,0,0.55);position:relative;}
#previewFrame{width:100%;height:100%;border:none;display:block;}
/* ─ Settings panel ─ */
#settings-panel{
    flex-shrink:0;
    background:white;
    border-radius:20px 20px 0 0;
    box-shadow:0 -10px 40px rgba(0,0,0,0.3);
    display:flex;
    flex-direction:column;
    position:relative;
    z-index:30;
    overflow:hidden;
    /* Safe area for notch phones */
    padding-bottom:env(safe-area-inset-bottom,0px);
    /* Ensure panel never goes off-screen on mobile */
    max-height:calc(var(--vh,1vh)*100 - 50px);
}
#panel-handle{
    padding:12px 16px 10px;
    min-height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    flex-shrink:0;
    user-select:none;
    -webkit-user-select:none;
    touch-action:manipulation;
    background:white;
}
.handle-bar{
    width:40px;height:4px;
    background:#d1d5db;
    border-radius:4px;
    pointer-events:none;
    transition:background 0.2s,width 0.2s;
}
/* When minimized: wider bar as subtle hint */
.panel-minimized .handle-bar{background:#94a3b8;width:52px;}
#panel-tabs{border-bottom:1px solid #e2e8f0;overflow-x:auto;flex-shrink:0;display:flex;padding:0 6px;-ms-overflow-style:none;scrollbar-width:none;}
#panel-tabs::-webkit-scrollbar{display:none;}
#panel-tabs a{display:inline-flex;align-items:center;gap:5px;padding:10px 13px;font-size:0.79rem;font-weight:600;border-bottom:2.5px solid transparent;color:#64748b;text-decoration:none;white-space:nowrap;transition:all 0.2s;flex-shrink:0;}
#panel-tabs a.ptab-active{border-bottom-color:#2563eb;color:#2563eb;}
#panel-tabs a:not(.ptab-active):hover{color:#2563eb;background:#f8fafc;border-radius:6px 6px 0 0;}
/* ─ Panel body ─ */
#panel-body{overflow-y:auto;overflow-x:hidden;flex:1;transition:max-height 0.38s cubic-bezier(0.4,0,0.2,1),opacity 0.25s ease;opacity:1;}
/* Minimized: collapse body, keep handle+tabs visible */
.panel-minimized #panel-body{max-height:0!important;overflow:hidden;opacity:0;pointer-events:none;}
/* Strip card chrome inside panel */
#panel-body .section-card{border-radius:0;border:none;box-shadow:none;margin:0;}
#panel-body .alert{margin:12px 16px 0;}
#panel-body .section-card-footer{position:sticky;bottom:0;z-index:5;background:white;box-shadow:0 -4px 16px rgba(0,0,0,0.07);}
</style>

<script>
/* Fix mobile viewport height — browsers vary in how they handle 100vh */
(function(){
    function setVH(){
        var vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', vh + 'px');
    }
    setVH();
    window.addEventListener('resize', setVH);
    window.addEventListener('orientationchange', function(){ setTimeout(setVH,150); });
})();
</script>

<div id="app-layout">

  <!-- TOP BAR -->
  <div id="app-topbar">
    <div style="display:flex;align-items:center;gap:9px;min-width:0;overflow:hidden;">
      <a href="<?= $base ?>admin" class="atb-btn" style="padding:5px 8px;flex-shrink:0;" title="Dashboard">
        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      </a>
      <div style="min-width:0;overflow:hidden;">
        <div style="font-weight:700;font-size:0.85rem;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($wedding['title']) ?></div>
        <div style="font-size:0.67rem;color:#94a3b8;white-space:nowrap;">Live Preview &middot; <?= htmlspecialchars($tabs[$activeTab]['label'] ?? '') ?></div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:7px;flex-shrink:0;">
      <button onclick="reloadPreview()" class="atb-btn" type="button">
        <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
        Refresh
      </button>
      <a href="<?= $base ?>preview/<?= $slug ?>" target="_blank" class="atb-btn">
        <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        Buka
      </a>
    </div>
  </div>

  <!-- PREVIEW PANE -->
  <div id="preview-pane">
    <div id="phone-wrapper">
      <iframe id="previewFrame"
        src="<?= htmlspecialchars($_previewUrl) ?>"
        frameborder="0"
        title="Preview Undangan Digital">
      </iframe>
    </div>
  </div>

  <!-- SETTINGS PANEL -->
  <div id="settings-panel" class="panel-minimized">
    <div id="panel-handle" onclick="toggleMinimize()" title="Buka / Tutup panel">
      <div class="handle-bar"></div>
    </div>
    <nav id="panel-tabs">
      <?php foreach ($visibleTabs as $tabKey => $tab): ?>
      <a href="<?= $base ?>admin/detail/<?= $wid ?>/<?= $tabKey ?>"
         class="<?= $activeTab === $tabKey ? 'ptab-active' : '' ?>">
        <?= $tab['icon'] ?>
        <span><?= $tab['label'] ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
    <div id="panel-body">
    <!-- view content injected below -->

<?php else: ?>
<!-- ════════════ NORMAL LAYOUT ════════════ -->
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

<!-- ── TOPBAR ── -->
<header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h1 class="flex items-center gap-2 text-base font-bold tracking-tight">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"></path></svg>
                <span><?= htmlspecialchars($wedding['title']) ?></span>
            </h1>
            <span class="inline-flex text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                <?= $wedding['is_enabled'] ? 'Aktif' : 'Nonaktif' ?>
            </span>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= $base ?>preview/<?= $slug ?>" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800 border border-slate-700">
                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                Preview
            </a>
            <a href="<?= $base ?>admin" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800">
                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Dashboard
            </a>
        </div>
    </div>
</header>

<!-- ── TAB NAVIGATION ── -->
<div class="bg-white border-b border-slate-200 sticky top-16 z-40 shadow-sm">
    <nav class="max-w-6xl mx-auto px-4 flex items-stretch overflow-x-auto no-scrollbar">
        <?php foreach ($visibleTabs as $tabKey => $tab): ?>
        <a href="<?= $base ?>admin/detail/<?= $wid ?>/<?= $tabKey ?>"
           class="inline-flex items-center gap-2 px-5 py-4 text-sm font-semibold border-b-2 transition-all shrink-0 <?= $activeTab === $tabKey ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600 hover:border-blue-200' ?>">
            <?= $tab['icon'] ?>
            <span><?= $tab['label'] ?></span>
            <?php if (!empty($tab['count'])): ?>
            <span class="inline-flex items-center justify-center text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-50 text-blue-600 ml-1 border border-blue-100"><?= $tab['count'] ?></span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </nav>
</div>

<!-- ── CONTENT START ── -->
<div class="max-w-4xl mx-auto px-4 py-8">
<?php endif; ?>
