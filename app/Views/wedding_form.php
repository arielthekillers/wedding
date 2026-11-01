<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($wedding) ? 'Edit' : 'Buat' ?> Undangan Pernikahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">
    <!-- ── TOPBAR ── -->
    <header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="flex items-center gap-2 text-lg font-bold tracking-tight">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"></path></svg>
                <span>Palovite</span>
                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-800 text-slate-300 rounded border border-slate-700 ml-1">Admin</span>
            </h1>
            <div class="flex items-center gap-3">
                <a href="<?= isset($wedding) ? '../../admin' : '../admin' ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800 border border-slate-700">
                    <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Batal
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider"><?= isset($wedding) ? 'Edit Undangan' : 'Buat Undangan Baru' ?></h2>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 text-sm font-semibold"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <!-- ── SITE CONFIG ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Konfigurasi Undangan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Judul Internal <small class="font-normal text-slate-400">(Nama Acara)</small></label>
                        <input type="text" name="title" value="<?= htmlspecialchars($wedding['title'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">URL Slug <small class="font-normal text-slate-400">(Huruf kecil, tanpa spasi)</small></label>
                        <input type="text" name="slug" id="slug-input" value="<?= htmlspecialchars($wedding['slug'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" required pattern="[a-z0-9\-]+" title="Hanya huruf kecil, angka, dan tanda minus">
                        <div id="slug-status" class="text-[11px] min-height-[1em] mt-1 font-semibold"></div>
                        <div class="text-xs text-slate-400 mt-2">
                            Tautan: <span class="text-blue-600 font-semibold"><?= $_SERVER['HTTP_HOST'] . (rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/') . '/') ?></span><span id="slug-preview-text" class="text-blue-600 font-semibold"><?= htmlspecialchars($wedding['slug'] ?? '', ENT_QUOTES) ?></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tanggal Mulai / Hitung Mundur</label>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($wedding['start_date'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tanggal Selesai (Opsional)</label>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($wedding['end_date'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Pilihan Tema</label>
                        <select name="theme_path" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
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
                    <div class="flex items-center h-full pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="is_enabled" value="1" <?= ($wedding['is_enabled'] ?? 1) ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span>Undangan langsung aktif dan dapat diakses publik</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ── BRIDE DETAILS ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    Data Pengantin Wanita (Bride)
                </h3>
                <div class="form-group mb-4">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="bride_name" value="<?= htmlspecialchars($wedding['bride_name'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Panggilan</label>
                        <input type="text" name="bride_nickname" value="<?= htmlspecialchars($wedding['bride_nickname'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Putri Ke (Urutan Anak)</label>
                        <input type="text" name="bride_order" placeholder="Contoh: Putri pertama" value="<?= htmlspecialchars($wedding['bride_order'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Ayah</label>
                        <input type="text" name="bride_father" value="<?= htmlspecialchars($wedding['bride_father'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Ibu</label>
                        <input type="text" name="bride_mother" value="<?= htmlspecialchars($wedding['bride_mother'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
            </div>

            <!-- ── GROOM DETAILS ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Data Pengantin Pria (Groom)
                </h3>
                <div class="form-group mb-4">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="groom_name" value="<?= htmlspecialchars($wedding['groom_name'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Panggilan</label>
                        <input type="text" name="groom_nickname" value="<?= htmlspecialchars($wedding['groom_nickname'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Putra Ke (Urutan Anak)</label>
                        <input type="text" name="groom_order" placeholder="Contoh: Putra pertama" value="<?= htmlspecialchars($wedding['groom_order'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Ayah</label>
                        <input type="text" name="groom_father" value="<?= htmlspecialchars($wedding['groom_father'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Ibu</label>
                        <input type="text" name="groom_mother" value="<?= htmlspecialchars($wedding['groom_mother'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
            </div>

            <!-- ── AKAD NIKAH ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><circle cx="12" cy="12" r="8"></circle><circle cx="12" cy="12" r="4"></circle></svg>
                    Data Akad Nikah
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Hari / Tanggal</label>
                        <input type="text" name="akad_date" value="<?= htmlspecialchars($wedding['akad_date'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="Contoh: Sabtu, 10 Januari 2026">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Waktu Akad</label>
                        <input type="text" name="akad_time" value="<?= htmlspecialchars($wedding['akad_time'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="Contoh: 08.00 - 10.00 WIB">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tempat / Alamat Akad</label>
                    <textarea name="akad_place" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" rows="2" placeholder="Nama Masjid, gedung atau alamat rumah lengkap..."><?= htmlspecialchars($wedding['akad_place'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
            </div>

            <!-- ── RESEPSI NIKAH ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Data Resepsi Pernikahan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Hari / Tanggal</label>
                        <input type="text" name="reception_date" value="<?= htmlspecialchars($wedding['reception_date'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="Contoh: Sabtu, 10 Januari 2026">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Waktu Resepsi</label>
                        <input type="text" name="reception_time" value="<?= htmlspecialchars($wedding['reception_time'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="Contoh: 11.00 - selesai">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tempat / Alamat Resepsi</label>
                    <textarea name="reception_place" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" rows="2" placeholder="Nama gedung, hotel, atau alamat rumah lengkap..."><?= htmlspecialchars($wedding['reception_place'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tautan Google Maps Lokasi</label>
                    <input type="text" name="reception_map_link" value="<?= htmlspecialchars($wedding['reception_map_link'] ?? '', ENT_QUOTES) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="https://maps.google.com/...">
                </div>
            </div>

            <!-- ── GIFTS INFO ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    Kirim Hadiah Digital (Gift Info)
                </h3>
                
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 border-l-2 border-blue-600 pl-2">Rekening Pengantin Pertama</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Pemilik Rekening</label>
                        <input type="text" name="bank1_owner" placeholder="Nama Pemilik Rekening 1" value="<?= $wedding['bank1_owner'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Bank</label>
                        <input type="text" name="bank1_name" placeholder="Contoh: BCA / Mandiri / dst" value="<?= $wedding['bank1_name'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
                <div class="form-group mb-6">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nomor Rekening</label>
                    <input type="text" name="bank1_acc" placeholder="Contoh: 1234567890" value="<?= $wedding['bank1_acc'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                </div>

                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 border-l-2 border-blue-600 pl-2">Rekening Pengantin Kedua</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Pemilik Rekening</label>
                        <input type="text" name="bank2_owner" placeholder="Nama Pemilik Rekening 2" value="<?= $wedding['bank2_owner'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Bank</label>
                        <input type="text" name="bank2_name" placeholder="Contoh: BCA / Mandiri / dst" value="<?= $wedding['bank2_name'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                </div>
                <div class="form-group">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Nomor Rekening</label>
                    <input type="text" name="bank2_acc" placeholder="Contoh: 0987654321" value="<?= $wedding['bank2_acc'] ?? '' ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                </div>
            </div>

            <!-- ── GALLERY LINKS ── -->
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm">
                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Galeri Foto Eksternal
                </h3>
                <div class="form-group">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Tautan Gambar <small class="font-normal text-slate-400">(Satu tautan per baris)</small></label>
                    <textarea name="gallery_urls" rows="4" placeholder="Contoh: https://example.com/photo1.jpg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors"><?php 
                        if (isset($id)) {
                            $gallery = Models\Wedding::getGalleryUrls($id);
                            $extUrls = array_filter($gallery, fn($u) => str_starts_with($u, 'http'));
                            echo implode("\n", $extUrls);
                        }
                    ?></textarea>
                    <small class="text-slate-400 text-[11px] mt-1.5 block">Anda juga dapat mengunggah file foto secara langsung melalui tab "Galeri" di panel detail setelah undangan dibuat.</small>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors py-2.5 px-6 rounded-lg shadow-sm">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Undangan
                </button>
                <a href="<?= isset($wedding) ? '../../admin' : '../admin' ?>" class="inline-flex items-center justify-center text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors py-2.5 px-6 rounded-lg">Batal</a>
            </div>
        </form>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slugInput = document.getElementById('slug-input');
        const slugStatus = document.getElementById('slug-status');
        const previewText = document.getElementById('slug-preview-text');
        const weddingId = <?= (int)($id ?? 0) ?>;
        const baseUrl = '<?= rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/') . '/' ?>';
        
        let debounceTimer;

        slugInput.addEventListener('input', function() {
            const rawVal = this.value.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9\-]/g, '');
            
            previewText.textContent = rawVal;

            if (rawVal.length === 0) {
                slugStatus.textContent = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetch(`${baseUrl}api/check-slug?slug=${rawVal}&exclude=${weddingId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            slugStatus.textContent = '✓ ' + data.message;
                            slugStatus.style.color = '#10b981';
                        } else {
                            slugStatus.textContent = '✗ ' + data.message;
                            slugStatus.style.color = '#ef4444';
                        }
                    })
                    .catch(err => console.error('Error:', err));
            }, 500);
        });
    });
    </script>
</body>
</html>
