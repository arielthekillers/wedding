<?php
$activeTab = 'event';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Data acara berhasil disimpan!</div>
<?php endif; ?>

<form method="POST">
<div class="form-grid g2">

    <!-- ── AKAD CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><circle cx="12" cy="12" r="8"></circle><circle cx="12" cy="12" r="4"></circle></svg></div>
            <div>
                <h2>Akad Nikah</h2>
                <p>Tanggal, waktu dan tempat akad nikah</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-grid g2">
                <div class="form-group">
                    <label class="form-label">Tampilan Hari / Tanggal</label>
                    <input type="text" name="akad_date" class="form-control"
                           value="<?= htmlspecialchars($wedding['akad_date'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Contoh: Sabtu, 10 Januari 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="text" name="akad_time" class="form-control"
                           value="<?= htmlspecialchars($wedding['akad_time'] ?? '', ENT_QUOTES) ?>"
                           placeholder="08.00 – 10.00 WIB">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Tempat / Venue</label>
                <textarea name="akad_place" class="form-control"
                          placeholder="Nama gedung / alamat lengkap"><?= htmlspecialchars($wedding['akad_place'] ?? '', ENT_QUOTES) ?></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Link Google Maps <small>(opsional)</small></label>
                <input type="text" name="akad_map_link" class="form-control"
                       value="<?= htmlspecialchars($wedding['akad_map_link'] ?? '', ENT_QUOTES) ?>"
                       placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

    <!-- ── RESEPSI CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
            <div>
                <h2>Resepsi Pernikahan</h2>
                <p>Tanggal, waktu, tempat dan lokasi maps</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-grid g2">
                <div class="form-group">
                    <label class="form-label">Hari / Tanggal</label>
                    <input type="text" name="reception_date" class="form-control"
                           value="<?= htmlspecialchars($wedding['reception_date'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Sabtu, 10 Januari 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="text" name="reception_time" class="form-control"
                           value="<?= htmlspecialchars($wedding['reception_time'] ?? '', ENT_QUOTES) ?>"
                           placeholder="11.00 – 14.00 WIB">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Tempat / Venue</label>
                <textarea name="reception_place" class="form-control"
                          placeholder="Nama gedung / alamat lengkap"><?= htmlspecialchars($wedding['reception_place'] ?? '', ENT_QUOTES) ?></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Link Google Maps <small>(opsional)</small></label>
                <input type="text" name="reception_map_link" class="form-control"
                       value="<?= htmlspecialchars($wedding['reception_map_link'] ?? '', ENT_QUOTES) ?>"
                       placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

</div>

<div class="section-card-footer">
    <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> <span>Simpan Data</span></button>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
