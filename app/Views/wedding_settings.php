<?php
$activeTab = 'settings';
include __DIR__ . '/partials/wedding_nav.php';
?>
<!-- Cropper.js for Interactive Image Cropping -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Pengaturan berhasil disimpan!</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
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

        <!-- ── FOTO SHARE / WHATSAPP PREVIEW (OPEN GRAPH - PORTRAIT) ── -->
        <?php
        $secSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
        $heroBg = $secSettings['hero']['bg_image'] ?? '';
        $currentOgPreview = !empty($wedding['og_image']) ? $wedding['og_image'] : (!empty($heroBg) ? $heroBg : '');
        ?>
        <input type="hidden" name="og_image_data" id="ogImageData" value="">
        <input type="file" id="fieldOgImage" accept="image/*" style="display:none;" onchange="handleOgFileSelect(this)">

        <div class="form-group" style="margin-top:20px; padding:18px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; flex-wrap:wrap; gap:10px;">
                <div>
                    <label class="form-label" style="margin-bottom:2px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        Foto Share WhatsApp &amp; Sosial Media (Portrait)
                    </label>
                    <p style="margin:0; font-size:0.78rem; color:#64748b;">
                        Foto portrait yang muncul saat tautan undangan dibagikan di WhatsApp. Anda dapat menggeser &amp; memotong (crop) foto sesuai keinginan.
                    </p>
                </div>
                <div id="ogStatusBadgeContainer">
                    <?php if (!empty($wedding['og_image'])): ?>
                        <span id="ogStatusBadge" style="background:#dcfce7; color:#166534; font-size:0.72rem; font-weight:700; padding:4px 12px; border-radius:20px; display:inline-block;">
                            ✓ Foto Khusus Aktif
                        </span>
                    <?php else: ?>
                        <span id="ogStatusBadge" style="background:#f1f5f9; color:#475569; font-size:0.72rem; font-weight:600; padding:4px 12px; border-radius:20px; display:inline-block;">
                            <?= !empty($heroBg) ? 'Default: Menggunakan Foto Sampul Depan' : 'Default: Belum Ada Foto' ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Preview Box (Portrait) & Action Buttons -->
            <div style="display:flex; gap:18px; align-items:center; margin-bottom:8px; flex-wrap:wrap;">
                <!-- Portrait Preview Frame (Ratio 4:5) -->
                <div style="width:140px; height:175px; border-radius:12px; overflow:hidden; border:2px dashed #cbd5e1; background:#0f172a; display:flex; align-items:center; justify-content:center; position:relative; box-shadow:0 6px 16px rgba(0,0,0,0.1); flex-shrink:0;">
                    <?php if (!empty($currentOgPreview)): ?>
                        <img id="ogPreviewImg" src="<?= htmlspecialchars($currentOgPreview) ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div id="ogPlaceholder" style="color:#94a3b8; font-size:0.75rem; text-align:center; padding:8px;">
                            Belum ada foto<br><span style="font-size:0.65rem;">(Portrait 4:5)</span>
                        </div>
                        <img id="ogPreviewImg" src="" style="width:100%; height:100%; object-fit:cover; display:none;">
                    <?php endif; ?>
                </div>

                <div style="flex:1; min-width:240px;">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('fieldOgImage').click()" style="display:inline-flex; align-items:center; gap:6px; padding:7px 16px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                            Pilih &amp; Potong Foto
                        </button>
                        
                        <?php if (!empty($currentOgPreview)): ?>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="recropCurrentPhoto()" style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; border:1px solid #cbd5e1;">
                            <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><path d="M8 12h8"></path><path d="M12 8v8"></path></svg>
                            Sesuaikan Ulang (Crop)
                        </button>
                        <?php endif; ?>
                    </div>

                    <small style="color:#64748b; font-size:0.75rem; line-height:1.45; display:block;">
                        💡 Popup crop akan muncul otomatis saat Anda memilih foto. Anda bisa <strong>menggeser posisi</strong> dan <strong>mengatur area crop</strong> sesuai keinginan sebelum disimpan.
                    </small>

                    <?php if (!empty($wedding['og_image'])): ?>
                    <div style="margin-top:10px;">
                        <label class="form-check" style="font-size:0.8rem; color:#dc2626;">
                            <input type="checkbox" name="delete_og_image" value="1" onchange="if(this.checked)document.getElementById('ogImageData').value='';">
                            <span>Hapus foto khusus ini &amp; gunakan kembali foto sampul depan</span>
                        </label>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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

