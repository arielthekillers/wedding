<?php
$activeTab = 'gallery';
include __DIR__ . '/partials/wedding_nav.php';
?>

<style>
.gal-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
}
.gal-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 7px;
    opacity: 0; pointer-events: none; transition: opacity 0.22s; background: rgba(0,0,0,0.45);
}
.gal-btn {
    border: none; border-radius: 8px; padding: 6px 13px; font-weight: 700; font-size: 0.78rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: transform 0.15s;
}
.gal-btn:active { transform: scale(0.95); }
.gal-btn-view { background: rgba(255,255,255,0.92); color: #1e293b; }
.gal-btn-del { background: rgba(220,38,38,0.92); color: white; }

@media (max-width: 640px) {
    .gal-grid-container {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 6px !important;
    }
    /* Mobile Touch Optimized: Ikon mini melayang transparan langsung di pojok foto */
    .gal-overlay {
        opacity: 1 !important;
        pointer-events: auto !important;
        background: transparent !important;
        flex-direction: row !important;
        justify-content: flex-end !important;
        align-items: flex-end !important;
        padding: 5px !important;
        gap: 4px !important;
    }
    .gal-btn {
        padding: 6px !important;
        border-radius: 50% !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }
    .gal-btn span { display: none !important; }
    .gal-btn svg { margin: 0 !important; width: 13px !important; height: 13px !important; }
}
</style>

<?php if (isset($_GET['uploaded'])): ?>
<div class="alert alert-success"><?= (int)$_GET['uploaded'] ?> foto berhasil diupload &amp; diproses!</div>
<?php elseif (isset($_GET['deleted'])): ?>
<div class="alert alert-info">Foto berhasil dihapus.</div>
<?php elseif (isset($_GET['deleted_all'])): ?>
<div class="alert alert-info">Semua foto galeri berhasil dihapus.</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="alert alert-error">Gagal mengupload foto. Pastikan format file didukung (JPG, PNG, WebP, GIF).</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-card-header">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg></div>
        <div>
            <h2>Upload Foto</h2>
            <p>Kelola dan tambahkan foto ke dalam galeri undangan</p>
        </div>
    </div>
    <div class="section-card-body">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <input type="file" name="photos[]" id="formPhotoInput2" style="display:none" multiple accept="image/*">

            <div id="dropZone"
                 style="border:2.5px dashed #fed7aa;border-radius:14px;padding:36px 20px;text-align:center;
                        background:#fff7ed;transition:background 0.2s,border-color 0.2s;cursor:pointer;"
                 ondragenter="this.style.background='#ffedd5';this.style.borderColor='var(--primary)'"
                 ondragleave="this.style.background='#fff7ed';this.style.borderColor='#fed7aa'"
                 ondragover="event.preventDefault()"
                 ondrop="handleDrop(event)"
                 onclick="document.getElementById('formPhotoInput2').click()">
                <div class="flex justify-center text-rose-500 mb-2 pointer-events-none"><svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-9 h-9"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
                <p style="margin:8px 0 14px;color:#475569;font-size:0.88rem;font-weight:500;pointer-events:none;">
                    Klik di sini atau seret foto untuk mengunggah
                </p>
                <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation();document.getElementById('formPhotoInput2').click()"><svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-1 inline-block"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg> Pilih Foto</button>
                <div id="previewBar" style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:14px;"></div>
                <p style="margin:10px 0 0;font-size:0.75rem;color:#94a3b8;">
                    Format: JPG, PNG, WebP, GIF &nbsp;•&nbsp; Multi-select didukung &nbsp;•&nbsp; Max 1200px, kualitas 85%
                </p>
            </div>

            <div id="fileInfo" style="display:none;text-align:center;margin-top:16px;font-size:0.88rem;color:#64748b;">
                <span id="fileCount"></span> foto dipilih &mdash;
                <button type="submit" class="btn btn-primary btn-sm" id="submitBtn"><svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-1 inline-block"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg> Upload</button>
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
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-rose-500"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
            <div>
                <h2>Foto Tersimpan</h2>
                <p>Daftar koleksi foto galeri pernikahan</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <?php if (!empty($galleryItems)): ?>
            <button type="button" onclick="confirmDeleteAll()" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;border-radius:8px;padding:5px 12px;font-size:0.75rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;cursor:pointer;">
                <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Semua
            </button>
            <?php endif; ?>
            <span style="background:#ffe4e6;color:#e11d48;font-weight:700;font-size:0.75rem;padding:4px 12px;border-radius:50px;white-space:nowrap;flex-shrink:0;">
                <?= count($galleryItems) ?> Foto
            </span>
        </div>
    </div>
    <div class="section-card-body">

    <?php if (empty($galleryItems)): ?>
    <div style="text-align:center;padding:40px 20px;">
        <div class="flex justify-center text-slate-400 mb-3"><svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
        <p style="color:#94a3b8;margin:0;">Belum ada foto. Upload menggunakan area di atas.</p>
    </div>
    <?php else: ?>
    <div class="gal-grid-container">
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
            <div class="gal-overlay">
                <button onclick="openLightbox(<?= $i ?>)" class="gal-btn gal-btn-view" title="Lihat Foto">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span>Lihat</span>
                </button>
                <button onclick="confirmDelete(<?= $img['id'] ?>, <?= $i+1 ?>, <?= htmlspecialchars(json_encode($thumb)) ?>)" class="gal-btn gal-btn-del" title="Hapus Foto">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>Hapus</span>
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
    <img id="lbImg" src="" alt="" style="max-width:90vw;max-height:75vh;border-radius:12px;object-fit:contain;box-shadow:0 20px 60px rgba(0,0,0,0.5);" onclick="event.stopPropagation()">
    <button onclick="event.stopPropagation();shiftLb(1)"
            style="position:fixed;right:16px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.15);border:none;color:white;width:50px;height:50px;border-radius:50%;font-size:1.6rem;cursor:pointer;">›</button>
    <div style="display:flex;align-items:center;gap:16px;" onclick="event.stopPropagation()">
        <p id="lbCounter" style="color:rgba(255,255,255,0.7);font-size:0.85rem;margin:0;"></p>
        <button id="lbDelBtn" onclick="deleteFromLb()" style="background:#dc2626;color:white;border:none;border-radius:8px;padding:6px 14px;font-size:0.8rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
            <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            Hapus Foto
        </button>
    </div>
</div>

<!-- ── DELETE MODAL ── -->
<div id="delModal" onclick="if(event.target===this)closeDelModal()"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:20px;padding:32px 36px;max-width:400px;width:100%;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.2);animation:popIn 0.22s ease;">
        <div class="flex justify-center text-rose-500 mb-3"><svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></div>
        <img id="delPrevImg" src="" onerror="this.style.display='none'" style="width:90px;height:90px;border-radius:10px;object-fit:cover;border:3px solid #fee2e2;margin:0 auto 16px;display:block;">
        <h3 style="margin:0 0 8px;font-size:1.1rem;">Hapus Foto Ini?</h3>
        <p style="margin:0 0 24px;color:#94a3b8;font-size:0.85rem;">File akan dihapus permanen dari server beserta thumbnail. <strong>Tidak dapat dibatalkan.</strong></p>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeDelModal()" class="btn btn-ghost">✕ Batal</button>
            <button onclick="executeDelete()" class="btn" style="background:#dc2626;color:white; display:inline-flex; align-items:center; gap:4px;"><svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Ya, Hapus</button>
        </div>
    </div>
