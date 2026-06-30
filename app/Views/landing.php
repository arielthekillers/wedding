<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palovite - Premium Digital Wedding Invitations</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen relative overflow-x-hidden">
    <!-- Decorative background blobs -->
    <div class="absolute top-[20%] left-[5%] w-[350px] h-[350px] bg-blue-600/10 blur-[100px] rounded-full z-0"></div>
    <div class="absolute bottom-[20%] right-[5%] w-[450px] h-[450px] bg-slate-800/30 blur-[120px] rounded-full z-0"></div>

    <!-- ── NAVBAR ── -->
    <header class="fixed top-0 left-0 w-full px-6 md:px-12 h-20 flex items-center justify-between z-50 bg-slate-950/80 backdrop-blur-xl border-b border-slate-900">
        <div class="logo text-2xl font-extrabold tracking-tight bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent">Palovite</div>
        <a href="<?= $base ?>login" class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all py-2 px-5 rounded-full shadow-lg shadow-blue-600/15 hover:scale-[1.02]">
            Login Client
        </a>
    </header>

    <!-- ── HERO SECTION ── -->
    <section class="min-h-screen flex flex-col justify-center items-center text-center px-4 pt-20 z-10 relative">
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 max-w-4xl tracking-tight">
            Momen Spesial,<br>
            <span class="bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent">Lebih Mengesankan</span>
        </h1>
        <p class="text-base md:text-lg text-slate-400 max-w-2xl mb-10 leading-relaxed font-light">
            Buat undangan pernikahan digital interaktif, elegan, dan mudah dikelola dengan Palovite. Bagikan momen bahagiamu ke seluruh dunia hanya dengan satu klik.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="<?= $base ?>login" class="inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all py-3.5 px-8 rounded-full shadow-lg shadow-blue-600/20 hover:scale-[1.02]">
                Mulai Sekarang
            </a>
            <a href="#features" class="inline-flex items-center justify-center gap-2 text-sm font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all py-3.5 px-8 rounded-full hover:scale-[1.02]">
                Pelajari Fitur
            </a>
        </div>
    </section>

    <!-- ── FEATURES SECTION ── -->
    <section id="features" class="max-w-6xl mx-auto px-6 py-24 relative z-10">
        <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-16 tracking-tight">Kenapa Memilih Palovite?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.7255 3.09032 17.1962 4.85857 19C5.3215 19.4629 5.25368 20.2443 4.90885 20.7303L4.85857 20.8012C4.30135 21.5878 4.79679 22 5.75936 22H12Z"></path><circle cx="7.5" cy="10.5" r="1.5" fill="currentColor"></circle><circle cx="11.5" cy="7.5" r="1.5" fill="currentColor"></circle><circle cx="16.5" cy="9.5" r="1.5" fill="currentColor"></circle><circle cx="15.5" cy="14.5" r="1.5" fill="currentColor"></circle></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Desain Premium</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Pilihan tema eksklusif yang dirancang khusus untuk membuat undangan pernikahan Anda terlihat mewah dan modern.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"></path></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Interaktif & Animasi</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Dilengkapi dengan efek visual memukau, musik latar, dan galeri foto untuk pengalaman tamu yang tak terlupakan.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Responsif Penuh</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Undangan Anda akan tampil sempurna di semua perangkat, baik smartphone, tablet, maupun komputer desktop.</p>
            </div>
            <!-- Feature 4 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Manajemen Tamu</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Kelola daftar tamu, RSVP, dan pesan buku tamu secara real-time langsung dari dashboard pintar Anda.</p>
            </div>
            <!-- Feature 5 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Akses Privat</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Dapatkan kontrol penuh. Setiap klien memiliki akun login khusus untuk memperbarui detail acara kapan saja.</p>
            </div>
            <!-- Feature 6 -->
            <div class="bg-slate-900/50 border border-slate-850 p-8 rounded-2xl backdrop-blur-md hover:border-blue-600/40 transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-950/50 border border-blue-900/30 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                </div>
                <h3 class="text-lg font-bold mb-3 tracking-tight">Cepat & Aman</h3>
                <p class="text-slate-400 text-sm font-light leading-relaxed">Infrastruktur cloud yang menjamin undangan Anda dapat diakses dengan cepat tanpa gangguan, dijamin aman.</p>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer class="text-center py-10 text-xs text-slate-500 border-t border-slate-900 relative z-10">
        <p>&copy; <?= date('Y') ?> Palovite. All rights reserved.</p>
    </footer>
</body>
</html>