<!-- ── POPUP MODAL CROPPER ── -->
<div id="cropModal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(15,23,42,0.8); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border-radius:20px; width:100%; max-width:680px; max-height:92vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.35); animation:popIn 0.22s ease;">
        
        <!-- Header -->
        <div style="padding:16px 22px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#ffffff;">
            <div>
                <h3 style="margin:0; font-size:1.05rem; color:#1e293b; font-weight:700;">Sesuaikan &amp; Potong Foto (Portrait)</h3>
                <p style="margin:2px 0 0; font-size:0.76rem; color:#64748b;">Geser foto ke posisi yang Anda inginkan, zoom atau ubah rasio potong</p>
            </div>
            <button type="button" onclick="closeCropModal()" style="background:#f1f5f9; border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; font-size:1rem; color:#64748b; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>

        <!-- Cropper Workspace Area -->
        <div style="padding:0; background:#0f172a; flex:1; min-height:300px; max-height:54vh; display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <div style="width:100%; height:100%; max-height:50vh; display:flex; align-items:center; justify-content:center;">
                <img id="imageToCrop" src="" style="display:block; max-width:100%; max-height:50vh;">
            </div>
        </div>

        <!-- Toolbar: Aspect Ratio & Controls -->
        <div style="padding:12px 18px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <!-- Ratio selection -->
            <div style="display:flex; align-items:center; gap:5px; flex-wrap:wrap;">
                <span style="font-size:0.75rem; font-weight:700; color:#475569; margin-right:2px;">Rasio:</span>
                <button type="button" class="crop-ratio-btn active-ratio" onclick="setCropRatio(4/5, this)" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; background:var(--primary, #e11d48); color:white; border:none; cursor:pointer; font-weight:700;">
                    Portrait 4:5
                </button>
                <button type="button" class="crop-ratio-btn" onclick="setCropRatio(3/4, this)" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; background:#e2e8f0; color:#334155; border:none; cursor:pointer; font-weight:600;">
                    Portrait 3:4
                </button>
                <button type="button" class="crop-ratio-btn" onclick="setCropRatio(1, this)" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; background:#e2e8f0; color:#334155; border:none; cursor:pointer; font-weight:600;">
                    Persegi 1:1
                </button>
                <button type="button" class="crop-ratio-btn" onclick="setCropRatio(NaN, this)" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; background:#e2e8f0; color:#334155; border:none; cursor:pointer; font-weight:600;">
                    Bebas
                </button>
            </div>

            <!-- Tool actions: Zoom & Rotate -->
            <div style="display:flex; align-items:center; gap:5px;">
                <button type="button" onclick="cropZoom(0.1)" title="Perbesar (Zoom In)" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; width:30px; height:30px; cursor:pointer; font-weight:bold; font-size:0.9rem;">＋</button>
                <button type="button" onclick="cropZoom(-0.1)" title="Perkecil (Zoom Out)" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; width:30px; height:30px; cursor:pointer; font-weight:bold; font-size:0.9rem;">－</button>
                <button type="button" onclick="cropRotate(-90)" title="Putar Kiri 90°" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; width:30px; height:30px; cursor:pointer; font-size:0.85rem;">↺</button>
                <button type="button" onclick="cropRotate(90)" title="Putar Kanan 90°" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; width:30px; height:30px; cursor:pointer; font-size:0.85rem;">↻</button>
                <button type="button" onclick="cropReset()" title="Reset" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; padding:0 8px; height:30px; font-size:0.75rem; cursor:pointer; color:#64748b;">Reset</button>
            </div>
        </div>

        <!-- Footer -->
        <div style="padding:12px 20px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px; background:#ffffff;">
            <button type="button" onclick="closeCropModal()" class="btn btn-ghost" style="padding:8px 16px;">✕ Batal</button>
            <button type="button" onclick="applyCrop()" class="btn btn-primary" style="padding:8px 20px; display:inline-flex; align-items:center; gap:6px;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Terapkan &amp; Gunakan Foto
            </button>
        </div>
    </div>
</div>

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

// ── CROPPER.JS MODAL LOGIC ──
let cropperInstance = null;

function handleOgFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            openCropModal(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function recropCurrentPhoto() {
    const previewImg = document.getElementById('ogPreviewImg');
    if (previewImg && previewImg.src) {
        openCropModal(previewImg.src);
    }
}

function openCropModal(imageSrc) {
    const modal = document.getElementById('cropModal');
    const imageToCrop = document.getElementById('imageToCrop');
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }

    imageToCrop.onload = function() {
        cropperInstance = new Cropper(imageToCrop, {
            aspectRatio: 4 / 5, // Default Portrait 4:5
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.95,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    };

    imageToCrop.src = imageSrc;
    if (imageToCrop.complete) {
        imageToCrop.onload();
    }
}

function closeCropModal() {
    const modal = document.getElementById('cropModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }
    const fileInput = document.getElementById('fieldOgImage');
    if (fileInput) fileInput.value = '';
}

function setCropRatio(ratio, btn) {
    if (!cropperInstance) return;
    cropperInstance.setAspectRatio(ratio);
    document.querySelectorAll('.crop-ratio-btn').forEach(b => {
        b.style.background = '#e2e8f0';
        b.style.color = '#334155';
        b.style.fontWeight = '600';
    });
    if (btn) {
        btn.style.background = 'var(--primary, #e11d48)';
        btn.style.color = '#ffffff';
        btn.style.fontWeight = '700';
    }
}

function cropZoom(delta) {
    if (cropperInstance) cropperInstance.zoom(delta);
}

function cropRotate(deg) {
    if (cropperInstance) cropperInstance.rotate(deg);
}

function cropReset() {
    if (cropperInstance) cropperInstance.reset();
}

function applyCrop() {
    if (!cropperInstance) return;

    // Render cropped canvas (portrait with high resolution)
    const canvas = cropperInstance.getCroppedCanvas({
        maxWidth: 1200,
        maxHeight: 1500,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });

    const croppedBase64 = canvas.toDataURL('image/jpeg', 0.85);

    // Save to hidden input
    document.getElementById('ogImageData').value = croppedBase64;

    // Update preview frame
    const previewImg = document.getElementById('ogPreviewImg');
    if (previewImg) {
        previewImg.src = croppedBase64;
        previewImg.style.display = 'block';
    }
    const ph = document.getElementById('ogPlaceholder');
    if (ph) ph.style.display = 'none';

    // Show indicator badge
    const badge = document.getElementById('ogStatusBadge');
    if (badge) {
        badge.textContent = '✓ Foto Baru Dipotong (Klik "Simpan Data" di bawah)';
        badge.style.background = '#fef3c7';
        badge.style.color = '#b45309';
    }

    closeCropModal();
}
</script>
</body>
</html>
