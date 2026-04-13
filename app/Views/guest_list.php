<?php
$activeTab = 'guests';
include __DIR__ . '/partials/wedding_nav.php';

$scheme   = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$basePath = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/');
$weddingUrl = $scheme . '://' . $host . $basePath . '/' . $wedding['slug'];
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">✅ Data berhasil disimpan!</div>
<?php elseif (isset($_GET['deleted'])): ?>
<div class="alert alert-success">🗑️ Data berhasil dihapus!</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-card-header" style="justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; display:flex;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="icon">📋</div>
            <h2 style="margin:0;">Manajemen Daftar Undangan</h2>
        </div>
        <div style="display:flex; gap:10px;">
            <button type="button" onclick="openAddCatModal()" class="btn btn-ghost btn-sm" style="padding:6px 12px; font-size:0.8rem;">+ Tambah Kategori</button>
            <button type="button" onclick="openAddGuestModal()" class="btn btn-primary btn-sm" style="padding:6px 16px; font-size:0.8rem; border-radius:30px;">+ Tambah Tamu</button>
        </div>
    </div>
    
    <div class="section-card-body" style="padding:0;">
        <!-- Categories Overview Row (Filter Badges) -->
        <div style="padding:15px 20px; border-bottom:1px solid var(--border); background:#f8fafc; display:flex; flex-wrap:wrap; gap:8px;">
            <div onclick="filterGuests('all', this)" class="cat-filter-btn active">
                <span class="cat-name">Semua Tamu</span> 
                <span class="cat-count">(<?= $stats['total'] ?>)</span>
            </div>

            <?php foreach ($stats['by_category'] as $stat): ?>
                <div class="cat-filter-btn" onclick="filterGuests('cat-<?= $stat['id'] ?>', this)">
                    <span class="cat-name"><?= htmlspecialchars($stat['category_name']) ?></span> 
                    <span class="cat-count">(<?= $stat['total'] ?>)</span>
                    <span onclick="confirmCategoryDelete(event, '<?= $stat['id'] ?>', '<?= htmlspecialchars(addslashes($stat['category_name'])) ?>')" 
                       class="cat-del" style="padding: 0 8px; margin-left: 5px;">&times;</span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Guest Matrix Table -->
        <?php if (empty($allGuests)): ?>
        <div style="text-align:center;padding:50px 0;">
            <div style="font-size:2.5rem;margin-bottom:10px;">📭</div>
            <p style="color:#94a3b8;margin:0;">Sistem undangan masih kosong. Mulai dengan membuat kategori & tambah tamu.</p>
        </div>
        <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="text-align:left;">
                <th style="padding: 12px 20px; font-size:0.85rem; color:#475569; border-bottom: 2px solid var(--border);">Silsilah Tamu / Link</th>
                <th style="padding: 12px 20px; font-size:0.85rem; color:#475569; border-bottom: 2px solid var(--border); width: 120px;">Kategori</th>
                <th style="padding: 12px 20px; font-size:0.85rem; color:#475569; border-bottom: 2px solid var(--border); text-align:right; width: 250px;">Aksi</th>
            </tr>
            <?php foreach ($allGuests as $g): 
                $link = $weddingUrl . '?to=' . rawurlencode($g['name']);
            ?>
            <tr class="guest-row cat-<?= $g['category_id'] ?>" style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 20px;">
                    <div style="font-size:0.95rem; font-weight:600; color:#1e293b;"><?= htmlspecialchars($g['name']) ?></div>
                </td>
                <td style="padding: 12px 20px;">
                    <span style="display:inline-block; font-size:0.75rem; background:#ede9fe; color:var(--primary); padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap;">
                        <?= htmlspecialchars($g['category_name']) ?>
                    </span>
                </td>
                <td style="padding: 12px 20px; text-align:right;">
                    <div style="display:inline-flex; gap:6px; align-items:center; position:relative;">
                        <button onclick="copyLink('<?= htmlspecialchars($link, ENT_QUOTES) ?>', <?= $g['id'] ?>)" class="btn btn-ghost btn-sm" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; font-size:0.75rem;">
                            <span>🔗</span><span>Salin</span>
                        </button>
                        <a href="https://wa.me/?text=<?= rawurlencode('Halo ' . $g['name'] . '! Kami mengundang kamu ke pernikahan kami 💍 ' . $link) ?>"
                           target="_blank" class="btn btn-sm" style="display:inline-flex; align-items:center; gap:4px; background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:6px 10px; font-size:0.75rem;">
                           <span>💬</span><span>WA</span>
                        </a>
                        <span style="color:#e2e8f0; margin:0 4px;">|</span>
                        <button onclick="openEditModal(<?= $g['id'] ?>, '<?= htmlspecialchars(addslashes($g['name'])) ?>', <?= $g['category_id'] ?>)" 
                                class="btn btn-ghost btn-sm" style="display:inline-flex; align-items:center; gap:4px; color:#d97706; padding:6px 10px; font-size:0.75rem;">
                                <span>✏️</span><span>Edit</span>
                        </button>
                        <span onclick="confirmGuestDelete(event, <?= $g['id'] ?>, '<?= htmlspecialchars(addslashes($g['name'])) ?>')" 
                           class="btn btn-ghost btn-sm" style="display:inline-flex; align-items:center; gap:4px; color:#dc2626; padding:6px 10px; font-size:0.75rem; cursor:pointer;">
                           <span>🗑️</span><span>Hapus</span>
                        </span>
                        
                        <div id="flash-<?= $g['id'] ?>" style="font-size:0.7rem; color:#166534; font-weight:700; position:absolute; display:none; top:-20px; right:0;">✓ URL disalin</div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- ── MODAL ADD CATEGORY ── -->
