<?php
$activeTab = 'story';
include __DIR__ . '/partials/wedding_nav.php';
?>
<link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">
<style>
    .timeline { position:relative; padding-left:36px; }
    .timeline::before { content:''; position:absolute; left:14px; top:0; bottom:0; width:2px; background:linear-gradient(to bottom,#ddd6fe,#fce7f3); }
    .timeline-item { position:relative; margin-bottom:20px; background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--border); overflow:hidden; transition:transform 0.2s,box-shadow 0.2s; }
    .timeline-item:hover { transform:translateY(-2px); box-shadow:var(--shadow-lg); }
    .timeline-dot { position:absolute; left:-30px; top:24px; width:14px; height:14px; border-radius:50%; background:var(--grad); box-shadow:0 0 0 3px #ede9fe; }
    .item-body { display:flex; }
    .item-thumb { width:100px; flex-shrink:0; object-fit:cover; }
    .item-thumb-ph { width:100px; flex-shrink:0; background:linear-gradient(135deg,#ede9fe,#fce7f3); display:flex; align-items:center; justify-content:center; font-size:1.8rem; }
    .item-content { padding:16px 18px; flex:1; }
    .item-date { font-size:0.72rem; color:#c5a059; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:5px; }
    .item-title { font-size:1rem; font-weight:700; margin:0 0 6px; }
    .item-text { font-size:0.85rem; color:#64748b; line-height:1.5; margin:0; }
    .item-actions { display:flex; gap:6px; margin-top:12px; }
    /* Modal */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:1000; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal-box { background:white; border-radius:20px; padding:32px 36px; width:100%; max-width:560px; max-height:90vh; overflow-y:auto; box-shadow:0 25px 60px rgba(0,0,0,0.2); animation:slideUp 0.25s ease; }
    @keyframes slideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
    .modal-title { font-size:1.15rem; font-weight:700; background:var(--grad); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .modal-close { background:#f1f5f9; border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; font-size:0.95rem; }
</style>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">✅ Cerita berhasil disimpan!</div>
<?php elseif (isset($_GET['deleted'])): ?>
<div class="alert alert-info">🗑️ Cerita berhasil dihapus.</div>
<?php endif; ?>

<!-- ── INTRO & OUTRO TEXTS ── -->
<div class="section-card" style="margin-bottom:24px;">
    <div class="section-card-header">
        <div class="icon">📝</div>
        <div>
            <h2>Teks Pembuka & Penutup</h2>
            <p>Kata-kata tambahan sebelum dan sesudah timeline cerita</p>
        </div>
    </div>
    <div class="section-card-body">
        <form method="POST">
            <input type="hidden" name="action" value="save_texts">
            <div class="form-grid g2">
                <div class="form-group">
                    <label class="form-label">Teks Pembuka (Intro)</label>
                    <textarea name="story_intro" class="form-control" rows="3" placeholder="Contoh: Ini adalah perjalanan cinta kami..."><?= htmlspecialchars($wedding['story_intro'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Penutup (Outro)</label>
                    <textarea name="story_outro" class="form-control" rows="3" placeholder="Contoh: Dan kini kami memulai babak baru..."><?= htmlspecialchars($wedding['story_outro'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
            </div>
            <div style="margin-top:16px; text-align:right;">
                <button type="submit" class="btn btn-primary">💾 Simpan Teks</button>
            </div>
        </form>
    </div>
</div>

<!-- ── ACTION ROW ── -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <strong style="font-size:1.05rem;">Timeline Cerita</strong>
        <span style="background:#ede9fe;color:var(--primary);font-size:0.72rem;font-weight:700;padding:2px 10px;border-radius:20px;margin-left:8px;">
            <?= count($stories) ?> Cerita
        </span>
        <p style="margin:4px 0 0;color:#94a3b8;font-size:0.82rem;">Kelola momen-momen perjalanan cinta Anda</p>
    </div>
    <button class="btn btn-primary" onclick="openModal()">✨ Tambah Cerita</button>
</div>

<!-- ── TIMELINE ── -->
<?php if (empty($stories)): ?>
<div class="section-card" style="text-align:center;padding:60px 20px;">
    <div style="font-size:3rem;margin-bottom:12px;">💕</div>
    <h3 style="margin:0 0 8px;color:#475569;">Belum ada cerita cinta</h3>
    <p style="color:#94a3b8;margin:0 0 20px;">Tambahkan momen-momen indah perjalanan cinta pengantin.</p>
    <button class="btn btn-primary" onclick="openModal()">✨ Tambah Cerita Pertama</button>
</div>
<?php else: ?>
<div class="timeline">
    <?php foreach ($stories as $s): ?>
    <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="item-body">
            <?php if (!empty($s['image_url'])): ?>
                <img src="<?= htmlspecialchars($s['image_url']) ?>" class="item-thumb" alt="<?= htmlspecialchars($s['title']) ?>">
            <?php else: ?>
                <div class="item-thumb-ph">💝</div>
            <?php endif; ?>
            <div class="item-content">
                <div class="item-date">📅 <?= date('d F Y', strtotime($s['story_date'])) ?></div>
                <div class="item-title"><?= htmlspecialchars($s['title']) ?></div>
                <p class="item-text"><?= nl2br(htmlspecialchars($s['content'])) ?></p>
                <div class="item-actions">
                    <button class="btn btn-ghost btn-sm"
                            onclick="openEditModal(<?= $s['id'] ?>, <?= htmlspecialchars(json_encode($s['title'])) ?>, '<?= $s['story_date'] ?>', <?= htmlspecialchars(json_encode($s['content'])) ?>, <?= htmlspecialchars(json_encode($s['image_url'])) ?>, <?= (int)$s['sort_order'] ?>)">
                        ✏️ Edit
                    </button>
                    <a href="?action=delete&story_id=<?= $s['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus cerita ini?')">🗑️ Hapus</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── MODAL ── -->
<div class="modal-overlay" id="modalOverlay" onclick="if(event.target===this)closeModal()">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle">✨ Tambah Cerita Baru</div>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <form method="POST" id="storyForm">
            <input type="hidden" name="story_id" id="storyId" value="">
            <div class="form-group">
                <label class="form-label">Judul Momen *</label>
                <input type="text" name="title" id="fieldTitle" class="form-control" placeholder="Contoh: Pertama Kali Bertemu" required>
            </div>
            <div class="form-grid g2" style="margin-top:14px;">
                <div class="form-group">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="story_date" id="fieldDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="fieldSort" class="form-control" value="0" min="0">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Cerita / Deskripsi *</label>
                <textarea name="content" id="fieldContent" class="form-control" placeholder="Ceritakan momen spesial ini..." required></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">URL Foto <small>(opsional)</small></label>
                <input type="url" name="image_url" id="fieldImage" class="form-control" placeholder="https://...">
            </div>
            <div style="margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    💾 Simpan Cerita
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = '✨ Tambah Cerita Baru';
    document.getElementById('storyId').value = '';
    ['fieldTitle','fieldDate','fieldContent','fieldImage'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fieldSort').value = '0';
    document.getElementById('modalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function openEditModal(id, title, date, content, image, sort) {
    document.getElementById('modalTitle').textContent = '✏️ Edit Cerita';
    document.getElementById('storyId').value = id;
    document.getElementById('fieldTitle').value = title;
    document.getElementById('fieldDate').value = date;
    document.getElementById('fieldContent').value = content;
    document.getElementById('fieldImage').value = image || '';
    document.getElementById('fieldSort').value = sort;
    document.getElementById('modalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

</div><!-- /.mgmt-content -->
</body>
</html>
