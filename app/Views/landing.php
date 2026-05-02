<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palovite - Premium Digital Wedding Invitations</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --secondary: #ec4899;
            --bg-color: #0f172a;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --card-bg: rgba(30, 41, 59, 0.7);
            --gradient: linear-gradient(135deg, var(--primary), var(--secondary));
            --glow: 0 0 20px rgba(139, 92, 246, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Nav */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .btn-login {
            background: var(--gradient);
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--glow);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(236, 72, 153, 0.6);
        }

        /* Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }

        /* Decorative background blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }
        .blob-1 { top: 20%; left: 10%; width: 300px; height: 300px; background: var(--primary); border-radius: 50%; }
        .blob-2 { bottom: 20%; right: 10%; width: 400px; height: 400px; background: var(--secondary); border-radius: 50%; }

        .hero h1 {
            font-size: 4.5rem;
            line-height: 1.1;
            margin-bottom: 24px;
            font-weight: 800;
        }

        .hero h1 span {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 40px;
            font-weight: 300;
            line-height: 1.6;
        }

        .hero-btns {
            display: flex;
            gap: 16px;
        }

        .btn-primary {
            background: var(--text-main);
            color: var(--bg-color);
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        /* Features */
        .features {
            padding: 100px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
            font-weight: 700;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.05);
            padding: 40px 30px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(139, 92, 246, 0.4);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            display: inline-block;
        }

        .feature-title {
            font-size: 1.4rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .feature-desc {
            color: var(--text-muted);
            line-height: 1.6;
            font-weight: 300;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 3rem; }
            header { padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <header>
        <div class="logo">Palovite</div>
        <a href="<?= $base ?>login" class="btn-login">Login Client</a>
    </header>

    <section class="hero">
        <h1>Momen Spesial,<br><span>Lebih Mengesankan</span></h1>
        <p>Buat undangan pernikahan digital interaktif, elegan, dan mudah dikelola dengan Palovite. Bagikan momen bahagiamu ke seluruh dunia hanya dengan satu klik.</p>
        <div class="hero-btns">
            <a href="<?= $base ?>login" class="btn-login">Mulai Sekarang</a>
            <a href="#features" class="btn-primary">Pelajari Fitur</a>
        </div>
    </section>

    <section id="features" class="features">
        <h2 class="section-title">Kenapa Memilih Palovite?</h2>
        <div class="grid">
            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3 class="feature-title">Desain Premium</h3>
                <p class="feature-desc">Pilihan tema eksklusif yang dirancang khusus untuk membuat undangan pernikahan Anda terlihat mewah dan modern.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✨</div>
                <h3 class="feature-title">Interaktif & Animasi</h3>
                <p class="feature-desc">Dilengkapi dengan efek visual memukau, musik latar, dan galeri foto untuk pengalaman tamu yang tak terlupakan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3 class="feature-title">Responsif Penuh</h3>
                <p class="feature-desc">Undangan Anda akan tampil sempurna di semua perangkat, baik smartphone, tablet, maupun komputer desktop.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3 class="feature-title">Manajemen Tamu</h3>
                <p class="feature-desc">Kelola daftar tamu, RSVP, dan pesan buku tamu secara real-time langsung dari dashboard pintar Anda.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Akses Privat</h3>
                <p class="feature-desc">Dapatkan kontrol penuh. Setiap klien memiliki akun login khusus untuk memperbarui detail acara kapan saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3 class="feature-title">Cepat & Aman</h3>
                <p class="feature-desc">Infrastruktur cloud yang menjamin undangan Anda dapat diakses dengan cepat tanpa gangguan, dijamin aman.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> Palovite. All rights reserved.</p>
    </footer>

</body>
</html>
