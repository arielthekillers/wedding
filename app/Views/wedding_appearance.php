<?php
$activeTab = $tab; // This maps to appearance, appearance_countdown, etc.
include __DIR__ . '/partials/wedding_nav.php';

$sectionsMeta = [
    'hero'       => ['title' => 'Hero / Sampul', 'desc' => 'Bagian paling atas undangan', 'default_opacity' => '0.70'],
    'countdown'  => ['title' => 'Hitung Mundur (Countdown)', 'desc' => 'Waktu menuju hari bahagia', 'default_opacity' => '0.08'],
    'couple'     => ['title' => 'Pasangan (Couple)', 'desc' => 'Profil pengantin pria dan wanita', 'default_opacity' => '0.08'],
    'love_story' => ['title' => 'Love Story', 'desc' => 'Perjalanan cinta pengantin', 'default_opacity' => '0.08'],
    'events'     => ['title' => 'Acara (Events)', 'desc' => 'Informasi akad dan resepsi', 'default_opacity' => '0.08'],
    'gallery'    => ['title' => 'Galeri', 'desc' => 'Kumpulan foto-foto momen', 'default_opacity' => '0.08'],
    'gifts'      => ['title' => 'Hadiah / Amplop', 'desc' => 'Informasi rekening dan alamat', 'default_opacity' => '0.08'],
    'guestbook'  => ['title' => 'Buku Tamu (RSVP)', 'desc' => 'Ucapan doa dan konfirmasi kehadiran', 'default_opacity' => '0.08'],
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

$activeSec = $secMap[$activeTab] ?? 'hero';
$meta = $sectionsMeta[$activeSec];
$defaultOp = $meta['default_opacity'];

$sectionSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
$cfg = $sectionSettings[$activeSec] ?? ['bg_color' => '', 'bg_image' => '', 'bg_opacity' => $defaultOp];
if (!isset($cfg['bg_opacity']) || $cfg['bg_opacity'] === '') $cfg['bg_opacity'] = $defaultOp;
?>

<style>
.color-picker-wrapper {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid var(--border);
    box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    flex-shrink: 0;
    cursor: pointer;
}
.color-picker-wrapper input[type="color"] {
    position: absolute;
    top: -10px;
    left: -10px;
    width: 64px;
    height: 64px;
    border: none;
    cursor: pointer;
    padding: 0;
    background: none;
}
.form-range {
    width: 100%;
    height: 6px;
    background: #e2e8f0;
    border-radius: 5px;
    outline: none;
    -webkit-appearance: none;
    margin-top: 10px;
}
.form-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary);
    cursor: pointer;
    box-shadow: 0 0 10px rgba(37, 99, 235, 0.4);
    border: 2px solid white;
}
.form-range::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary);
    cursor: pointer;
    box-shadow: 0 0 10px rgba(37, 99, 235, 0.4);
    border: 2px solid white;
}
/* Switch toggle classes */
.switch-toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
    margin: 0;
}
.switch-toggle .slider-round {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 34px;
}
.switch-toggle-checkbox:checked + .switch-toggle .slider-round {
    background-color: var(--primary) !important;
}
.switch-toggle .slider-round:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.switch-toggle-checkbox:checked + .switch-toggle .slider-round:before {
    transform: translateX(24px);
}
.text-color-selector input:checked + .text-color-btn {
    background: white;
    color: var(--primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.status-label-text {
    font-size: 0.85rem;
    font-weight: 700;
    min-width: 62px;
    text-align: right;
}
.status-label-active { display: none; color: #166534; }
.status-label-inactive { display: inline; color: #64748b; }
.switch-toggle-checkbox:checked ~ .status-label-active { display: inline; }
.switch-toggle-checkbox:checked ~ .status-label-inactive { display: none; }
</style>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Pengaturan tampilan <?= htmlspecialchars($meta['title']) ?> berhasil disimpan!</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon text-blue-600">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </div>
            <div>
                <h2>Tampilan Bagian <?= htmlspecialchars($meta['title']) ?></h2>
                <p><?= htmlspecialchars($meta['desc']) ?></p>
            </div>
        </div>
        
        <div class="section-card-body">
            <!-- ── Status & Text Color controls ── -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 20px;">
                <div class="form-group" style="margin: 0; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 14px 18px; border-radius: 12px; border: 1px solid var(--border);">
                    <div>
                        <label class="form-label" style="margin: 0; font-weight: 600;">Status Bagian</label>
                        <small style="color: var(--muted); display: block; margin-top: 2px;">Aktifkan atau nonaktifkan bagian ini pada undangan digital.</small>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active_checkbox" class="switch-toggle-checkbox" name="is_active" value="1" <?= ($cfg['is_active'] ?? '1') !== '0' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0; display: none;">
                        <label for="is_active_checkbox" class="switch-toggle">
                            <span class="slider-round"></span>
                        </label>
                        <span class="status-label-text status-label-active">Aktif</span>
                        <span class="status-label-text status-label-inactive">Nonaktif</span>
                    </div>
                </div>

                <div class="form-group" style="margin: 0; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 14px 18px; border-radius: 12px; border: 1px solid var(--border);">
                    <div>
                        <label class="form-label" style="margin: 0; font-weight: 600;">Skema Warna Teks</label>
                        <small style="color: var(--muted); display: block; margin-top: 2px;">Gunakan 'Terang' untuk background gelap, dan sebaliknya.</small>
                    </div>
                    <div class="text-color-selector" style="display: flex; background: #e2e8f0; padding: 3px; border-radius: 8px; gap: 4px; width: 180px; flex-shrink: 0;">
                        <label style="flex: 1; cursor: pointer; text-align: center; margin: 0; position: relative;">
                            <input type="radio" name="text_color" value="dark" <?= ($cfg['text_color'] ?? 'dark') === 'dark' ? 'checked' : '' ?> style="position: absolute; opacity: 0; width: 0; height: 0; display: none;">
                            <div class="text-color-btn" style="padding: 6px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; color: #64748b; transition: all 0.2s;">
                                Dark
                            </div>
                        </label>
                        <label style="flex: 1; cursor: pointer; text-align: center; margin: 0; position: relative;">
                            <input type="radio" name="text_color" value="light" <?= ($cfg['text_color'] ?? 'dark') === 'light' ? 'checked' : '' ?> style="position: absolute; opacity: 0; width: 0; height: 0; display: none;">
                            <div class="text-color-btn" style="padding: 6px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; color: #64748b; transition: all 0.2s;">
                                Light
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-grid g3">
                <div class="form-group">
                    <label class="form-label">Warna Latar Belakang</label>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div class="color-picker-wrapper">
                            <input type="color" id="bg_color_picker" 
                                   value="<?= $cfg['bg_color'] ?: '#ffffff' ?>"
                                   onchange="document.getElementById('bg_color').value = this.value">
                        </div>
                        <input type="text" name="bg_color" id="bg_color" class="form-control"
                               value="<?= htmlspecialchars($cfg['bg_color']) ?>" placeholder="Misal: #ffffff atau rgba(...)">
                    </div>
                </div>
                
                <div class="form-group" style="justify-content: center;">
                    <label class="form-label">
                        Opasitas Gambar Latar
                        <span id="opacity_val" style="float:right; font-weight:700; color:var(--primary); font-size: 1rem;"><?= htmlspecialchars($cfg['bg_opacity'] ?? $defaultOp) ?></span>
                    </label>
                    <input type="range" name="bg_opacity" class="form-range"
                           step="0.01" min="0" max="1"
                           value="<?= htmlspecialchars($cfg['bg_opacity'] ?? $defaultOp) ?>"
                           oninput="document.getElementById('opacity_val').textContent = this.value">
                </div>

                <div class="form-group">
                    <label class="form-label">Gambar Latar Belakang</label>
                    <?php if (!empty($cfg['bg_image'])): 
                        $bgSrc = $cfg['bg_image'];
                        if (preg_match('#(uploads/.+)$#', $bgSrc, $_m)) {
                            $bgSrc = rtrim($GLOBALS['base'] ?? '/', '/') . '/' . $_m[1];
                        }
                    ?>
                        <div style="display: flex; gap: 10px; align-items: flex-start; margin-bottom: 10px;">
                            <img src="<?= $bgSrc ?>" alt="Background" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);">
                            <label class="form-check" style="font-size: 0.8rem; color: #dc2626;">
                                <input type="checkbox" name="remove_bg" value="1">
                                Hapus gambar ini
                            </label>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="bg_image" class="form-control" accept="image/*">
                    <small style="color: var(--muted); font-size: 0.7rem; display: block; margin-top: 4px;">Upload gambar baru untuk mengganti latar belakang.</small>
                </div>
            </div>
        </div>
        
        <div class="section-card-footer">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> 
                Simpan Tampilan <?= htmlspecialchars($meta['title']) ?>
            </button>
        </div>
    </div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
