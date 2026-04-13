<?php
$activeTab = 'gallery';
include __DIR__ . '/partials/wedding_nav.php';
?>

<?php if (isset($_GET['uploaded'])): ?>
<div class="alert alert-success">✅ <?= (int)$_GET['uploaded'] ?> foto berhasil diupload &amp; diproses!</div>
<?php elseif (isset($_GET['deleted'])): ?>
<div class="alert alert-info">🗑️ Foto berhasil dihapus.</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="alert alert-error">⚠️ Gagal mengupload foto. Pastikan format file didukung (JPG, PNG, WebP, GIF).</div>
<?php endif; ?>

<!-- ── UPLOAD CARD ── -->
<input type="file" name="photos[]" id="formPhotoInput" style="display:none" multiple accept="image/*">

<div class="section-card">
    <div class="section-card-header">
        <div class="icon">⬆️</div>
        <div>
            <h2>Upload Foto</h2>
            <p>Seret &amp; lepas atau klik pilih foto. Otomatis dikompresi &amp; dibuat thumbnail.</p>
        </div>
    </div>
    <div class="section-card-body">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <input type="file" name="photos[]" id="formPhotoInput2" style="display:none" multiple accept="image/*">

            <div id="dropZone"
                 style="border:2.5px dashed #c4b5fd;border-radius:14px;padding:36px 20px;text-align:center;
                        background:#faf8ff;transition:background 0.2s,border-color 0.2s;cursor:pointer;"
                 ondragenter="this.style.background='#ede9fe';this.style.borderColor='var(--primary)'"
                 ondragleave="this.style.background='#faf8ff';this.style.borderColor='#c4b5fd'"
                 ondragover="event.preventDefault()"
                 ondrop="handleDrop(event)">
                <div style="font-size:2.5rem;pointer-events:none;">📸</div>
                <p style="margin:8px 0 16px;color:#94a3b8;font-size:0.88rem;pointer-events:none;">
                    Seret foto ke sini <strong>atau</strong>
                </p>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('formPhotoInput2').click()">
                    📂 Pilih Foto
                </button>
                <div id="previewBar" style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:14px;"></div>
                <p style="margin:10px 0 0;font-size:0.75rem;color:#94a3b8;">
                    Format: JPG, PNG, WebP, GIF &nbsp;•&nbsp; Multi-select didukung &nbsp;•&nbsp; Max 1200px, kualitas 85%
                </p>
            </div>

            <div id="fileInfo" style="display:none;text-align:center;margin-top:16px;font-size:0.88rem;color:#64748b;">
                <span id="fileCount"></span> foto dipilih &mdash;
                <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">⬆️ Upload</button>
                <button type="button" class="btn btn-ghost btn-sm" onclick="clearFiles()">✕ Batal</button>
            </div>

            <div id="uploadProgress" style="display:none;text-align:center;margin-top:16px;">
                <progress id="progressBar" value="0" max="100" style="width:100%;max-width:340px;height:8px;border-radius:8px;"></progress>
                <p style="font-size:0.82rem;color:#94a3b8;margin:6px 0 0;">Sedang mengupload &amp; memproses...</p>
            </div>
        </form>
    </div>
</div>

