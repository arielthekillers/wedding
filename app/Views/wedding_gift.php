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
                       placeholder="Nama Pemilik Rekening 1">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank1_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank1_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: BCA / Mandiri / BRI / dst">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="bank1_acc" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank1_acc'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: 1234567890">
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
                       placeholder="Nama Pemilik Rekening 2">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank2_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank2_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: BCA / Mandiri / BRI / dst">
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="bank2_acc" class="form-control"
                       value="<?= htmlspecialchars($wedding['bank2_acc'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: 0987654321">
            </div>
        </div>
    </div>

</div>

<!-- ── SAWERIA INTEGRATION CARD ── -->
<div class="section-card" style="margin-top:20px;">
    <div class="section-card-header">
        <div class="icon" style="background:linear-gradient(135deg, #f59e0b, #d97706);"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-white"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path></svg></div>
        <div>
            <h2>Saweria Angpao Digital &amp; Live Feed</h2>
            <p>Aktifkan pembayaran kado digital berpesan via Saweria (QRIS, E-Wallet &amp; Transfer Bank)</p>
        </div>
    </div>
    <div class="section-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px;background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;margin-bottom:16px;">
            <div>
                <strong style="font-size:0.95rem;color:#9a3412;">Aktifkan Pembayaran Saweria</strong>
                <p style="margin:4px 0 0;font-size:0.8rem;color:#7c2d12;">Tamu dapat mengirim angpao disertai ucapan doa secara langsung dari undangan digital.</p>
            </div>
            <label class="form-check" style="margin:0;cursor:pointer;">
                <input type="checkbox" name="saweria_enabled" value="1" <?= !empty($wedding['saweria_enabled']) ? 'checked' : '' ?> style="width:20px;height:20px;">
            </label>
        </div>
        <div style="font-size:0.83rem;color:#475569;line-height:1.6;background:#fafafa;padding:14px;border-radius:10px;border:1px solid #e2e8f0;">
            <strong>💡 Cara Kerja Saweria di Palovite:</strong>
            <ul style="margin:6px 0 0 18px;padding:0;">
                <li>Sistem secara otomatis menambahkan tag khusus pengenal <code>[WED:<?= $wedding['id'] ?>]</code> pada link transaksi Saweria.</li>
                <li>Pemberi angpao tidak akan melihat tag ini, namun webhook server Palovite akan otomatis menyaring dan menampilkan hadiah &amp; ucapan live pada halaman undangan ini.</li>
            </ul>
        </div>
    </div>
</div>
<p style="font-size:0.8rem; color:#94a3b8; margin: 10px 0 15px; text-align: center;">💡 <em>Kosongkan nomor rekening untuk menyembunyikan bagian hadiah dari halaman undangan.</em></p>

<div class="section-card-footer">
    <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> <span>Simpan Data</span></button>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
