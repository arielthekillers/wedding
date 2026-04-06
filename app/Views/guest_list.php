<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Undangan — <?= $wedding['title'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #8b5cf6; --secondary: #ec4899; --bg: #f8fafc; --border: #e2e8f0; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg); margin: 0; padding: 30px; color: #1e293b; }
        .container { max-width: 900px; margin: auto; }
        h1 { font-size: 1.6rem; background: linear-gradient(to right, var(--primary), var(--secondary));
             -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 5px; }
        .subtitle { color: #64748b; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: white; border-radius: 14px; padding: 24px; border: 1px solid var(--border);
                box-shadow: 0 4px 10px rgba(0,0,0,0.04); margin-bottom: 20px; }
        .cat-header { display: flex; justify-content: space-between; align-items: center; }
        .cat-name { font-weight: 700; font-size: 1rem; color: var(--primary); }
        .guest-row { display: flex; justify-content: space-between; align-items: center;
                     padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .guest-row:last-child { border-bottom: none; }
        .guest-name { font-size: 0.9rem; }
        .btn-copy { background: linear-gradient(to right, var(--primary), var(--secondary));
                    color: white; border: none; padding: 6px 14px; border-radius: 8px;
                    cursor: pointer; font-size: 0.78rem; font-family: inherit; font-weight: 600;
                    transition: 0.2s; white-space: nowrap; }
        .btn-copy:hover { opacity: 0.85; transform: translateY(-1px); }
        /* Add form */
        .add-form { background: white; border-radius: 14px; padding: 24px; border: 2px dashed var(--border); margin-bottom: 20px; }
        .add-form h3 { margin: 0 0 16px; font-size: 1rem; color: #475569; }
        .cat-block { background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 16px; margin-bottom: 14px; position: relative; }
        .cat-block input[type=text] { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-family: inherit; font-size: 0.9rem; }
        .cat-block textarea { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-family: inherit; font-size: 0.9rem; resize: vertical; height: 80px; margin-top: 8px; }
        label { font-size: 0.8rem; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px; }
        .btn { padding: 10px 22px; border-radius: 8px; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 0.85rem; border: none; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-add { background: #f1f5f9; color: #475569; border: 1px solid var(--border); }
        .btn-save { background: linear-gradient(to right, var(--primary), var(--secondary)); color: white; font-size: 1rem; padding: 12px 30px; }
        .btn-remove { position: absolute; top: 10px; right: 10px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem; padding: 4px 10px; }
        .back-link { color: #64748b; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 20px; }
        .total-badge { background: #ede9fe; color: var(--primary); padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .copy-flash { font-size: 0.75rem; color: #166534; font-weight: 600; display: none; margin-left: 8px; }
    </style>
</head>
<body>
<div class="container">
    <a href="../../admin" class="back-link">← Kembali ke Dashboard</a>
    <h1>📋 Daftar Undangan</h1>
    <p class="subtitle"><?= $wedding['title'] ?> — <a href="../../<?= $wedding['slug'] ?>" target="_blank" style="color:var(--primary)">localhost/<?= $wedding['slug'] ?></a></p>

    <!-- ADD / EDIT FORM -->
    <div class="add-form">
        <h3>Tambah / Perbarui Kategori Tamu</h3>
        <form method="POST" id="guestForm">
            <div id="catList">
                <?php foreach ($categories as $cat): ?>
                <div class="cat-block">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕ Hapus</button>
                    <label>Nama Kategori</label>
                    <input type="text" name="cat_name[]" value="<?= htmlspecialchars($cat['name']) ?>" placeholder="Contoh: Teman SMA" required>
                    <label style="margin-top:8px;">Nama Tamu <small style="font-weight:400;">(pisahkan dengan koma)</small></label>
                    <textarea name="cat_guests[]" placeholder="Budi, Ani, Siti"><?php
                        echo htmlspecialchars(implode(', ', array_column($cat['guests'], 'name')));
                    ?></textarea>
                </div>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                <div class="cat-block">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕ Hapus</button>
                    <label>Nama Kategori</label>
                    <input type="text" name="cat_name[]" placeholder="Contoh: Teman SMA" required>
                    <label style="margin-top:8px;">Nama Tamu <small style="font-weight:400;">(pisahkan dengan koma)</small></label>
                    <textarea name="cat_guests[]" placeholder="Budi, Ani, Siti"></textarea>
                </div>
                <?php endif; ?>
            </div>
            <div style="display:flex; gap:12px; margin-top:16px; align-items:center; flex-wrap:wrap;">
                <button type="button" onclick="addCategory()" class="btn btn-add">+ Tambah Kategori</button>
                <button type="submit" class="btn btn-save">💾 Simpan Daftar</button>
            </div>
        </form>
    </div>

    <!-- GUEST LIST WITH SHARE LINKS -->
    <?php if (!empty($categories)): 
        $allGuests = Models\Wedding::getAllGuests($wedding['id']);
        $scheme    = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host      = $_SERVER['HTTP_HOST'];
        $basePath  = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/');
        $weddingUrl = $scheme . '://' . $host . $basePath . '/' . $wedding['slug'];
        // Group by category
        $grouped = [];
        foreach ($allGuests as $g) {
            $grouped[$g['category_name']][] = $g;
        }
    ?>
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <strong style="font-size:1.05rem;">Daftar Tamu & Link Undangan</strong>
            <span class="total-badge"><?= count($allGuests) ?> tamu</span>
        </div>

        <?php foreach ($grouped as $catName => $guests): ?>
        <div style="margin-bottom:24px;">
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#94a3b8; margin-bottom:10px;">
                📂 <?= htmlspecialchars($catName) ?> (<?= count($guests) ?>)
            </div>
            <?php foreach ($guests as $g):
                $link = $weddingUrl . '?to=' . rawurlencode($g['name']);
            ?>
            <div class="guest-row">
                <span class="guest-name"><?= htmlspecialchars($g['name']) ?></span>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span class="copy-flash" id="flash-<?= $g['id'] ?>">Tersalin ✓</span>
                    <button class="btn-copy" onclick="copyLink('<?= htmlspecialchars($link, ENT_QUOTES) ?>', <?= $g['id'] ?>)">
                        🔗 Salin Link
                    </button>
                    <a href="https://wa.me/?text=<?= rawurlencode('Halo ' . $g['name'] . '! Kami mengundang kamu ke pernikahan kami 💍 ' . $link) ?>"
                       target="_blank" class="btn-copy" style="background: linear-gradient(135deg, #25d366, #128c7e); text-decoration:none;">
                        📲 WhatsApp
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function addCategory() {
    const block = `<div class="cat-block">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕ Hapus</button>
        <label>Nama Kategori</label>
        <input type="text" name="cat_name[]" placeholder="Contoh: Keluarga" required>
        <label style="margin-top:8px;">Nama Tamu <small style="font-weight:400;">(pisahkan dengan koma)</small></label>
        <textarea name="cat_guests[]" placeholder="Budi, Ani, Siti"></textarea>
    </div>`;
    document.getElementById('catList').insertAdjacentHTML('beforeend', block);
}

function copyLink(url, id) {
    navigator.clipboard.writeText(url).then(() => {
        const flash = document.getElementById('flash-' + id);
        if (flash) {
            flash.style.display = 'inline';
            setTimeout(() => flash.style.display = 'none', 2000);
        }
    });
}
</script>
</body>
</html>