<!-- ── GALLERY GRID CARD ── -->
<div class="section-card">
    <div class="section-card-header" style="justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="icon">🖼️</div>
            <div>
                <h2>Foto Tersimpan</h2>
                <p>Hover foto untuk lihat atau hapus</p>
            </div>
        </div>
        <span style="background:#ede9fe;color:var(--primary);font-weight:700;font-size:0.82rem;padding:5px 14px;border-radius:20px;">
            <?= count($galleryItems) ?> foto
        </span>
    </div>
    <div class="section-card-body">

    <?php if (empty($galleryItems)): ?>
    <div style="text-align:center;padding:40px 20px;">
        <div style="font-size:3rem;margin-bottom:12px;">🖼️</div>
        <p style="color:#94a3b8;margin:0;">Belum ada foto. Upload menggunakan area di atas.</p>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;">
        <?php foreach ($galleryItems as $i => $img):
            $thumb = !empty($img['thumb_url']) ? $img['thumb_url'] : $img['image_url'];
            $full  = $img['image_url'];
            $filePath = rtrim($_SERVER['DOCUMENT_ROOT'],'/\\') . str_replace('/', DIRECTORY_SEPARATOR, $full);
            $sizeKb   = file_exists($filePath) ? round(filesize($filePath)/1024) . ' KB' : '';
        ?>
        <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1/1;background:#f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:transform 0.2s;"
             onmouseover="this.style.transform='translateY(-3px)'; this.querySelector('.gal-overlay').style.opacity='1'; this.querySelector('.gal-overlay').style.pointerEvents='auto';"
             onmouseout="this.style.transform='none'; this.querySelector('.gal-overlay').style.opacity='0'; this.querySelector('.gal-overlay').style.pointerEvents='none';">
            <img src="<?= htmlspecialchars($thumb) ?>" loading="lazy"
                 alt="Foto <?= $i+1 ?>"
                 style="width:100%;height:100%;object-fit:cover;display:block;transition:filter 0.25s;cursor:pointer;"
                 onclick="openLightbox(<?= $i ?>)">
            <span style="position:absolute;top:7px;left:7px;background:rgba(0,0,0,0.45);color:white;font-size:0.68rem;font-weight:700;padding:2px 7px;border-radius:20px;pointer-events:none;">
                #<?= $i+1 ?><?= $sizeKb ? ' · '.$sizeKb : '' ?>
            </span>
            <div class="gal-overlay" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:7px;opacity:0;pointer-events:none;transition:opacity 0.22s;background:rgba(0,0,0,0.35);">
                <button onclick="openLightbox(<?= $i ?>)"
                        style="background:rgba(255,255,255,0.92);color:#1e293b;border:none;border-radius:8px;padding:6px 13px;font-weight:700;font-size:0.78rem;cursor:pointer;">
                    🔍 Lihat
                </button>
                <button onclick="confirmDelete(<?= $img['id'] ?>, <?= $i+1 ?>, <?= htmlspecialchars(json_encode($thumb)) ?>)"
                        style="background:rgba(220,38,38,0.9);color:white;border:none;border-radius:8px;padding:6px 13px;font-weight:700;font-size:0.78rem;cursor:pointer;">
                    🗑️ Hapus
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    </div>
</div>

<!-- ── LIGHTBOX ── -->
<div id="lightbox" onclick="closeLightbox()"
     style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,0.93);align-items:center;justify-content:center;flex-direction:column;gap:14px;padding:20px;">
    <button onclick="event.stopPropagation();closeLightbox()"
            style="position:fixed;top:16px;right:16px;background:rgba(255,255,255,0.15);border:none;color:white;width:38px;height:38px;border-radius:50%;font-size:1rem;cursor:pointer;">✕</button>
    <button onclick="event.stopPropagation();shiftLb(-1)"
            style="position:fixed;left:16px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.15);border:none;color:white;width:50px;height:50px;border-radius:50%;font-size:1.6rem;cursor:pointer;">‹</button>
    <img id="lbImg" src="" alt="" style="max-width:90vw;max-height:82vh;border-radius:12px;object-fit:contain;box-shadow:0 20px 60px rgba(0,0,0,0.5);" onclick="event.stopPropagation()">
    <button onclick="event.stopPropagation();shiftLb(1)"
            style="position:fixed;right:16px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.15);border:none;color:white;width:50px;height:50px;border-radius:50%;font-size:1.6rem;cursor:pointer;">›</button>
    <p id="lbCounter" style="color:rgba(255,255,255,0.5);font-size:0.82rem;margin:0;"></p>
</div>