</div>

<style>
@keyframes popIn { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
</style>

<script>
const lbItemsData = [<?php echo implode(',', array_map(fn($g) => json_encode(['id' => $g['id'], 'thumb' => !empty($g['thumb_url']) ? $g['thumb_url'] : $g['image_url'], 'full' => $g['image_url']]), $galleryItems)); ?>];
let lbIdx = 0, pendingDelId = null;

function openLightbox(i) {
    if (!lbItemsData.length) return;
    lbIdx = i;
    document.getElementById('lbImg').src = lbItemsData[i].full;
    document.getElementById('lbCounter').textContent = (i+1) + ' / ' + lbItemsData.length;
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex'; document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
function shiftLb(d) {
    if (!lbItemsData.length) return;
    lbIdx = (lbIdx + d + lbItemsData.length) % lbItemsData.length;
    document.getElementById('lbImg').src = lbItemsData[lbIdx].full;
    document.getElementById('lbCounter').textContent = (lbIdx+1) + ' / ' + lbItemsData.length;
}
function deleteFromLb() {
    if (!lbItemsData.length || !lbItemsData[lbIdx]) return;
    const item = lbItemsData[lbIdx];
    closeLightbox();
    confirmDelete(item.id, lbIdx + 1, item.thumb);
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
    const prev = document.getElementById('delPrevImg');
    if (prev) {
        prev.style.display = 'block';
        prev.src = thumb;
    }
    const m = document.getElementById('delModal');
    m.style.display = 'flex'; document.body.style.overflow = 'hidden';
}
function closeDelModal() {
    pendingDelId = null;
    document.getElementById('delModal').style.display = 'none';
    document.body.style.overflow = '';
}
function executeDelete() {
    if (pendingDelId) {
        window.location.href = window.location.pathname + '?action=delete&img_id=' + pendingDelId;
    }
}
function confirmDeleteAll() {
    if (confirm('Apakah Anda yakin ingin menghapus SEMUA foto galeri? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = window.location.pathname + '?action=delete_all';
    }
}

// Upload drag & drop and file pick accumulation
let selectedFiles = new DataTransfer();

document.getElementById('formPhotoInput2').addEventListener('change', function() {
    handleFiles(this.files);
});

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.background = '#faf8ff';
    document.getElementById('dropZone').style.borderColor = '#c4b5fd';
    handleFiles(e.dataTransfer.files);
}

function handleFiles(files) {
    if (!files || !files.length) return;
    
    // Append newly selected files to the DataTransfer accumulator
    Array.from(files).forEach(f => {
        if (f.type.startsWith('image/')) {
            selectedFiles.items.add(f);
        }
    });

    // Update the input element's files
    document.getElementById('formPhotoInput2').files = selectedFiles.files;

    renderPreviews();
}

function renderPreviews() {
    const bar = document.getElementById('previewBar');
    bar.innerHTML = '';
    
    Array.from(selectedFiles.files).forEach((f, index) => {
        const r = new FileReader();
        r.onload = ev => {
            const container = document.createElement('div');
            container.style.cssText = 'position:relative;width:70px;height:70px;';
            
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.style.cssText = 'width:100%;height:100%;border-radius:8px;object-fit:cover;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.1);';
            
            const delBtn = document.createElement('button');
            delBtn.innerHTML = '✕';
            delBtn.type = 'button';
            delBtn.style.cssText = 'position:absolute;top:-4px;right:-4px;width:18px;height:18px;border-radius:50%;border:none;background:#dc2626;color:white;font-size:10px;font-weight:bold;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(0,0,0,0.2);z-index:10;';
            delBtn.onclick = (e) => {
                e.stopPropagation();
                removeFile(index);
            };
            
            container.appendChild(img);
            container.appendChild(delBtn);
            bar.appendChild(container);
        };
        r.readAsDataURL(f);
    });
    
    document.getElementById('fileCount').textContent = selectedFiles.files.length;
    if (selectedFiles.files.length > 0) {
        document.getElementById('fileInfo').style.display = 'block';
    } else {
        document.getElementById('fileInfo').style.display = 'none';
    }
}

function removeFile(index) {
    const dt = new DataTransfer();
    Array.from(selectedFiles.files).forEach((f, i) => {
        if (i !== index) dt.items.add(f);
    });
    selectedFiles = dt;
    document.getElementById('formPhotoInput2').files = selectedFiles.files;
    renderPreviews();
}

function clearFiles() {
    selectedFiles = new DataTransfer();
    document.getElementById('formPhotoInput2').value = '';
    document.getElementById('previewBar').innerHTML = '';
    document.getElementById('fileInfo').style.display = 'none';
}
document.getElementById('uploadForm').addEventListener('submit', () => {
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').textContent = 'Mengupload...';
    let v = 0;
    setInterval(() => { v = Math.min(v + Math.random()*8, 90); document.getElementById('progressBar').value = v; }, 200);
});
</script>

</div><!-- /.mgmt-content -->
</body>
</html>
