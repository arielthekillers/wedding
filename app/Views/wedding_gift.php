<?php
$activeTab = 'gift';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Informasi hadiah berhasil disimpan!</div>
<?php endif; ?>

<form method="POST">
<div class="form-grid g2">

    <!-- ── BANK 1 ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
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
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
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
        <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Info Hadiah</button>
        <span style="font-size:0.8rem; color:#94a3b8;">Kosongkan nomor rekening untuk menyembunyikan bagian hadiah</span>
    </div>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