<div id="addCatModal" class="modal-overlay">
    <div class="section-card modal-container">
        <div class="section-card-header">
            <h2>Tambah Kategori</h2>
            <button type="button" onclick="closeModals()" class="close-btn">&times;</button>
        </div>
        <div class="section-card-body">
            <form method="POST" action="?action=add_cat">
                <div class="form-group mb-4">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="cat_name" class="form-control" placeholder="Teman SMA" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Simpan Kategori</button>
            </form>
        </div>
    </div>
</div>

<!-- ── MODAL ADD GUEST ── -->
<div id="addGuestModal" class="modal-overlay">
    <div class="section-card modal-container">
        <div class="section-card-header">
            <h2>Tambah Tamu Baru</h2>
            <button type="button" onclick="closeModals()" class="close-btn">&times;</button>
        </div>
        <div class="section-card-body">
            <?php if (empty($categories)): ?>
                <div class="alert alert-error" style="background:#fee2e2; color:#dc2626; padding:10px; border-radius:6px; font-size:0.85rem;">Buat setidaknya satu kategori terlebih dahulu!</div>
            <?php else: ?>
            <form method="POST" action="?action=add_guest">
                <div class="form-group mb-3">
                    <label class="form-label">Pilih Kategori</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label">Nama Tamu</label>
                    <input type="text" name="guest_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Tambah Tamu Form</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ── MODAL EDIT GUEST ── -->
<div id="editModal" class="modal-overlay">
    <div class="section-card modal-container">
        <div class="section-card-header">
            <h2>Edit Tamu</h2>
            <button type="button" onclick="closeModals()" class="close-btn">&times;</button>
        </div>
        <div class="section-card-body">
            <form method="POST" action="?action=edit_guest">
                <input type="hidden" name="guest_id" id="editGuestId">
                <div class="form-group mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" id="editCategoryId" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label">Nama Tamu</label>
                    <input type="text" name="guest_name" id="editGuestName" class="form-control" required>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="closeModals()" class="btn btn-ghost" style="flex:1; justify-content:center;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── MODAL DELETE CATEGORY ── -->
<div id="deleteCatModal" class="modal-overlay">
    <div class="section-card modal-container" style="max-width:380px;">
        <div class="section-card-header">
            <h2 style="color:#ef4444;">Hapus Kategori?</h2>
            <button type="button" onclick="closeModals()" class="close-btn">&times;</button>
        </div>
        <div class="section-card-body" style="text-align:center;">
            <div style="font-size:3rem; margin-bottom:15px;">⚠️</div>
            <p style="margin-bottom:20px; color:#475569; line-height:1.5;">
                Apakah Anda yakin ingin menghapus kategori <strong id="delCatName"></strong>?<br>
                <span style="color:#ef4444; font-size:0.85rem; font-weight:600;">Semua tamu di dalamnya akan ikut terhapus selamanya.</span>
            </p>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeModals()" class="btn btn-ghost" style="flex:1; justify-content:center;">Batal</button>
                <a id="delCatConfirmBtn" href="#" class="btn" style="flex:1; justify-content:center; background:#ef4444; color:#fff;">Ya, Hapus Semua</a>
            </div>
        </div>
    </div>
</div>

