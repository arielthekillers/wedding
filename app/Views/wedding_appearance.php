<?php
$activeTab = 'appearance';
include __DIR__ . '/partials/wedding_nav.php';

$sectionsMeta = [
    'hero'       => ['title' => 'Hero / Sampul', 'desc' => 'Bagian paling atas undangan'],
    'countdown'  => ['title' => 'Hitung Mundur (Countdown)', 'desc' => 'Waktu menuju hari bahagia'],
    'couple'     => ['title' => 'Pasangan (Couple)', 'desc' => 'Profil pengantin pria dan wanita'],
    'love_story' => ['title' => 'Love Story', 'desc' => 'Perjalanan cinta pengantin'],
    'events'     => ['title' => 'Acara (Events)', 'desc' => 'Informasi akad dan resepsi'],
    'gallery'    => ['title' => 'Galeri', 'desc' => 'Kumpulan foto-foto momen'],
    'gifts'      => ['title' => 'Hadiah / Amplop', 'desc' => 'Informasi rekening dan alamat'],
    'guestbook'  => ['title' => 'Buku Tamu (RSVP)', 'desc' => 'Ucapan doa dan konfirmasi kehadiran'],
];
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
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.4);
    border: 2px solid white;
}
.form-range::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary);
    cursor: pointer;
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.4);
    border: 2px solid white;
}
</style>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">✅ Pengaturan tampilan berhasil disimpan!</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon">🎨</div>
            <div>
                <h2>Pengaturan Tampilan per Bagian</h2>
                <p>Sesuaikan warna, gambar latar, dan tingkat transparansi untuk setiap bagian (section) pada tema undangan.</p>
            </div>
        </div>
        <div class="section-card-body">
            
            <?php foreach ($sectionsMeta as $sec => $meta): 
                $defaultOp = ($sec === 'hero') ? '0.70' : '0.08';
                $cfg = $sectionSettings[$sec] ?? ['bg_color' => '', 'bg_image' => '', 'bg_opacity' => $defaultOp];
                // Handle edge case where older saves didn't have opacity and it defaults to empty string
                if (!isset($cfg['bg_opacity']) || $cfg['bg_opacity'] === '') $cfg['bg_opacity'] = $defaultOp;
            ?>
            <div style="border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; background: #fafafa;">
                <h3 style="margin-top: 0; font-size: 1.1rem; color: var(--primary); display: flex; justify-content: space-between; align-items: center;">
                    <?= $meta['title'] ?>
                    <span style="font-size: 0.8rem; color: var(--muted); font-weight: normal;"><?= $meta['desc'] ?></span>
                </h3>
                
                <div class="form-grid g3">
                    <div class="form-group">
                        <label class="form-label">Warna Latar Belakang</label>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div class="color-picker-wrapper">
                                <input type="color" id="<?= $sec ?>_bg_color_picker" 
                                       value="<?= $cfg['bg_color'] ?: '#ffffff' ?>"
                                       onchange="document.getElementById('<?= $sec ?>_bg_color').value = this.value">
                            </div>
                            <input type="text" name="<?= $sec ?>_bg_color" id="<?= $sec ?>_bg_color" class="form-control"
                                   value="<?= htmlspecialchars($cfg['bg_color']) ?>" placeholder="Misal: #ffffff atau rgba(...)">
                        </div>
                    </div>
                    
                    <div class="form-group" style="justify-content: center;">
                        <label class="form-label">
                            Opasitas Gambar Latar
                            <span id="<?= $sec ?>_opacity_val" style="float:right; font-weight:700; color:var(--primary); font-size: 1rem;"><?= htmlspecialchars($cfg['bg_opacity'] ?? '0.08') ?></span>
                        </label>
                        <input type="range" name="<?= $sec ?>_bg_opacity" class="form-range"
                               step="0.01" min="0" max="1"
                               value="<?= htmlspecialchars($cfg['bg_opacity'] ?? '0.08') ?>"
                               oninput="document.getElementById('<?= $sec ?>_opacity_val').textContent = this.value">
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
                                    <input type="checkbox" name="<?= $sec ?>_remove_bg" value="1">
                                    Hapus gambar ini
                                </label>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="<?= $sec ?>_bg_image" class="form-control" accept="image/*">
                        <small style="color: var(--muted); font-size: 0.7rem; display: block; margin-top: 4px;">Upload gambar baru untuk mengganti latar belakang.</small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        <div class="section-card-footer">
            <button type="submit" class="btn btn-primary">💾 Simpan Tampilan</button>
        </div>
    </div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
