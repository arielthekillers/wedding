<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($wedding) ? 'Edit' : 'Create' ?> Wedding Invitation</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --secondary: #ec4899;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 1.75rem;
            margin-bottom: 30px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 40px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--bg);
            color: var(--primary);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
        }
        input[type="text"], input[type="date"], textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .btn-group {
            margin-top: 40px;
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }
        .btn-save { background: var(--primary); color: white; }
        .btn-cancel { background: #f1f5f9; color: #475569; }
        .error { color: #ef4444; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= isset($wedding) ? 'Edit Invitation' : 'Create New Invitation' ?></h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="section-title">Site Configuration</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Internal Title (e.g. Billy & Jane Wedding)</label>
                    <input type="text" name="title" value="<?= $wedding['title'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>URL Slug (e.g. billyandjane)</label>
                    <input type="text" name="slug" value="<?= $wedding['slug'] ?? '' ?>" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="<?= $wedding['start_date'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="<?= $wedding['end_date'] ?? '' ?>">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Theme</label>
                    <select name="theme_path">
                        <?php 
                        $themes = array_filter(glob('themes/*'), 'is_dir');
                        foreach ($themes as $t) {
                            $t_name = basename($t);
                            $selected = ($wedding['theme_path'] ?? 'default') === $t_name ? 'selected' : '';
                            echo "<option value='$t_name' $selected>$t_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" style="padding-top: 30px;">
                    <label>
                        <input type="checkbox" name="is_enabled" <?= ($wedding['is_enabled'] ?? 1) ? 'checked' : '' ?>>
                        Enabled / Active
                    </label>
                </div>
            </div>

            <div class="section-title">👰 PENGANTIN WANITA (Bride)</div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="bride_name" value="<?= $wedding['bride_name'] ?? '' ?>">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Panggilan</label>
                    <input type="text" name="bride_nickname" value="<?= $wedding['bride_nickname'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Putri ke berapa</label>
                    <input type="text" name="bride_order" value="<?= $wedding['bride_order'] ?? '' ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Ayah</label>
                    <input type="text" name="bride_father" value="<?= $wedding['bride_father'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Nama Ibu</label>
                    <input type="text" name="bride_mother" value="<?= $wedding['bride_mother'] ?? '' ?>">
                </div>
            </div>

            <div class="section-title">🤵 PENGANTIN PRIA (Groom)</div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="groom_name" value="<?= $wedding['groom_name'] ?? '' ?>">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Panggilan</label>
                    <input type="text" name="groom_nickname" value="<?= $wedding['groom_nickname'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Putra ke berapa</label>
                    <input type="text" name="groom_order" value="<?= $wedding['groom_order'] ?? '' ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Ayah</label>
                    <input type="text" name="groom_father" value="<?= $wedding['groom_father'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Nama Ibu</label>
                    <input type="text" name="groom_mother" value="<?= $wedding['groom_mother'] ?? '' ?>">
                </div>
            </div>

            <div class="section-title">💕 AKAD NIKAH 🤝</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Hari/tanggal</label>
                    <input type="text" name="akad_date" value="<?= $wedding['akad_date'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Waktu (WIB/WITA/WIT)</label>
                    <input type="text" name="akad_time" value="<?= $wedding['akad_time'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Tempat</label>
                <textarea name="akad_place"><?= $wedding['akad_place'] ?? '' ?></textarea>
            </div>

            <div class="section-title">💕 RESEPSI PERNIKAHAN</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Hari/tanggal</label>
                    <input type="text" name="reception_date" value="<?= $wedding['reception_date'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Waktu (WIB/WITA/WIT)</label>
                    <input type="text" name="reception_time" value="<?= $wedding['reception_time'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Tempat</label>
                <textarea name="reception_place"><?= $wedding['reception_place'] ?? '' ?></textarea>
            </div>
            <div class="form-group">
                <label>Sharelok Acara (Maps Link)</label>
                <input type="text" name="reception_map_link" value="<?= $wedding['reception_map_link'] ?? '' ?>">
            </div>

            <div class="section-title">🎁 KIRIM HADIAH (Gift Info)</div>

            <p style="font-size:0.85rem; color:#64748b; margin-top:-10px;">Rekening 1</p>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Pemilik Rekening</label>
                    <input type="text" name="bank1_owner" placeholder="Contoh: SYIFA AULIA FADHILLAH" value="<?= $wedding['bank1_owner'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Nama Bank</label>
                    <input type="text" name="bank1_name" placeholder="Contoh: Bank Mandiri" value="<?= $wedding['bank1_name'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Nomor Rekening</label>
                <input type="text" name="bank1_acc" placeholder="Contoh: 1480020167113" value="<?= $wedding['bank1_acc'] ?? '' ?>">
            </div>

            <p style="font-size:0.85rem; color:#64748b; margin-top:5px;">Rekening 2</p>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Pemilik Rekening</label>
                    <input type="text" name="bank2_owner" placeholder="Contoh: ABDUL ROCHIM MUIZ" value="<?= $wedding['bank2_owner'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Nama Bank</label>
                    <input type="text" name="bank2_name" placeholder="Contoh: Bank Mandiri" value="<?= $wedding['bank2_name'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Nomor Rekening</label>
                <input type="text" name="bank2_acc" placeholder="Contoh: 1480021027993" value="<?= $wedding['bank2_acc'] ?? '' ?>">
            </div>

            <div class="section-title">🖼️ GALERI FOTO</div>
            <div class="form-group">
                <label>Image URLs (one per line)</label>
                <textarea name="gallery_urls" rows="6" placeholder="https://example.com/photo1.jpg"><?php 
                    if (isset($id)) {
                        $gallery = Models\Wedding::getGallery($id);
                        echo implode("\n", $gallery);
                    }
                ?></textarea>
            </div>

            <?php if (isset($id)): ?>
            <div class="section-title">📝 BUKU TAMU & RSVP</div>
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; max-height: 400px; overflow-y: auto; border: 1px solid var(--border);">
                <?php 
                $messages = Models\Wedding::getMessages($id);
                if (empty($messages)): ?>
                    <p style="text-align: center; color: #64748b; font-size: 0.9rem;">Belum ada pesan dari tamu.</p>
                <?php else: ?>
                    <?php foreach ($messages as $m): 
                        $avatar  = Models\Wedding::gravatarUrl($m['email'] ?? '', 40);
                        $isHadir = (bool)$m['attendance'];
                    ?>
                        <div style="display:flex; gap:12px; align-items:flex-start; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #e2e8f0;">
                            <img src="<?= $avatar ?>" width="40" height="40" style="border-radius:50%; flex-shrink:0;">
                            <div style="flex:1;">
                                <div style="display:flex; justify-content:space-between; align-items:center; gap:5px; flex-wrap:wrap;">
                                    <div>
                                        <strong style="font-size:0.9rem;"><?= $m['name'] ?></strong>
                                        <span style="font-size:0.7rem; color:#94a3b8; margin-left:6px;"><?= $m['email'] ?></span>
                                    </div>
                                    <div style="display:flex; gap:6px; align-items:center;">
                                        <span class="status-badge <?= $isHadir ? 'status-on' : 'status-off' ?>" style="font-size:0.65rem;">
                                            <?= $isHadir ? 'Hadir' : 'Tidak Hadir' ?>
                                        </span>
                                        <a href="../../admin/guestbook/delete/<?= $m['id'] ?>"
                                           onclick="return confirm('Hapus komentar ini?')"
                                           style="font-size:0.7rem; background:#fee2e2; color:#dc2626; padding:3px 8px; border-radius:6px; text-decoration:none; font-weight:600;">
                                            Hapus
                                        </a>
                                    </div>
                                </div>
                                <p style="margin:4px 0 0; font-size:0.82rem; color:#475569;"><?= $m['message'] ?></p>
                                <small style="color:#cbd5e1; font-size:0.7rem;"><?= $m['created_at'] ?> — IP: <?= $m['ip_address'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">Save Invitation</button>
                <a href="<?= isset($wedding) ? '../../admin' : '../admin' ?>" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