<!-- ── DELETE MODAL ── -->
<div id="delModal" onclick="if(event.target===this)closeDelModal()"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:20px;padding:32px 36px;max-width:400px;width:100%;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.2);animation:popIn 0.22s ease;">
        <div style="font-size:2.5rem;margin-bottom:10px;">🗑️</div>
        <img id="delPrevImg" src="" style="width:90px;height:90px;border-radius:10px;object-fit:cover;border:3px solid #fee2e2;margin:0 auto 16px;display:block;">
        <h3 style="margin:0 0 8px;font-size:1.1rem;">Hapus Foto Ini?</h3>
        <p style="margin:0 0 24px;color:#94a3b8;font-size:0.85rem;">File akan dihapus permanen dari server beserta thumbnail. <strong>Tidak dapat dibatalkan.</strong></p>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeDelModal()" class="btn btn-ghost">✕ Batal</button>
            <button onclick="executeDelete()" class="btn" style="background:#dc2626;color:white;">🗑️ Ya, Hapus</button>
        </div>
    </div>
</div>

<style>
@keyframes popIn { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
</style>

<script>
const BASE_URL = '<?= rtrim($GLOBALS['base'], '/') ?>/admin/detail/<?= $id ?>/gallery';
const lbImages = [<?php echo implode(',', array_map(fn($g) => json_encode($g['image_url']), $galleryItems)); ?>];
let lbIdx = 0, pendingDelId = null;

function openLightbox(i) {
    lbIdx = i;
    document.getElementById('lbImg').src = lbImages[i];
    document.getElementById('lbCounter').textContent = (i+1) + ' / ' + lbImages.length;
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex'; document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
function shiftLb(d) {
    if (!lbImages.length) return;
    lbIdx = (lbIdx + d + lbImages.length) % lbImages.length;
    document.getElementById('lbImg').src = lbImages[lbIdx];
    document.getElementById('lbCounter').textContent = (lbIdx+1) + ' / ' + lbImages.length;
}
document.addEventListener('keydown', e => {
    if (document.getElementById('lightbox').style.display==='flex') {
        if (e.key==='Escape') closeLightbox();
        if (e.key==='ArrowLeft') shiftLb(-1);
        if (e.key==='ArrowRight') shiftLb(1);
    }
});

function confirmDelete(id, num, thumb) {
    pendingDelId = id;
    document.getElementById('delPrewImg') && (document.getElementById('delPrewImg').src = thumb);
    document.getElementById('delPrevImg').src = thumb;
    const m = document.getElementById('delModal');
    m.style.display = 'flex'; document.body.style.overflow = 'hidden';
}
function closeDelModal() {
    pendingDelId = null;
    document.getElementById('delModal').style.display = 'none';
    document.body.style.overflow = '';
}
function executeDelete() {
    if (pendingDelId) window.location.href = BASE_URL + '?action=delete&img_id=' + pendingDelId;
}

// Upload drag & drop
document.getElementById('formPhotoInput2').addEventListener('change', function() {
    handleFiles(this.files);
});
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.background = '#faf8ff';
    document.getElementById('dropZone').style.borderColor = '#c4b5fd';
    const files = e.dataTransfer.files;
    const dt = new DataTransfer();
    Array.from(files).forEach(f => dt.items.add(f));
    document.getElementById('formPhotoInput2').files = dt.files;
    handleFiles(files);
}
function handleFiles(files) {
    if (!files || !files.length) return;
    const bar = document.getElementById('previewBar');
    bar.innerHTML = '';
    Array.from(files).slice(0, 14).forEach(f => {
        if (!f.type.startsWith('image/')) return;
        const r = new FileReader();
        r.onload = ev => {
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.style.cssText = 'width:70px;height:70px;border-radius:8px;object-fit:cover;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.1);';
            bar.appendChild(img);
        };
        r.readAsDataURL(f);
    });
    document.getElementById('fileCount').textContent = files.length;
    document.getElementById('fileInfo').style.display = 'block';
}
function clearFiles() {
    document.getElementById('formPhotoInput2').value = '';
    document.getElementById('previewBar').innerHTML = '';
    document.getElementById('fileInfo').style.display = 'none';
}
document.getElementById('uploadForm').addEventListener('submit', () => {
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').textContent = '⏳ Mengupload...';
    let v = 0;
    setInterval(() => { v = Math.min(v + Math.random()*8, 90); document.getElementById('progressBar').value = v; }, 200);
});
</script>

</div><!-- /.mgmt-content -->
</body>
</html>
