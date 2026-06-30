<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'slide-up': 'slideUp 0.18s cubic-bezier(0.16, 1, 0.3, 1)',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(8px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">
    <!-- ── TOPBAR ── -->
    <header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="flex items-center gap-2 text-lg font-bold tracking-tight">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"></path></svg>
                <span>Palovite</span>
                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-800 text-slate-300 rounded border border-slate-700 ml-1"><?= $_SESSION['role'] === 'admin' ? 'Admin' : 'Client' ?></span>
            </h1>
            <div class="flex items-center gap-3">
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/users" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Manage Users
                    </a>
                    <a href="admin/create" class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors py-1.5 px-4 rounded shadow">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Buat Undangan
                    </a>
                <?php endif; ?>
                <a href="<?= $GLOBALS['base'] ?>logout" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800 border border-slate-700">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <!-- ── STATS ROW ── -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-lg p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="8"></circle><circle cx="12" cy="12" r="4"></circle></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900"><?= count($weddings) ?></div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Undangan</div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900"><?= count(array_filter($weddings, fn($w) => $w['is_enabled'])) ?></div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Undangan Aktif</div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900"><?= $_SERVER['HTTP_HOST'] ?></div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Server</div>
                </div>
            </div>
        </div>

        <!-- ── WEDDING LIST ── -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daftar Undangan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($weddings)): ?>
            <div class="col-span-full bg-white rounded-lg border-2 border-dashed border-slate-300 py-16 px-4 text-center">
                <div class="text-slate-400 mb-4 flex justify-center">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-1">Belum ada undangan</h3>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <p class="text-sm text-slate-500 mb-6">Buat undangan digital pertama Anda sekarang!</p>
                    <a href="admin/create" class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors py-2.5 px-6 rounded shadow">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Buat Undangan Pertama
                    </a>
                <?php else: ?>
                    <p class="text-sm text-slate-500">Anda belum memiliki undangan yang terhubung dengan akun ini. Silakan hubungi admin.</p>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <?php foreach ($weddings as $w): ?>
            <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between h-[230px]">
                <div>
                    <div class="flex items-start justify-between mb-3">
                        <div class="text-blue-600">
                            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"></path></svg>
                        </div>
                        <span class="inline-flex text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full <?= $w['is_enabled'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' ?>">
                            <?= $w['is_enabled'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 truncate mb-1"><?= htmlspecialchars($w['title']) ?></h3>
                    <a href="<?= $w['slug'] ?>" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline block mb-3 truncate">
                        <?= $_SERVER['HTTP_HOST'] ?>/<?= htmlspecialchars($w['slug']) ?>
                    </a>
                    <div class="text-[11px] font-semibold text-slate-500">
                        Periode: <?= $w['start_date'] ?> - <?= $w['end_date'] ?>
                        &middot; Tema: <?= htmlspecialchars($w['theme_path']) ?>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <div class="relative flex-1">
                        <button type="button" onclick="toggleManageDropdown(event, 'dropdown-<?= $w['id'] ?>')" class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors py-2 px-4 rounded-lg shadow-sm">
                            <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            Kelola
                        </button>
                        <div id="dropdown-<?= $w['id'] ?>" class="manage-dropdown-menu hidden absolute bottom-[calc(100%+8px)] left-0 w-full bg-white rounded-lg shadow-xl border border-slate-200 z-10 overflow-hidden animate-slide-up">
                            <a href="<?= $GLOBALS['base'] ?>admin/detail/<?= $w['id'] ?>/settings" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors border-b border-slate-100">
                                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                Settings
                            </a>
                            <a href="<?= $GLOBALS['base'] ?>admin/detail/<?= $w['id'] ?>/profile" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors border-b border-slate-100">
                                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                Content Data
                            </a>
                            <a href="<?= $GLOBALS['base'] ?>admin/detail/<?= $w['id'] ?>/appearance" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors border-b border-slate-100">
                                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                Layout
                            </a>
                            <a href="<?= $GLOBALS['base'] ?>admin/detail/<?= $w['id'] ?>/guests" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                Interaksi
                            </a>
                        </div>
                    </div>
                    <a href="<?= $GLOBALS['base'] ?>preview/<?= $w['slug'] ?>" target="_blank" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors py-2 px-3.5 rounded-lg shadow-sm">
                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        Preview
                    </a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/delete/<?= $w['id'] ?>" class="inline-flex items-center justify-center text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors py-2 px-2.5 rounded-lg"
                       onclick="return confirm('Hapus undangan <?= htmlspecialchars(addslashes($w['title'])) ?>? Semua data akan dihapus.')">
                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
    function toggleManageDropdown(event, id) {
        event.stopPropagation();
        const dropdowns = document.querySelectorAll('.manage-dropdown-menu');
        dropdowns.forEach(d => {
            if (d.id !== id) {
                d.classList.add('hidden');
            }
        });
        
        const current = document.getElementById(id);
        current.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.manage-dropdown-menu');
        dropdowns.forEach(d => {
            d.classList.add('hidden');
        });
    });
    </script>
</body>
</html>
