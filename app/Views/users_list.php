<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Palovite</title>
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
                <a href="<?= $GLOBALS['base'] ?>admin" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800 border border-slate-700">
                    <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Dashboard
                </a>
                <a href="<?= $GLOBALS['base'] ?>logout" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-300 hover:text-white transition-colors py-1.5 px-3 rounded hover:bg-slate-800 border border-slate-700">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Manajemen User (Klien)</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-semibold">Berhasil menyimpan data user!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="mb-6 p-4 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 text-sm font-semibold"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Create User Form -->
        <div class="bg-white rounded-lg p-6 border border-slate-200 shadow-sm mb-8">
            <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Tambah Klien Baru</h3>
            <form action="<?= $GLOBALS['base'] ?>admin/users" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Username</label>
                        <input type="text" name="username" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" required placeholder="Hanya huruf dan angka">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Password</label>
                        <input type="text" name="password" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors" placeholder="Kosongkan jika sama dengan username">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600 bg-slate-50 focus:bg-white transition-colors">
                            <option value="client">Client</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors py-2 px-4 rounded-lg shadow-sm">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-500">#<?= $u['id'] ?></td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?= htmlspecialchars($u['username']) ?></td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full <?= $u['role'] === 'admin' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700' ?>">
                                <?= $u['role'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= date('d M Y H:i', strtotime($u['created_at'])) ?></td>
                        <td class="px-6 py-4 text-sm text-right">
                            <?php if ($u['username'] !== 'admin' && $u['id'] !== $_SESSION['user_id']): ?>
                                <a href="<?= $GLOBALS['base'] ?>admin/users?action=delete&id=<?= $u['id'] ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors bg-rose-50 hover:bg-rose-100 py-1.5 px-3 rounded-lg" onclick="return confirm('Hapus user ini? Wedding yang terkait dengan user ini akan di-set menjadi tanpa pemilik.')">
                                    <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    Hapus
                                </a>
                            <?php else: ?>
                                <span class="text-slate-300 text-xs font-semibold">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