<!-- ── MODAL DELETE GUEST ── -->
<div id="deleteGuestModal" class="modal-overlay">
    <div class="section-card modal-container" style="max-width:380px;">
        <div class="section-card-header">
            <h2 style="color:#ef4444;">Hapus Tamu?</h2>
            <button type="button" onclick="closeModals()" class="close-btn">&times;</button>
        </div>
        <div class="section-card-body" style="text-align:center;">
            <div style="font-size:3rem; margin-bottom:15px;">🗑️</div>
            <p style="margin-bottom:20px; color:#475569; line-height:1.5;">
                Apakah Anda yakin ingin menghapus nama tamu <strong id="delGuestName"></strong>?
            </p>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeModals()" class="btn btn-ghost" style="flex:1; justify-content:center;">Batal</button>
                <a id="delGuestConfirmBtn" href="#" class="btn" style="flex:1; justify-content:center; background:#ef4444; color:#fff;">Ya, Hapus Tamu</a>
            </div>
        </div>
    </div>
</div>

<style>
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 20px; }
    .modal-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(2px); }
    .modal-container { width: 100%; max-width: 420px; margin:20px; background:#fff; animation: popIn 0.3s ease; position: relative; }
    .close-btn { position:absolute; top: 15px; right: 15px; background:transparent; border:none; font-size:1.5rem; cursor:pointer; color:#94a3b8; transition:0.2s; line-height: 1; outline: none; }
    .close-btn:hover { color:#ef4444; }
    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .cat-filter-btn {
        display:inline-flex; align-items:center; background:#fff; padding:4px 12px; border-radius:30px; font-size:0.8rem; border:1px solid var(--border); box-shadow:0 1px 2px rgba(0,0,0,0.02); cursor:pointer;
        transition: 0.2s;
    }
    .cat-filter-btn .cat-name { font-weight:600; color:var(--primary); margin-right:5px; transition: 0.2s;}
    .cat-filter-btn .cat-count { color:#64748b; font-size:0.75rem; transition: 0.2s;}
    .cat-filter-btn .cat-del { display:inline-flex; align-items:center; color:#ef4444; margin-left:8px; text-decoration:none; font-weight:bold; font-size:1rem; line-height:1; }

    .cat-filter-btn:hover { border-color: var(--primary); }
    .cat-filter-btn.active {
        background: var(--primary);
        border-color: var(--primary);
    }
    .cat-filter-btn.active .cat-name { color: #fff; }
    .cat-filter-btn.active .cat-count { color: rgba(255,255,255,0.8); }
    .cat-filter-btn.active .cat-del { color: #fecaca; }
    .cat-filter-btn.active .cat-del:hover { color: #fff; }
</style>

<script>
// Filter Guests by Category
function filterGuests(val, btnElement) {
    const rows = document.querySelectorAll('.guest-row');
    rows.forEach(row => {
        if (val === 'all' || row.classList.contains(val)) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });

    if (btnElement) {
        document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active'));
        btnElement.classList.add('active');
    }
}

// Copy to Clipboard
function copyLink(link, id) {
    navigator.clipboard.writeText(link).then(() => {
        const flash = document.getElementById('flash-' + id);
        flash.style.display = 'inline-block';
        setTimeout(() => { flash.style.display = 'none'; }, 2000);
    });
}

function confirmCategoryDelete(e, id, name) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }
    document.getElementById('delCatName').innerText = name;
    document.getElementById('delCatConfirmBtn').href = '?action=delete_cat&cat_id=' + id;
    document.getElementById('deleteCatModal').style.display = 'flex';
}

function confirmGuestDelete(e, id, name) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }
    document.getElementById('delGuestName').innerText = name;
    document.getElementById('delGuestConfirmBtn').href = '?action=delete_guest&guest_id=' + id;
    document.getElementById('deleteGuestModal').style.display = 'flex';
}

// Modals
function openAddCatModal() { document.getElementById('addCatModal').style.display = 'flex'; }
function openAddGuestModal() { document.getElementById('addGuestModal').style.display = 'flex'; }
function openEditModal(id, name, catId) {
    document.getElementById('editGuestId').value = id;
    document.getElementById('editGuestName').value = name;
    document.getElementById('editCategoryId').value = catId;
    document.getElementById('editModal').style.display = 'flex';
}
function closeModals() {
    document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none');
}

// Close modal on outside click
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        closeModals();
    }
}
</script>

</div><!-- /.mgmt-content -->
</body>
</html>

