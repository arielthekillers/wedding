<?php
$activeTab = 'event';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">✅ Data acara berhasil disimpan!</div>
<?php endif; ?>

<form method="POST">
<div class="form-grid g2">

    <!-- ── AKAD CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon">🤝</div>
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
                           value="<?= htmlspecialchars($wedding['akad_date'] ?? '') ?>"
                           placeholder="Contoh: Sabtu, 10 Januari 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="text" name="akad_time" class="form-control"
                           value="<?= htmlspecialchars($wedding['akad_time'] ?? '') ?>"
                           placeholder="08.00 – 10.00 WIB">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Tempat / Venue</label>
                <textarea name="akad_place" class="form-control"
                          placeholder="Nama gedung / alamat lengkap"><?= htmlspecialchars($wedding['akad_place'] ?? '') ?></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Link Google Maps <small>(opsional)</small></label>
                <input type="text" name="akad_map_link" class="form-control"
                       value="<?= htmlspecialchars($wedding['akad_map_link'] ?? '') ?>"
                       placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

    <!-- ── RESEPSI CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon">🎉</div>
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
                           value="<?= htmlspecialchars($wedding['reception_date'] ?? '') ?>"
                           placeholder="Sabtu, 10 Januari 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="text" name="reception_time" class="form-control"
                           value="<?= htmlspecialchars($wedding['reception_time'] ?? '') ?>"
                           placeholder="11.00 – 14.00 WIB">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Tempat / Venue</label>
                <textarea name="reception_place" class="form-control"
                          placeholder="Nama gedung / alamat lengkap"><?= htmlspecialchars($wedding['reception_place'] ?? '') ?></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Link Google Maps <small>(opsional)</small></label>
                <input type="text" name="reception_map_link" class="form-control"
                       value="<?= htmlspecialchars($wedding['reception_map_link'] ?? '') ?>"
                       placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

</div>

<div class="section-card" style="margin-top:0;">
    <div class="section-card-footer">
        <button type="submit" class="btn btn-primary">💾 Simpan Data Acara</button>
    </div>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
