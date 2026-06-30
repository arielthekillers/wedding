<?php
$activeTab = 'profile';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Profil pengantin berhasil disimpan!</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="form-grid g2">

    <!-- ── BRIDE CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></div>
            <div>
                <h2>Pengantin Wanita (Bride)</h2>
                <p>Data lengkap pengantin wanita</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label">Foto Pengantin Wanita</label>
                <?php if (!empty($wedding['bride_image'])): ?>
                    <div style="margin-bottom:10px;">
                        <img src="<?= $wedding['bride_image'] ?>" style="width:80px; height:80px; object-fit:cover; border-radius:10px; border:1px solid var(--border);">
                    </div>
                <?php endif; ?>
                <input type="file" name="bride_image" class="form-control" accept="image/*">
                <small style="color:#64748b; font-size:0.75rem; margin-top:4px; display:block;">Disarankan foto portrait (JPG/PNG/WEBP)</small>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="bride_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['bride_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: Siti Rahayu Dewi">
            </div>
            <div class="form-grid g2" style="margin-top:14px;">
                <div class="form-group">
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="bride_nickname" class="form-control"
                           value="<?= htmlspecialchars($wedding['bride_nickname'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Rara">
                </div>
                <div class="form-group">
                    <label class="form-label">Putri ke-</label>
                    <input type="text" name="bride_order" class="form-control"
                           value="<?= htmlspecialchars($wedding['bride_order'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Pertama / ke-2">
                </div>
            </div>
            <div class="section-divider" style="margin-top:18px;">Orang Tua</div>
            <div class="form-grid g2">
                <div class="form-group">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="bride_father" class="form-control"
                           value="<?= htmlspecialchars($wedding['bride_father'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Bapak Ahmad">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="bride_mother" class="form-control"
                           value="<?= htmlspecialchars($wedding['bride_mother'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Ibu Sarmini">
                </div>
            </div>
        </div>
    </div>

    <!-- ── GROOM CARD ── -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
            <div>
                <h2>Pengantin Pria (Groom)</h2>
                <p>Data lengkap pengantin pria</p>
            </div>
        </div>
        <div class="section-card-body">
            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label">Foto Pengantin Pria</label>
                <?php if (!empty($wedding['groom_image'])): ?>
                    <div style="margin-bottom:10px;">
                        <img src="<?= $wedding['groom_image'] ?>" style="width:80px; height:80px; object-fit:cover; border-radius:10px; border:1px solid var(--border);">
                    </div>
                <?php endif; ?>
                <input type="file" name="groom_image" class="form-control" accept="image/*">
                <small style="color:#64748b; font-size:0.75rem; margin-top:4px; display:block;">Disarankan foto portrait (JPG/PNG/WEBP)</small>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="groom_name" class="form-control"
                       value="<?= htmlspecialchars($wedding['groom_name'] ?? '', ENT_QUOTES) ?>"
                       placeholder="Contoh: Budi Santoso Putra">
            </div>
            <div class="form-grid g2" style="margin-top:14px;">
                <div class="form-group">
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="groom_nickname" class="form-control"
                           value="<?= htmlspecialchars($wedding['groom_nickname'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Budi">
                </div>
                <div class="form-group">
                    <label class="form-label">Putra ke-</label>
                    <input type="text" name="groom_order" class="form-control"
                           value="<?= htmlspecialchars($wedding['groom_order'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Pertama / ke-2">
                </div>
            </div>
            <div class="section-divider" style="margin-top:18px;">Orang Tua</div>
            <div class="form-grid g2">
                <div class="form-group">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="groom_father" class="form-control"
                           value="<?= htmlspecialchars($wedding['groom_father'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Bapak Sutrisno">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="groom_mother" class="form-control"
                           value="<?= htmlspecialchars($wedding['groom_mother'] ?? '', ENT_QUOTES) ?>"
                           placeholder="Ibu Wulandari">
                </div>
            </div>
        </div>
    </div>

</div>

<div class="section-card" style="margin-top:0;">
    <div class="section-card-footer">
        <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Data Pengantin</button>
    </div>
</div>
</form>

</div><!-- /.mgmt-content -->
</body>
</html>
