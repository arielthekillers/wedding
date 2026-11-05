<?php
$activeTab = $tab;
include __DIR__ . '/partials/wedding_nav.php';

$sectionsMeta = [
    'hero'       => ['title' => 'Hero / Sampul',        'default_opacity' => '0.70'],
    'countdown'  => ['title' => 'Hitung Mundur',        'default_opacity' => '0.08'],
    'couple'     => ['title' => 'Pasangan',             'default_opacity' => '0.08'],
    'love_story' => ['title' => 'Love Story',           'default_opacity' => '0.08'],
    'events'     => ['title' => 'Acara',                'default_opacity' => '0.08'],
    'gallery'    => ['title' => 'Galeri',               'default_opacity' => '0.08'],
    'gifts'      => ['title' => 'Hadiah / Amplop',      'default_opacity' => '0.08'],
    'guestbook'  => ['title' => 'Buku Tamu',            'default_opacity' => '0.08'],
];

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

$activeSec  = $secMap[$activeTab] ?? 'hero';
$meta       = $sectionsMeta[$activeSec];
$defaultOp  = $meta['default_opacity'];

$sectionSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
$cfg = $sectionSettings[$activeSec] ?? [];
if (!isset($cfg['bg_opacity']) || $cfg['bg_opacity'] === '') $cfg['bg_opacity'] = $defaultOp;
$isActive  = ($cfg['is_active']  ?? '1') !== '0';
$textColor = $cfg['text_color'] ?? 'dark';
$bgColor   = $cfg['bg_color']   ?? '';
$bgOpacity = $cfg['bg_opacity'];

$heroCfg      = $sectionSettings['hero'] ?? [];
$btnRounded   = isset($cfg['btn_rounded']) && $cfg['btn_rounded'] !== '' 
                ? ($cfg['btn_rounded'] !== '0') 
                : (($heroCfg['btn_rounded'] ?? '1') !== '0');
$btnColor     = $cfg['btn_color'] ?? '';
$btnTextColor = !empty($cfg['btn_text_color']) ? $cfg['btn_text_color'] : (!empty($heroCfg['btn_text_color']) ? $heroCfg['btn_text_color'] : 'light');
$hasButtonSec = in_array($activeSec, ['hero', 'countdown', 'events', 'gifts', 'guestbook']);

// Specific countdown settings
$cdBoxRounded      = $cfg['box_rounded'] ?? '1';
$cdBoxBgStyle      = $cfg['box_bg_style'] ?? 'glass';
$cdBoxNumColor     = $cfg['box_num_color'] ?? '';
$cdShowBtnCalendar = $cfg['show_btn_calendar'] ?? '1';
$cdBtnStyle        = $cfg['btn_style'] ?? 'outline';
$cdTitleValue      = $cfg['countdown_title'] ?? '';
$cdSubtitleValue   = $cfg['countdown_subtitle'] ?? '';
$cdEndedTextValue  = $cfg['countdown_ended_text'] ?? '';
$contentPosition   = $cfg['content_position'] ?? 'center';
?>
<style>
/* ── Compact form styles ── */
.app-form { padding: 14px 16px 80px; display: flex; flex-direction: column; gap: 10px; }

