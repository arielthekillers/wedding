<?php
$activeTab = 'settings';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Pengaturan berhasil disimpan!</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form method="POST">
<div class="section-card">
    <div class="section-card-header">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></div>
        <div>
            <h2>Pengaturan Umum</h2>
            <p>Identitas undangan, URL, dan konfigurasi tampilan</p>
        </div>
    </div>
    <div class="section-card-body">

        <div class="form-grid g2">
            <div class="form-group">
                <label class="form-label">Judul Internal <small>(nama acara)</small></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($wedding['title'] ?? '', ENT_QUOTES) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL Slug <small>(huruf kecil, tanpa spasi)</small></label>
                <input type="text" name="slug" id="slug-input" class="form-control"
                       value="<?= htmlspecialchars($wedding['slug'] ?? '', ENT_QUOTES) ?>" required
                       pattern="[a-z0-9\-]+" title="Hanya huruf kecil, angka, dan tanda minus">
                <div id="slug-status" style="font-size: 0.75rem; min-height: 1em; margin-top: 4px; font-weight: 600;"></div>
            </div>
        </div>

        <div class="form-grid g3" style="margin-top:16px;">
            <div class="form-group">
                <label class="form-label">Tanggal Countdown</label>
                <input type="date" name="start_date" class="form-control"
                       value="<?= $wedding['start_date'] ?? '' ?>">
                <small style="color:#64748b; font-size:0.7rem; display:block; margin-top:4px;">Target hitung mundur di hero section.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Selesai (Opsional)</label>
                <input type="date" name="end_date" class="form-control"
                       value="<?= $wedding['end_date'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Tema Tampilan</label>
                <select name="theme_path" class="form-control">
                    <?php
                    $themes = array_filter(glob(__DIR__ . '/../../themes/*'), 'is_dir');
                    foreach ($themes as $t) {
                        $t_name  = basename($t);
                        $selected = ($wedding['theme_path'] ?? 'default') === $t_name ? 'selected' : '';
                        echo "<option value='$t_name' $selected>$t_name</option>";
                    }
                    ?>
                </select>
            </div>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="form-group">
                <label class="form-label">Pemilik (Client)</label>
                <select name="user_id" class="form-control">
                    <option value="">-- Tidak ada (Admin saja) --</option>
                    <?php foreach ($all_users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($wedding['user_id'] == $u['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['username']) ?> (<?= $u['role'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-group" style="margin-top:16px;">
            <label class="form-label">URL Musik Latar <small>(Tautan file MP3 atau YouTube)</small></label>
            <input type="url" name="music_url" class="form-control"
                   value="<?= htmlspecialchars($wedding['music_url'] ?? '', ENT_QUOTES) ?>" placeholder="https://example.com/music.mp3 atau https://youtube.com/watch?v=...">
        </div>


        <div style="margin-top:18px;">
            <label class="form-check">
                <input type="checkbox" name="is_enabled" value="1"
                       <?= ($wedding['is_enabled'] ?? 1) ? 'checked' : '' ?>>
                <span><strong>Undangan Aktif</strong> — Halaman undangan dapat diakses publik</span>
            </label>
        </div>

    </div>
    <div class="section-card-footer">
        <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1.5 inline-block"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> <span>Simpan Data</span></button>
        <span style="font-size:0.8rem; color:#94a3b8;">
            URL Undangan:
            <a href="<?= $base . ($wedding['slug'] ?? '') ?>" id="slug-preview-link" target="_blank"
               style="color:var(--primary); font-weight:600;">
                <?= $_SERVER['HTTP_HOST'] ?>/<span id="slug-preview-text"><?= htmlspecialchars($wedding['slug'] ?? '', ENT_QUOTES) ?></span>
            </a>
        </span>
    </div>
</div>
</form>

</div><!-- /.mgmt-content -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slugInput = document.getElementById('slug-input');
    const slugStatus = document.getElementById('slug-status');
    const previewLink = document.getElementById('slug-preview-link');
    const previewText = document.getElementById('slug-preview-text');
    const weddingId = <?= (int)($wedding['id'] ?? 0) ?>;
    const baseUrl = '<?= $base ?>';
    
    let debounceTimer;

    slugInput.addEventListener('input', function() {
        // Sanitize input
        let val = this.value.toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start
            .replace(/-+$/, '');            // Trim - from end
        
        // Don't auto-correct WHILE typing if it breaks user experience, 
        // but for pattern satisfaction we update the preview
        const rawVal = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '');
        
        // Update Preview
        previewText.textContent = rawVal;
        previewLink.href = baseUrl + rawVal;

        // Clear status if empty
        if (rawVal.length === 0) {
            slugStatus.textContent = '';
            return;
        }

        // Debounce API call
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetch(`${baseUrl}api/check-slug?slug=${rawVal}&exclude=${weddingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        slugStatus.textContent = '✓ ' + data.message;
                        slugStatus.style.color = '#10b981'; // Success green
                    } else {
                        slugStatus.textContent = '✗ ' + data.message;
                        slugStatus.style.color = '#ef4444'; // Error red
                    }
                })
                .catch(err => {
                    console.error('Error checking slug:', err);
                });
        }, 500);
    });
});
</script>
</body>
</html>
