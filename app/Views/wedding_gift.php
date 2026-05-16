<?php
$activeTab = 'gift';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">✅ Informasi hadiah berhasil disimpan!</div>
<?php endif; ?>

<form method="POST">
<div class="form-grid g2">

    <!-- ── BANK 1 ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon">🏦</div>
            <div>
                <h2>Rekening 1</h2>
                <p>Rekening atas nama pengantin pertama</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-group">
                <label class="form-label">Nama Pemilik Rekening</label>
                <input type="text" name="bank1_owner" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank1_owner'] ?? '', ENT_QUOTES) ?>"
                       placeholder="SYIFA AULIA FADHILLAH">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank1_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank1_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Bank Mandiri / BCA / BNI / dst">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="bank1_acc" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank1_acc'] ?? '', ENT_QUOTES) ?>"
                       placeholder="1480020167113">
            </div>
        </div>
    </div>

    <!-- ── BANK 2 ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon">🏦</div>
            <div>
                <h2>Rekening 2</h2>
                <p>Rekening atas nama pengantin kedua</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-group">
                <label class="form-label">Nama Pemilik Rekening</label>
                <input type="text" name="bank2_owner" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank2_owner'] ?? '', ENT_QUOTES) ?>"
                       placeholder="ABDUL ROCHIM MUIZ">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank2_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank2_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Bank Mandiri / BCA / BNI / dst">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="bank2_acc" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank2_acc'] ?? '', ENT_QUOTES) ?>"
                       placeholder="1480021027993">
            </div>
        </div>
    </div>

</div>

<div class="section-card" style="margin-top:0;">
    <div class="section-card-footer">
        <button type="submit" class="btn btn-primary">💾 Simpan Info Hadiah</button>
        <span style="font-size:0.8rem; color:#94a3b8;">Kosongkan nomor rekening untuk menyembunyikan bagian hadiah</span>
    </div>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