/* Row: label + control side by side */
.cf-row {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; padding: 10px 14px; background: #f8fafc;
    border-radius: 10px; border: 1px solid #e8edf3;
    min-height: 44px;
}
.cf-label { font-size: 0.78rem; font-weight: 600; color: #334155; white-space: nowrap; }
.cf-sub   { font-size: 0.68rem; color: #94a3b8; font-weight: 400; display: block; margin-top: 1px; }

/* Toggle switch */
.sw { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.sw input { opacity: 0; width: 0; height: 0; position: absolute; }
.sw-track {
    position: absolute; inset: 0;
    background: #cbd5e1; border-radius: 24px; cursor: pointer;
    transition: background 0.2s;
}
.sw input:checked ~ .sw-track { background: #2563eb; }
.sw-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 18px; height: 18px; background: white;
    border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.2s; pointer-events: none;
}
.sw input:checked ~ .sw-track ~ .sw-thumb { transform: translateX(20px); }
.sw-label { font-size: 0.72rem; font-weight: 700; color: #64748b; white-space: nowrap; }
.sw-label.on { color: #2563eb; }

/* Text color pill selector */
.tc-group { display: flex; background: #e2e8f0; border-radius: 7px; padding: 2px; gap: 2px; }
.tc-group label { cursor: pointer; }
.tc-group input { position: absolute; opacity: 0; width: 0; height: 0; }
.tc-pill {
    display: block; padding: 4px 12px; border-radius: 5px;
    font-size: 0.72rem; font-weight: 700; color: #64748b; transition: all 0.15s;
    white-space: nowrap; user-select: none;
}
.tc-group input:checked + .tc-pill {
    background: white; color: #2563eb;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

/* Color picker modern */
.color-row { display: flex; align-items: center; gap: 8px; }
.cp-swatch-btn {
    width: 34px; height: 34px; border-radius: 8px; border: 2px solid #e2e8f0;
    cursor: pointer; flex-shrink: 0; transition: border-color 0.2s, transform 0.15s;
    background: none; padding: 0; position: relative; overflow: hidden;
}
.cp-swatch-btn:hover { border-color: #94a3b8; transform: scale(1.08); }
.cp-swatch-btn.active { border-color: #2563eb; }
.cp-swatch-inner { position: absolute; inset: 0; border-radius: 6px; }
/* checkerboard for transparent */
.cp-swatch-btn[data-empty] .cp-swatch-inner {
    background-image: linear-gradient(45deg,#ccc 25%,transparent 25%),linear-gradient(-45deg,#ccc 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#ccc 75%),linear-gradient(-45deg,transparent 75%,#ccc 75%);
    background-size: 8px 8px; background-position: 0 0,0 4px,4px -4px,-4px 0;
}
.color-text {
    flex: 1; padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 0.78rem; color: #334155; background: white; font-family: monospace;
    min-width: 0;
}
.color-text:focus { outline: none; border-color: #2563eb; }
/* Picker palette panel */
.cp-panel {
    display: none; flex-direction: column; gap: 10px;
    background: white; border: 1px solid #e8edf3; border-radius: 12px;
    padding: 12px; margin-top: 2px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    animation: cp-open 0.18s cubic-bezier(0.34,1.56,0.64,1);
}
.cp-panel.open { display: flex; }
@keyframes cp-open { from { opacity:0; transform:translateY(-6px) scale(0.97); } to { opacity:1; transform:none; } }
.cp-row-label { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 2px; }
.cp-grid { display: grid; grid-template-columns: repeat(10,1fr); gap: 4px; }
.cp-dot {
    width: 100%; aspect-ratio: 1; border-radius: 5px; border: none; cursor: pointer;
    transition: transform 0.12s, box-shadow 0.12s; padding: 0;
}
.cp-dot:hover { transform: scale(1.25); box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 1; position: relative; }
.cp-dot.selected { box-shadow: 0 0 0 2.5px white, 0 0 0 4px #2563eb; transform: scale(1.15); }
.cp-hex-row { display: flex; gap: 6px; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 2px; }
.cp-hex-input {
    flex: 1; padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 0.78rem; color: #334155; background: #f8fafc; font-family: monospace;
}
.cp-hex-input:focus { outline: none; border-color: #2563eb; background: white; }
.cp-clear-btn {
    padding: 6px 10px; background: #f1f5f9; border: 1px solid #e2e8f0;
    border-radius: 7px; font-size: 0.72rem; font-weight: 600; color: #64748b;
    cursor: pointer; white-space: nowrap;
}
.cp-clear-btn:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }

/* Opacity slider */
.opacity-row { display: flex; align-items: center; gap: 8px; flex: 1; }
.opacity-val { font-size: 0.78rem; font-weight: 700; color: #2563eb; min-width: 28px; text-align: right; }
.op-slider {
    flex: 1; -webkit-appearance: none; height: 5px;
    background: #e2e8f0; border-radius: 5px; outline: none; cursor: pointer;
}
.op-slider::-webkit-slider-thumb {
    -webkit-appearance: none; width: 17px; height: 17px;
    border-radius: 50%; background: #2563eb; cursor: pointer;
    border: 2px solid white; box-shadow: 0 0 6px rgba(37,99,235,0.35);
}
.op-slider::-moz-range-thumb {
    width: 17px; height: 17px; border-radius: 50%; background: #2563eb;
    cursor: pointer; border: 2px solid white; box-shadow: 0 0 6px rgba(37,99,235,0.35);
}

/* Image upload compact */
.img-row { display: flex; align-items: center; gap: 8px; }
.img-thumb { width: 52px; height: 34px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; flex-shrink: 0; }
.file-input { font-size: 0.75rem; color: #475569; flex: 1; min-width: 0; }
.file-input::file-selector-button {
    padding: 4px 10px; background: #f1f5f9; border: 1px solid #e2e8f0;
    border-radius: 5px; font-size: 0.72rem; font-weight: 600; color: #475569;
    cursor: pointer; margin-right: 8px;
}
.del-check { font-size: 0.72rem; color: #dc2626; display: flex; align-items: center; gap: 4px; cursor: pointer; white-space: nowrap; }
.del-check input { accent-color: #dc2626; }

/* Section divider */
.cf-divider { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 4px 2px 0; }

/* ── FAB Save Button ── */
#fab-save {
    position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%); z-index: 100;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white; border: none; border-radius: 50%;
    width: 52px; height: 52px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; box-shadow: 0 4px 20px rgba(37,99,235,0.5);
    transition: all 0.2s;
}
#fab-save:hover { transform: translateX(-50%) translateY(-2px); box-shadow: 0 8px 28px rgba(37,99,235,0.6); }
#fab-save:active { transform: translateX(-50%) scale(0.93); }
#fab-save svg { flex-shrink: 0; }

/* ── Toast Notification ── */
#toast-container {
    position: fixed; top: 14px; right: 14px; z-index: 9999;
    display: flex; flex-direction: column; gap: 8px; pointer-events: none;
}
.toast {
    background: #1e293b; color: white;
    padding: 11px 16px; border-radius: 10px;
    font-size: 0.8rem; font-weight: 600;
    display: flex; align-items: center; gap: 9px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    animation: toast-in 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
    pointer-events: all; max-width: 260px;
}
.toast.success { border-left: 3px solid #22c55e; }
.toast .toast-icon { color: #22c55e; flex-shrink: 0; }
.toast.hiding { animation: toast-out 0.25s ease forwards; }
@keyframes toast-in  { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
@keyframes toast-out { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(20px); } }
</style>

<!-- ── Toast container ── -->
<div id="toast-container"></div>

<form method="POST" enctype="multipart/form-data" id="appearance-form">
  <div class="app-form">

    <!-- ── Status & Text Color ── -->
    <div class="cf-divider">Visibilitas & Teks</div>

    <div class="cf-row">
      <div>
        <span class="cf-label">Status</span>
        <span class="cf-sub">Tampilkan bagian ini di undangan</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <input type="hidden" name="is_active" value="0">
        <label class="sw">
          <input type="checkbox" id="is_active_checkbox" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?>>
          <span class="sw-track"></span>
          <span class="sw-thumb"></span>
        </label>
        <span class="sw-label <?= $isActive ? 'on' : '' ?>" id="sw-status-label"><?= $isActive ? 'Aktif' : 'Nonaktif' ?></span>
      </div>
    </div>

    <div class="cf-row">
      <div>
        <span class="cf-label">Warna Teks</span>
        <span class="cf-sub">Sesuaikan dengan warna background</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="text_color" value="dark"  <?= $textColor === 'dark'  ? 'checked' : '' ?> onchange="if(typeof updateLiveHeroPos === 'function') updateLiveHeroPos();">
          <span class="tc-pill">Dark</span>
        </label>
        <label>
          <input type="radio" name="text_color" value="light" <?= $textColor === 'light' ? 'checked' : '' ?> onchange="if(typeof updateLiveHeroPos === 'function') updateLiveHeroPos();">
          <span class="tc-pill">Light</span>
        </label>
      </div>
    </div>

    <?php if ($activeSec === 'hero'): ?>
    <div class="cf-row">
      <div>
        <span class="cf-label">Posisi Teks / Konten</span>
        <span class="cf-sub">Tengah atau di bawah agar foto terlihat jelas</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="content_position" value="center" <?= ($contentPosition ?? 'center') === 'center' ? 'checked' : '' ?> onchange="updateLiveHeroPos('center')">
          <span class="tc-pill">Tengah</span>
        </label>
        <label>
          <input type="radio" name="content_position" value="bottom" <?= ($contentPosition ?? 'center') === 'bottom' ? 'checked' : '' ?> onchange="updateLiveHeroPos('bottom')">
          <span class="tc-pill">Bawah</span>
        </label>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($hasButtonSec): ?>
    <!-- ── Tombol ── -->
    <div class="cf-divider">
      Tombol
      <?php if ($activeSec === 'hero'): ?>
        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;font-size:0.65rem;">(Buka Undangan & Default Semua Bagian)</span>
      <?php elseif ($activeSec === 'countdown'): ?>
        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;font-size:0.65rem;">(Google Calendar)</span>
      <?php else: ?>
        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;font-size:0.65rem;">(Khusus Bagian Ini)</span>
      <?php endif; ?>
    </div>

    <?php if ($activeSec === 'countdown'): ?>
    <!-- Tampilkan Tombol Kalender -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Tombol Google Calendar</span>
        <span class="cf-sub">Tampilkan tombol pengingat kalender</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <input type="hidden" name="show_btn_calendar" value="0">
        <label class="sw">
          <input type="checkbox" id="show_btn_calendar" name="show_btn_calendar" value="1" <?= $cdShowBtnCalendar !== '0' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="sw-track"></span>
          <span class="sw-thumb"></span>
        </label>
        <span class="sw-label <?= $cdShowBtnCalendar !== '0' ? 'on' : '' ?>" id="sw-cal-label"><?= $cdShowBtnCalendar !== '0' ? 'Aktif' : 'Nonaktif' ?></span>
      </div>
    </div>

    <!-- Gaya Tombol Kalender (Outline / Solid) -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Gaya Tombol Kalender</span>
        <span class="cf-sub">Garis tepi (outline) atau warna penuh (solid)</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="btn_style" value="outline" <?= $cdBtnStyle === 'outline' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Outline</span>
        </label>
        <label>
          <input type="radio" name="btn_style" value="solid" <?= $cdBtnStyle === 'solid' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Solid</span>
        </label>
      </div>
    </div>
    <?php endif; ?>

    <!-- Bentuk Tombol: Rounded atau Kotak -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Bentuk Tombol</span>
        <span class="cf-sub">Rounded (melengkung) atau kotak (persegi)</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="btn_rounded" value="1" <?= $btnRounded ? 'checked' : '' ?> onchange="updateLiveBtnPreview(); updateLiveCdPreview();">
          <span class="tc-pill">Rounded</span>
        </label>
        <label>
          <input type="radio" name="btn_rounded" value="0" <?= !$btnRounded ? 'checked' : '' ?> onchange="updateLiveBtnPreview(); updateLiveCdPreview();">
          <span class="tc-pill">Kotak</span>
        </label>
      </div>
    </div>

    <!-- Warna Tombol -->
    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:8px;">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span class="cf-label">Warna Tombol</span>
        <span class="cf-sub"><?= $activeSec === 'hero' ? 'Warna utama tombol' : 'Kosong = ikuti warna hero' ?></span>
      </div>
      <div class="color-row">
        <button type="button" class="cp-swatch-btn" id="btn-cp-swatch"
          onclick="toggleBtnColorPicker()"
          title="Pilih warna tombol">
          <span class="cp-swatch-inner" id="btn-cp-swatch-inner" style="background:<?= htmlspecialchars($btnColor ?: '') ?>"></span>
        </button>
        <input type="text" name="btn_color" id="btn_color" class="color-text"
          value="<?= htmlspecialchars($btnColor) ?>"
          placeholder="<?= !empty($heroCfg['btn_color']) && $activeSec !== 'hero' ? 'Ikuti Hero (' . htmlspecialchars($heroCfg['btn_color']) . ')' : 'Default: #c5a059 (Gold)' ?>"
          oninput="onBtnHexInput(this.value); updateLiveCdPreview();">
      </div>
      <!-- Modern color palette panel for button -->
      <div class="cp-panel" id="btn-cp-panel">
        <div>
          <div class="cp-row-label">Pilihan Warna</div>
          <div class="cp-grid" id="btn-cp-grid"></div>
        </div>
        <div class="cp-hex-row">
          <input type="text" class="cp-hex-input" id="btn-cp-hex" placeholder="#rrggbb">
          <button type="button" class="cp-clear-btn" onclick="selectBtnColor(''); updateLiveCdPreview();">✕ Default</button>
        </div>
      </div>
    </div>

    <!-- Warna Teks Tombol -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Warna Teks Tombol</span>
        <span class="cf-sub">Warna tulisan / ikon di dalam tombol</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="btn_text_color" value="light" <?= $btnTextColor === 'light' ? 'checked' : '' ?> onchange="updateLiveBtnPreview(); updateLiveCdPreview();">
          <span class="tc-pill">Light (Putih)</span>
        </label>
        <label>
          <input type="radio" name="btn_text_color" value="dark"  <?= $btnTextColor === 'dark'  ? 'checked' : '' ?> onchange="updateLiveBtnPreview(); updateLiveCdPreview();">
          <span class="tc-pill">Dark (Gelap)</span>
        </label>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($activeSec === 'countdown'): ?>
    <!-- ── Kotak Angka Countdown ── -->
    <div class="cf-divider">Kotak Angka Countdown</div>

    <!-- Bentuk Kotak Angka -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Bentuk Kotak Angka</span>
        <span class="cf-sub">Rounded (melengkung) atau kotak (persegi)</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="box_rounded" value="1" <?= $cdBoxRounded !== '0' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Rounded</span>
        </label>
        <label>
          <input type="radio" name="box_rounded" value="0" <?= $cdBoxRounded === '0' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Kotak</span>
        </label>
      </div>
    </div>

    <!-- Gaya Background Kotak -->
    <div class="cf-row">
      <div>
        <span class="cf-label">Tampilan Kotak</span>
        <span class="cf-sub">Latar belakang kartu angka</span>
      </div>
      <div class="tc-group">
        <label>
          <input type="radio" name="box_bg_style" value="glass" <?= $cdBoxBgStyle === 'glass' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Kaca</span>
        </label>
        <label>
          <input type="radio" name="box_bg_style" value="white" <?= $cdBoxBgStyle === 'white' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Putih</span>
        </label>
        <label>
          <input type="radio" name="box_bg_style" value="dark"  <?= $cdBoxBgStyle === 'dark'  ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Gelap</span>
        </label>
        <label>
          <input type="radio" name="box_bg_style" value="trans" <?= $cdBoxBgStyle === 'trans' ? 'checked' : '' ?> onchange="updateLiveCdPreview()">
          <span class="tc-pill">Polos</span>
        </label>
      </div>
    </div>

    <!-- Warna Angka Countdown -->
    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:8px;">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span class="cf-label">Warna Angka Countdown</span>
        <span class="cf-sub">Warna teks angka (Hari, Jam, Menit, Detik)</span>
      </div>
      <div class="color-row">
        <button type="button" class="cp-swatch-btn" id="num-cp-swatch"
          onclick="toggleNumColorPicker()"
          title="Pilih warna angka">
          <span class="cp-swatch-inner" id="num-cp-swatch-inner" style="background:<?= htmlspecialchars($cdBoxNumColor ?: '') ?>"></span>
        </button>
        <input type="text" name="box_num_color" id="box_num_color" class="color-text"
          value="<?= htmlspecialchars($cdBoxNumColor) ?>"
          placeholder="Default (Warna tema / gelap)"
          oninput="onNumHexInput(this.value)">
      </div>
      <!-- Panel Palette Angka -->
      <div class="cp-panel" id="num-cp-panel">
        <div>
          <div class="cp-row-label">Pilihan Warna Angka</div>
          <div class="cp-grid" id="num-cp-grid"></div>
        </div>
        <div class="cp-hex-row">
          <input type="text" class="cp-hex-input" id="num-cp-hex" placeholder="#rrggbb">
          <button type="button" class="cp-clear-btn" onclick="selectNumColor('')">✕ Default</button>
        </div>
      </div>
    </div>

    <!-- ── Kustomisasi Teks Countdown ── -->
    <div class="cf-divider">Teks Countdown</div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:6px;">
      <span class="cf-label">Judul Countdown</span>
      <input type="text" name="countdown_title" id="countdown_title" class="color-text" style="font-family:inherit;"
        value="<?= htmlspecialchars($cdTitleValue) ?>"
        placeholder="Menuju Hari Bahagia"
        oninput="updateLiveCdPreview()">
    </div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:6px;">
      <span class="cf-label">Subjudul Countdown</span>
      <input type="text" name="countdown_subtitle" id="countdown_subtitle" class="color-text" style="font-family:inherit;"
        value="<?= htmlspecialchars($cdSubtitleValue) ?>"
        placeholder="Menghitung hari menuju ikatan suci"
        oninput="updateLiveCdPreview()">
    </div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:6px;">
      <span class="cf-label">Pesan Waktu Selesai (Hari H)</span>
      <input type="text" name="countdown_ended_text" id="countdown_ended_text" class="color-text" style="font-family:inherit;"
        value="<?= htmlspecialchars($cdEndedTextValue) ?>"
        placeholder="HARI BAHAGIA TELAH TIBA! 🌸"
        oninput="updateLiveCdPreview()">
    </div>
    <?php endif; ?>

    <!-- ── Background ── -->
    <div class="cf-divider">Background</div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:8px;">
      <span class="cf-label">Warna Background</span>
      <div class="color-row">
        <button type="button" class="cp-swatch-btn <?= $bgColor ? '' : '' ?>" id="cp-swatch"
          onclick="toggleColorPicker()"
          title="Pilih warna">
          <span class="cp-swatch-inner" id="cp-swatch-inner" style="background:<?= htmlspecialchars($bgColor ?: '') ?>"></span>
        </button>
        <input type="text" name="bg_color" id="bg_color" class="color-text"
          value="<?= htmlspecialchars($bgColor) ?>"
          placeholder="Kosong = transparan"
          oninput="onHexInput(this.value)">
      </div>
      <!-- Modern color palette panel -->
      <div class="cp-panel" id="cp-panel">
        <div>
          <div class="cp-row-label">Netral</div>
          <div class="cp-grid" id="cp-grid"></div>
        </div>
        <div class="cp-hex-row">
          <input type="text" class="cp-hex-input" id="cp-hex" placeholder="#rrggbb atau rgba(...)">
          <button type="button" class="cp-clear-btn" onclick="selectColor('')">✕ Hapus</button>
        </div>
      </div>
    </div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:6px;">
      <div style="display:flex;justify-content:space-between;">
        <span class="cf-label">Opasitas Gambar</span>
        <span class="opacity-val" id="opacity_val"><?= htmlspecialchars($bgOpacity) ?></span>
      </div>
      <input type="range" name="bg_opacity" class="op-slider"
        step="0.01" min="0" max="1"
        value="<?= htmlspecialchars($bgOpacity) ?>"
        oninput="document.getElementById('opacity_val').textContent = (+this.value).toFixed(2)">
    </div>

    <div class="cf-row" style="flex-direction:column;align-items:stretch;gap:8px;">
      <span class="cf-label">Gambar Latar</span>
      <?php if (!empty($cfg['bg_image'])):
          $bgSrc = $cfg['bg_image'];
          if (preg_match('#(uploads/.+)$#', $bgSrc, $_m)) {
              $bgSrc = rtrim($GLOBALS['base'] ?? '/', '/') . '/' . $_m[1];
          }
      ?>
      <div class="img-row">
        <img src="<?= $bgSrc ?>" alt="bg" class="img-thumb">
        <label class="del-check">
          <input type="checkbox" name="remove_bg" value="1"> Hapus
        </label>
      </div>
      <?php endif; ?>
      <input type="file" name="bg_image" class="file-input" accept="image/*">
    </div>

  </div><!-- /.app-form -->

  <!-- ── Floating Save Button ── -->
  <button type="submit" id="fab-save" title="Simpan">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
      <polyline points="17 21 17 13 7 13 7 21"></polyline>
      <polyline points="7 3 7 8 15 8"></polyline>
    </svg>
  </button>

</form>

<script>
/* ── Modern Color Picker ── */
const CP_PALETTE = [
  // Slate / neutral
  ['#f8fafc','#f1f5f9','#e2e8f0','#cbd5e1','#94a3b8','#64748b','#475569','#334155','#1e293b','#0f172a'],
  // Stone / warm neutral
  ['#fafaf9','#f5f5f4','#e7e5e4','#d6d3d1','#a8a29e','#78716c','#57534e','#44403c','#292524','#1c1917'],
  // Rose / pink
  ['#fff1f2','#ffe4e6','#fecdd3','#fda4af','#fb7185','#f43f5e','#e11d48','#be123c','#9f1239','#881337'],
  // Amber / gold (wedding)
  ['#fffbeb','#fef3c7','#fde68a','#fcd34d','#fbbf24','#f59e0b','#d97706','#b45309','#92400e','#78350f'],
  // Emerald / sage
  ['#ecfdf5','#d1fae5','#a7f3d0','#6ee7b7','#34d399','#10b981','#059669','#047857','#065f46','#064e3b'],
  // Sky / blue
  ['#f0f9ff','#e0f2fe','#bae6fd','#7dd3fc','#38bdf8','#0ea5e9','#0284c7','#0369a1','#075985','#0c4a6e'],
  // Violet / mauve
  ['#faf5ff','#f3e8ff','#e9d5ff','#d8b4fe','#c084fc','#a855f7','#9333ea','#7e22ce','#6b21a8','#581c87'],
  // Nude / brown (wedding)
  ['#fdf8f0','#f5e6d3','#e8c9a0','#d4a976','#c28c5e','#a67045','#8b5e38','#6b4226','#4a2e1b','#2d1a0e'],
];
const CP_LABELS = ['Netral','Batu','Mawar','Emas / Amber','Hijau','Biru','Ungu / Mauve','Nude / Coklat'];

let _cpOpen = false;
let _cpSelected = document.getElementById('bg_color')?.value || '';

function buildPalette() {
    const grid = document.getElementById('cp-grid');
    if (!grid) return;
    // Build all rows inside the single grid container
    // First clear the panel and rebuild all rows
    const panel = document.getElementById('cp-panel');
    if (!panel) return;
    // Remove existing palette rows
    while (panel.firstChild) panel.removeChild(panel.firstChild);

    CP_PALETTE.forEach(function(row, ri) {
        const wrapper = document.createElement('div');
        const lbl = document.createElement('div');
        lbl.className = 'cp-row-label';
        lbl.textContent = CP_LABELS[ri];
        const g = document.createElement('div');
        g.className = 'cp-grid';
        row.forEach(function(hex) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cp-dot' + (hex.toLowerCase() === _cpSelected.toLowerCase() ? ' selected' : '');
            btn.style.background = hex;
            btn.title = hex;
            btn.setAttribute('data-color', hex);
            btn.onclick = function() { selectColor(hex); };
            g.appendChild(btn);
        });
        wrapper.appendChild(lbl);
        wrapper.appendChild(g);
        panel.appendChild(wrapper);
    });

    // Hex row
    const hexRow = document.createElement('div');
    hexRow.className = 'cp-hex-row';
    hexRow.innerHTML = '<input type="text" class="cp-hex-input" id="cp-hex" placeholder="#rrggbb ou rgba(...)">' +
        '<button type="button" class="cp-clear-btn" onclick="selectColor(\'\')">✕ Hapus</button>';
    panel.appendChild(hexRow);
    document.getElementById('cp-hex').addEventListener('input', function() { onHexInput(this.value, true); });
}

function toggleColorPicker() {
    const panel = document.getElementById('cp-panel');
    const btn   = document.getElementById('cp-swatch');
    if (!panel) return;
    _cpOpen = !_cpOpen;
    panel.classList.toggle('open', _cpOpen);
    btn.classList.toggle('active', _cpOpen);
    if (_cpOpen) buildPalette();
}

function selectColor(hex) {
    _cpSelected = hex;
    document.getElementById('bg_color').value = hex;
    const inner = document.getElementById('cp-swatch-inner');
    if (inner) inner.style.background = hex;
    const swBtn = document.getElementById('cp-swatch');
    if (swBtn) {
        if (!hex) { swBtn.setAttribute('data-empty','1'); } else { swBtn.removeAttribute('data-empty'); }
    }
    const hexI = document.getElementById('cp-hex');
    if (hexI) hexI.value = hex;
    // Update selected dot highlights
    document.querySelectorAll('.cp-dot').forEach(function(d) {
        d.classList.toggle('selected', d.getAttribute('data-color').toLowerCase() === hex.toLowerCase());
    });
}

function onHexInput(val, fromHexField) {
    document.getElementById('bg_color').value = val;
    const inner = document.getElementById('cp-swatch-inner');
    const swBtn = document.getElementById('cp-swatch');
    if (/^#[0-9a-fA-F]{3,8}$/.test(val) || /^rgba?\(/.test(val)) {
        if (inner) inner.style.background = val;
        if (swBtn) swBtn.removeAttribute('data-empty');
        _cpSelected = val;
    } else if (!val) {
        if (inner) inner.style.background = '';
        if (swBtn) swBtn.setAttribute('data-empty','1');
        _cpSelected = '';
    }
    if (!fromHexField) {
        const hexI = document.getElementById('cp-hex');
        if (hexI) hexI.value = val;
    }
}

// Close picker when clicking outside
document.addEventListener('click', function(e) {
    if (_cpOpen) {
        const panel = document.getElementById('cp-panel');
        const swatch = document.getElementById('cp-swatch');
        if (panel && !panel.contains(e.target) && e.target !== swatch && !swatch.contains(e.target)) {
            _cpOpen = false;
            panel.classList.remove('open');
            swatch.classList.remove('active');
        }
    }
    if (_btnCpOpen) {
        const btnPanel = document.getElementById('btn-cp-panel');
        const btnSwatch = document.getElementById('btn-cp-swatch');
        if (btnPanel && !btnPanel.contains(e.target) && e.target !== btnSwatch && !btnSwatch.contains(e.target)) {
            _btnCpOpen = false;
            btnPanel.classList.remove('open');
            btnSwatch.classList.remove('active');
        }
    }
    if (_numCpOpen) {
        const numPanel = document.getElementById('num-cp-panel');
        const numSwatch = document.getElementById('num-cp-swatch');
        if (numPanel && !numPanel.contains(e.target) && e.target !== numSwatch && !numSwatch.contains(e.target)) {
            _numCpOpen = false;
            numPanel.classList.remove('open');
            numSwatch.classList.remove('active');
        }
    }
});

/* ── Modern Button Color Picker & Live Preview ── */
let _btnCpOpen = false;
let _btnCpSelected = document.getElementById('btn_color')?.value || '';

function buildBtnPalette() {
    const panel = document.getElementById('btn-cp-panel');
    if (!panel) return;
    while (panel.firstChild) panel.removeChild(panel.firstChild);

    CP_PALETTE.forEach(function(row, ri) {
        const wrapper = document.createElement('div');
        const lbl = document.createElement('div');
        lbl.className = 'cp-row-label';
        lbl.textContent = CP_LABELS[ri];
        const g = document.createElement('div');
        g.className = 'cp-grid';
        row.forEach(function(hex) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cp-dot' + (hex.toLowerCase() === _btnCpSelected.toLowerCase() ? ' selected' : '');
            btn.style.background = hex;
            btn.title = hex;
            btn.setAttribute('data-color', hex);
            btn.onclick = function() { selectBtnColor(hex); };
            g.appendChild(btn);
        });
        wrapper.appendChild(lbl);
        wrapper.appendChild(g);
        panel.appendChild(wrapper);
    });

    const hexRow = document.createElement('div');
    hexRow.className = 'cp-hex-row';
    hexRow.innerHTML = '<input type="text" class="cp-hex-input" id="btn-cp-hex" placeholder="#rrggbb">' +
        '<button type="button" class="cp-clear-btn" onclick="selectBtnColor(\'\')">✕ Default</button>';
    panel.appendChild(hexRow);
    const hexInput = document.getElementById('btn-cp-hex');
    if (hexInput) {
        hexInput.value = _btnCpSelected;
        hexInput.addEventListener('input', function() { onBtnHexInput(this.value, true); });
    }
}

function toggleBtnColorPicker() {
    const panel = document.getElementById('btn-cp-panel');
    const btn   = document.getElementById('btn-cp-swatch');
    if (!panel) return;
    _btnCpOpen = !_btnCpOpen;
    panel.classList.toggle('open', _btnCpOpen);
    btn.classList.toggle('active', _btnCpOpen);
    if (_btnCpOpen) buildBtnPalette();
}

function selectBtnColor(hex) {
    _btnCpSelected = hex;
    const input = document.getElementById('btn_color');
    if (input) input.value = hex;
    const inner = document.getElementById('btn-cp-swatch-inner');
    if (inner) inner.style.background = hex;
    const swBtn = document.getElementById('btn-cp-swatch');
    if (swBtn) {
        if (!hex) { swBtn.setAttribute('data-empty','1'); } else { swBtn.removeAttribute('data-empty'); }
    }
    const hexI = document.getElementById('btn-cp-hex');
    if (hexI) hexI.value = hex;
    document.querySelectorAll('#btn-cp-panel .cp-dot').forEach(function(d) {
        d.classList.toggle('selected', d.getAttribute('data-color').toLowerCase() === hex.toLowerCase());
    });
    updateLiveBtnPreview();
}

function onBtnHexInput(val, fromHexField) {
    const input = document.getElementById('btn_color');
    if (input) input.value = val;
    const inner = document.getElementById('btn-cp-swatch-inner');
    const swBtn = document.getElementById('btn-cp-swatch');
    if (/^#[0-9a-fA-F]{3,8}$/.test(val) || /^rgba?\(/.test(val)) {
        if (inner) inner.style.background = val;
        if (swBtn) swBtn.removeAttribute('data-empty');
        _btnCpSelected = val;
    } else if (!val) {
        if (inner) inner.style.background = '';
        if (swBtn) swBtn.setAttribute('data-empty','1');
        _btnCpSelected = '';
    }
    if (!fromHexField) {
        const hexI = document.getElementById('btn-cp-hex');
        if (hexI) hexI.value = val;
    }
    updateLiveBtnPreview();
}

function updateLiveBtnPreview() {
    try {
        const frame = document.getElementById('previewFrame');
        if (!frame || !frame.contentDocument) return;
        const doc = frame.contentDocument;
        let dynamicStyle = doc.getElementById('dynamic-btn-live-style');
        if (!dynamicStyle) {
            dynamicStyle = doc.createElement('style');
            dynamicStyle.id = 'dynamic-btn-live-style';
            doc.head.appendChild(dynamicStyle);
        }

        const isRounded = document.querySelector('input[name="btn_rounded"]:checked')?.value !== '0';
        const color = document.getElementById('btn_color')?.value?.trim() || '';
        const textColorRadio = document.querySelector('input[name="btn_text_color"]:checked')?.value || 'light';
        const textColor = textColorRadio === 'dark' ? '#1e293b' : '#ffffff';

        let css = '';
        const radiusVal = isRounded ? '50px !important' : '4px !important';
        css += `.btn-open, .btn-reminder, .btn-map-bottom, .btn-copy, .btn-map, .btn-submit { border-radius: ${radiusVal}; }\n`;

        // Always apply text and icon color to buttons (including envelope icon)
        css += `.btn-open, .btn-open span { color: ${textColor} !important; }\n`;
        css += `.btn-open svg, .btn-open svg path, .btn-open svg polyline { stroke: ${textColor} !important; color: ${textColor} !important; }\n`;
        css += `.btn-submit, .btn-submit span { color: ${textColor} !important; }\n`;
        css += `.btn-submit svg, .btn-submit svg * { stroke: ${textColor} !important; color: ${textColor} !important; }\n`;

        if (color) {
            css += `.btn-open { background: ${color} !important; border-color: ${color} !important; }\n`;
            css += `.btn-submit { background: ${color} !important; }\n`;
            css += `.btn-reminder, .btn-map-bottom, .btn-copy { border-color: ${color} !important; color: ${color} !important; }\n`;
            css += `.btn-reminder svg, .btn-reminder svg * { color: ${color} !important; stroke: ${color} !important; }\n`;
            css += `.btn-reminder:hover, .btn-map-bottom:hover, .btn-copy:hover { background: ${color} !important; color: ${textColor} !important; border-color: ${color} !important; }\n`;
            css += `.btn-reminder:hover svg, .btn-reminder:hover svg *, .btn-map-bottom:hover svg, .btn-map-bottom:hover svg *, .btn-copy:hover svg, .btn-copy:hover svg * { color: ${textColor} !important; stroke: ${textColor} !important; }\n`;
        }
        dynamicStyle.textContent = css;
    } catch(e) {
        // frame not loaded or cross-origin
    }
}

/* ── Modern Number Color Picker & Live Preview for Countdown ── */
let _numCpOpen = false;
let _numCpSelected = document.getElementById('box_num_color')?.value || '';

function buildNumPalette() {
    const panel = document.getElementById('num-cp-panel');
    if (!panel) return;
    while (panel.firstChild) panel.removeChild(panel.firstChild);

    CP_PALETTE.forEach(function(row, ri) {
        const wrapper = document.createElement('div');
        const lbl = document.createElement('div');
        lbl.className = 'cp-row-label';
        lbl.textContent = CP_LABELS[ri];
        const g = document.createElement('div');
        g.className = 'cp-grid';
        row.forEach(function(hex) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cp-dot' + (hex.toLowerCase() === _numCpSelected.toLowerCase() ? ' selected' : '');
            btn.style.background = hex;
            btn.title = hex;
            btn.setAttribute('data-color', hex);
            btn.onclick = function() { selectNumColor(hex); };
            g.appendChild(btn);
        });
        wrapper.appendChild(lbl);
        wrapper.appendChild(g);
        panel.appendChild(wrapper);
    });

    const hexRow = document.createElement('div');
    hexRow.className = 'cp-hex-row';
    hexRow.innerHTML = '<input type="text" class="cp-hex-input" id="num-cp-hex" placeholder="#rrggbb">' +
        '<button type="button" class="cp-clear-btn" onclick="selectNumColor(\'\')">✕ Default</button>';
    panel.appendChild(hexRow);
    const hexInput = document.getElementById('num-cp-hex');
    if (hexInput) {
        hexInput.value = _numCpSelected;
        hexInput.addEventListener('input', function() { onNumHexInput(this.value, true); });
    }
}

function toggleNumColorPicker() {
    const panel = document.getElementById('num-cp-panel');
    const btn   = document.getElementById('num-cp-swatch');
    if (!panel) return;
    _numCpOpen = !_numCpOpen;
    panel.classList.toggle('open', _numCpOpen);
    btn.classList.toggle('active', _numCpOpen);
    if (_numCpOpen) buildNumPalette();
}

function selectNumColor(hex) {
    _numCpSelected = hex;
    const input = document.getElementById('box_num_color');
    if (input) input.value = hex;
    const inner = document.getElementById('num-cp-swatch-inner');
    if (inner) inner.style.background = hex;
    const swBtn = document.getElementById('num-cp-swatch');
    if (swBtn) {
        if (!hex) { swBtn.setAttribute('data-empty','1'); } else { swBtn.removeAttribute('data-empty'); }
    }
    const hexI = document.getElementById('num-cp-hex');
    if (hexI) hexI.value = hex;
    document.querySelectorAll('#num-cp-panel .cp-dot').forEach(function(d) {
        d.classList.toggle('selected', d.getAttribute('data-color').toLowerCase() === hex.toLowerCase());
    });
    updateLiveCdPreview();
}

function onNumHexInput(val, fromHexField) {
    const input = document.getElementById('box_num_color');
    if (input) input.value = val;
    const inner = document.getElementById('num-cp-swatch-inner');
    const swBtn = document.getElementById('num-cp-swatch');
    if (/^#[0-9a-fA-F]{3,8}$/.test(val) || /^rgba?\(/.test(val)) {
        if (inner) inner.style.background = val;
        if (swBtn) swBtn.removeAttribute('data-empty');
        _numCpSelected = val;
    } else if (!val) {
        if (inner) inner.style.background = '';
        if (swBtn) swBtn.setAttribute('data-empty','1');
        _numCpSelected = '';
    }
    if (!fromHexField) {
        const hexI = document.getElementById('num-cp-hex');
        if (hexI) hexI.value = val;
    }
    updateLiveCdPreview();
}

function updateLiveCdPreview() {
    try {
        const frame = document.getElementById('previewFrame');
        if (!frame || !frame.contentDocument) return;
        const doc = frame.contentDocument;
        let dynamicStyle = doc.getElementById('dynamic-cd-live-style');
        if (!dynamicStyle) {
            dynamicStyle = doc.createElement('style');
            dynamicStyle.id = 'dynamic-cd-live-style';
            doc.head.appendChild(dynamicStyle);
        }

        // Box rounded
        const isBoxRounded = document.querySelector('input[name="box_rounded"]:checked')?.value !== '0';
        const boxRadius = isBoxRounded ? '18px !important' : '4px !important';

        // Box style
        const boxStyle = document.querySelector('input[name="box_bg_style"]:checked')?.value || 'glass';
        let boxCss = '';
        if (boxStyle === 'white') {
            boxCss = 'background: #ffffff !important; border: 1px solid #f1f5f9 !important; box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important;';
        } else if (boxStyle === 'dark') {
            boxCss = 'background: rgba(15, 23, 42, 0.85) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; box-shadow: 0 8px 24px rgba(0,0,0,0.2) !important; backdrop-filter: blur(15px) !important; -webkit-backdrop-filter: blur(15px) !important;';
        } else if (boxStyle === 'trans') {
            boxCss = 'background: transparent !important; border: none !important; box-shadow: none !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important;';
        } else {
            // glass
            boxCss = 'background: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(15px) !important; -webkit-backdrop-filter: blur(15px) !important; border: 1px solid rgba(0, 0, 0, 0.05) !important;';
        }

        // Num color
        const numColor = document.getElementById('box_num_color')?.value?.trim() || '';

        // Calendar button style
        const btnStyle = document.querySelector('input[name="btn_style"]:checked')?.value || 'outline';
        const btnColor = document.getElementById('btn_color')?.value?.trim() || '#c5a059';
        const btnTextRadio = document.querySelector('input[name="btn_text_color"]:checked')?.value || 'light';
        const btnTextColor = btnTextRadio === 'dark' ? '#1e293b' : '#ffffff';

        let css = '';
        css += `#countdown-section .countdown-item { border-radius: ${boxRadius}; ${boxCss} }\n`;
        if (boxStyle === 'dark') {
            css += `#countdown-section .countdown-item label { color: #94a3b8 !important; }\n`;
            if (!numColor) {
                css += `#countdown-section .countdown-item span { color: #ffffff !important; }\n`;
            }
        }
        if (numColor) {
            css += `#countdown-section .countdown-item span { color: ${numColor} !important; }\n`;
        }
        if (btnStyle === 'solid') {
            css += `#countdown-section .btn-reminder { background: ${btnColor} !important; color: ${btnTextColor} !important; border-color: ${btnColor} !important; }\n`;
            css += `#countdown-section .btn-reminder svg { color: ${btnTextColor} !important; stroke: ${btnTextColor} !important; }\n`;
        } else {
            css += `#countdown-section .btn-reminder { background: transparent !important; color: ${btnColor} !important; border: 1.5px solid ${btnColor} !important; }\n`;
            css += `#countdown-section .btn-reminder svg { color: ${btnColor} !important; stroke: ${btnColor} !important; }\n`;
        }

        dynamicStyle.textContent = css;

        // Toggle calendar button wrapper
        const showCal = document.getElementById('show_btn_calendar');
        const calWrapper = doc.getElementById('gcalBtnWrapper');
        if (showCal && calWrapper) {
            calWrapper.style.display = showCal.checked ? 'block' : 'none';
        }

        // Text live update
        const titleInput = document.getElementById('countdown_title');
        const titleEl = doc.getElementById('cdTitleText');
        if (titleInput && titleEl) {
            titleEl.textContent = titleInput.value.trim() || 'Menuju Hari Bahagia';
        }

        const subtitleInput = document.getElementById('countdown_subtitle');
        const subtitleEl = doc.getElementById('cdSubtitleText');
        if (subtitleInput && subtitleEl) {
            subtitleEl.textContent = subtitleInput.value.trim() || 'Menghitung hari menuju ikatan suci';
        }

        const endedInput = document.getElementById('countdown_ended_text');
        const endedEl = doc.getElementById('happyMessage');
        if (endedInput && endedEl) {
            endedEl.textContent = endedInput.value.trim() || 'HARI BAHAGIA TELAH TIBA! 🌸';
        }
    } catch(e) {}
}

function updateLiveHeroPos(pos) {
    try {
        const frame = document.getElementById('previewFrame');
        if (!frame || !frame.contentDocument) return;
        const doc = frame.contentDocument;
        let dynamicStyle = doc.getElementById('dynamic-hero-pos-style');
        if (!dynamicStyle) {
            dynamicStyle = doc.createElement('style');
            dynamicStyle.id = 'dynamic-hero-pos-style';
            doc.head.appendChild(dynamicStyle);
        }

        const selectedPos = pos || document.querySelector('input[name="content_position"]:checked')?.value || 'center';
        if (selectedPos === 'bottom') {
            const isLightHeroText = document.querySelector('input[name="text_color"]:checked')?.value !== 'dark';
            const botGradient = isLightHeroText 
                ? 'linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.55) 60%, rgba(0,0,0,0.2) 85%, transparent 100%)' 
                : 'linear-gradient(to top, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.65) 60%, rgba(255,255,255,0.2) 85%, transparent 100%)';

            dynamicStyle.textContent = `
                #openingOverlay, .hero { 
                    justify-content: flex-end !important; 
                    padding-bottom: 0 !important; 
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
                #openingOverlay .overlay-content, .hero > div { 
                    margin-bottom: 0 !important; 
                    width: 100% !important; 
                    max-width: 100% !important; 
                    padding: 50px 20px max(24px, env(safe-area-inset-bottom, 24px)) !important; 
                    background: ${botGradient} !important; 
                    border-radius: 0 !important; 
                    box-sizing: border-box !important;
                }
                #openingOverlay .overlay-content p { 
                    margin-bottom: 10px !important; 
                    font-size: 0.95rem !important; 
                    letter-spacing: 2.5px !important; 
                }
                #openingOverlay .overlay-content h1 { 
                    font-size: clamp(2.2rem, 9vw, 3.8rem) !important; 
                    line-height: 1.15 !important; 
                }
                #openingOverlay .btn-open { 
                    margin: 20px auto 0 !important; 
                    padding: 12px 34px !important; 
                }
            `;
        } else {
            dynamicStyle.textContent = '';
        }
    } catch(e) {}
}

// Init swatch state on load + scroll active tab into view
document.addEventListener('DOMContentLoaded', function() {
    const val = document.getElementById('bg_color')?.value;
    const swBtn = document.getElementById('cp-swatch');
    if (!val && swBtn) swBtn.setAttribute('data-empty','1');

    const btnVal = document.getElementById('btn_color')?.value;
    const btnSw = document.getElementById('btn-cp-swatch');
    if (!btnVal && btnSw) btnSw.setAttribute('data-empty','1');

    const numVal = document.getElementById('box_num_color')?.value;
    const numSw = document.getElementById('num-cp-swatch');
    if (!numVal && numSw) numSw.setAttribute('data-empty','1');

    // Attach iframe load listener for real-time preview sync
    const frame = document.getElementById('previewFrame');
    if (frame) {
        frame.addEventListener('load', function() {
            setTimeout(function() {
                updateLiveBtnPreview();
                updateLiveCdPreview();
                updateLiveHeroPos();
            }, 100);
        });
    }

    // Calendar toggle switch label listener
    const calSw = document.getElementById('show_btn_calendar');
    if (calSw) {
        calSw.addEventListener('change', function() {
            const lbl = document.getElementById('sw-cal-label');
            if (lbl) {
                lbl.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                lbl.className = 'sw-label ' + (this.checked ? 'on' : '');
            }
        });
    }

    // Scroll active tab to center of tab nav
    const nav    = document.getElementById('panel-tabs');
    const active = nav ? nav.querySelector('.ptab-active') : null;
    if (nav && active) {
        nav.scrollLeft = active.offsetLeft - (nav.clientWidth / 2) + (active.offsetWidth / 2);
    }
});

/* ── Toggle switch label update ── */
document.getElementById('is_active_checkbox').addEventListener('change', function() {
    const lbl = document.getElementById('sw-status-label');
    lbl.textContent = this.checked ? 'Aktif' : 'Nonaktif';
    lbl.className = 'sw-label ' + (this.checked ? 'on' : '');
});

/* ── Toast system ── */
function showToast(msg, type) {
    const c = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = 'toast ' + (type || 'success');
    t.innerHTML = '<svg class="toast-icon" viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>' + msg;
    c.appendChild(t);
    setTimeout(function() {
        t.classList.add('hiding');
        setTimeout(function() { t.remove(); }, 280);
    }, 3200);
}

/* ── Show toast on saved ── */
<?php if (isset($_GET['saved'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    showToast('Tampilan <?= htmlspecialchars($meta['title']) ?> disimpan!', 'success');
    // Reload preview iframe after save
    setTimeout(reloadPreview, 400);
});
<?php endif; ?>
</script>

    </div><!-- /#panel-body -->
  </div><!-- /#settings-panel -->
</div><!-- /#app-layout -->

<script>
function toggleMinimize() {
    document.getElementById('settings-panel').classList.toggle('panel-minimized');
}
function reloadPreview() {
    const f = document.getElementById('previewFrame');
    if (!f) return;
    const s = f.src;
    f.src = '';
    setTimeout(function() { f.src = s; }, 80);
}
</script>
</body>
</html>
