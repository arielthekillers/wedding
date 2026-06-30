<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Palovite</title>
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
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen flex items-center justify-center relative overflow-hidden px-4">
    <!-- Decorative Blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-[350px] h-[350px] bg-blue-600/20 blur-[100px] rounded-full z-0"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[450px] h-[450px] bg-slate-800/40 blur-[100px] rounded-full z-0"></div>

    <div class="w-full max-w-[400px] z-10">
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-8 backdrop-blur-xl shadow-2xl text-center">
            <div class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent mb-1">Palovite</div>
            <div class="text-xs font-semibold text-slate-400 mb-8 tracking-wide">Masuk ke Dashboard Panel</div>

            <?php if (!empty($error)): ?>
                <div class="mb-5 p-3.5 rounded-lg bg-rose-500/10 text-rose-300 border border-rose-500/20 text-xs font-semibold text-left">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= $base ?>login" method="POST" class="space-y-4 text-left">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all placeholder-slate-600" placeholder="Masukkan username" required autocomplete="username">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all placeholder-slate-600" placeholder="Masukkan password" required autocomplete="current-password">
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-600/10 mt-2">
                    Sign In
                </button>
            </form>
            
            <a href="<?= $base ?>" class="inline-block mt-8 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
