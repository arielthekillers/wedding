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
          <input type="radio" name="text_color" value="dark"  <?= $textColor === 'dark'  ? 'checked' : '' ?>>
          <span class="tc-pill">Dark</span>
        </label>
        <label>
          <input type="radio" name="text_color" value="light" <?= $textColor === 'light' ? 'checked' : '' ?>>
          <span class="tc-pill">Light</span>
        </label>
      </div>
    </div>

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
    if (!_cpOpen) return;
    const panel = document.getElementById('cp-panel');
    const swatch = document.getElementById('cp-swatch');
    if (panel && !panel.contains(e.target) && e.target !== swatch && !swatch.contains(e.target)) {
        _cpOpen = false;
        panel.classList.remove('open');
        swatch.classList.remove('active');
    }
});

// Init swatch state on load + scroll active tab into view
document.addEventListener('DOMContentLoaded', function() {
    const val = document.getElementById('bg_color').value;
    const swBtn = document.getElementById('cp-swatch');
    if (!val && swBtn) swBtn.setAttribute('data-empty','1');

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
