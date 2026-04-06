<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of <?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #c5a059;
            --dark: #1a1a1a;
            --white: #ffffff;
            --soft: #fcf9f5;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: var(--soft);
            color: var(--dark);
            text-align: center;
        }
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=2070') no-repeat center center/cover;
            color: white;
            padding: 20px;
        }
        .hero h2 { font-family: 'Alex Brush', cursive; font-size: 5rem; margin: 0; color: var(--white); }
        .hero p { font-size: 1.2rem; letter-spacing: 4px; text-transform: uppercase; margin-top: 10px; }
        
        section { padding: 80px 20px; max-width: 800px; margin: auto; }
        h3 { font-family: 'Alex Brush', cursive; font-size: 3.5rem; color: var(--gold); margin-bottom: 30px; }
        
        .couple { display: flex; justify-content: space-around; align-items: center; margin-top: 40px; flex-wrap: wrap; }
        .person { flex: 1; min-width: 300px; padding: 20px; }
        .person h4 { font-size: 1.8rem; margin: 10px 0; }
        .person p { color: #666; line-height: 1.6; }
        
        .event-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border: 1px solid #eee;
        }
        .event-card h4 { font-size: 1.5rem; color: var(--gold); margin-top: 0; letter-spacing: 2px; }
        .event-info { margin: 20px 0; font-size: 1.1rem; }
        .btn-map {
            display: inline-block;
            padding: 10px 25px;
            margin-top: 15px;
            background: var(--gold);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-map:hover { background: var(--dark); }
        
        .footer { padding: 40px; font-size: 0.9rem; color: #999; }
    </style>
</head>
<body>

    <?php 
    $toGuest = isset($_GET['to']) ? htmlspecialchars(strip_tags($_GET['to'])) : '';
    if ($toGuest): ?>
    <div id="guestGreeting" style="
        position:fixed; top:0; left:0; right:0; z-index:9999;
        background:linear-gradient(135deg, rgba(139,92,246,0.97), rgba(236,72,153,0.97));
        color:white; padding:14px 24px;
        display:flex; justify-content:space-between; align-items:center;
        box-shadow:0 4px 20px rgba(0,0,0,0.2); font-family:'Montserrat', sans-serif;">
        <div>
            <div style="font-size:0.7rem; text-transform:uppercase; letter-spacing:2px; opacity:0.85;">Undangan Spesial Untuk</div>
            <div style="font-size:1.1rem; font-weight:700; letter-spacing:0.5px;">💌 <?= $toGuest ?></div>
        </div>
        <button onclick="document.getElementById('guestGreeting').style.display='none'"
            style="background:rgba(255,255,255,0.2); border:none; color:white; border-radius:8px; padding:6px 12px; cursor:pointer; font-size:0.8rem;">
            ✕ Tutup
        </button>
    </div>
    <div style="height:56px;"></div>
    <?php endif; ?>

    <div class="hero">
        <p>The Wedding of</p>
        <h2><?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></h2>
        <p><?= $wedding['akad_date'] ?></p>
    </div>

    <section id="couple">
        <h3>The Couple</h3>
        <div class="couple">
            <div class="person">
                <h4><?= $wedding['bride_name'] ?></h4>
                <p>Putri <?= $wedding['bride_order'] ?> dari pasangan<br><b>Bapak <?= $wedding['bride_father'] ?> & Ibu <?= $wedding['bride_mother'] ?></b></p>
            </div>
            <div style="font-family: 'Alex Brush', cursive; font-size: 3rem; color: #ddd;">&</div>
            <div class="person">
                <h4><?= $wedding['groom_name'] ?></h4>
                <p>Putra <?= $wedding['groom_order'] ?> dari pasangan<br><b>Bapak <?= $wedding['groom_father'] ?> & Ibu <?= $wedding['groom_mother'] ?></b></p>
            </div>
        </div>
    </section>

    <section id="events" style="background-color: #fffefb;">
        <h3>Save the Date</h3>
        
        <div class="event-card">
            <h4>AKAD NIKAH</h4>
            <div class="event-info">
                📅 <?= $wedding['akad_date'] ?><br>
                ⏰ <?= $wedding['akad_time'] ?><br>
                📍 <?= $wedding['akad_place'] ?>
            </div>
        </div>

        <div class="event-card">
            <h4>RESEPSI</h4>
            <div class="event-info">
                📅 <?= $wedding['reception_date'] ?><br>
                ⏰ <?= $wedding['reception_time'] ?><br>
                📍 <?= $wedding['reception_place'] ?>
            </div>
            <?php if (!empty($wedding['reception_map_link'])): ?>
                <a href="<?= $wedding['reception_map_link'] ?>" target="_blank" class="btn-map">View on Google Maps</a>
            <?php endif; ?>
        </div>
    </section>

    <section id="gallery">
        <h3>Our Gallery</h3>
        <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; margin-top: 30px;">
            <?php 
            $gallery = Models\Wedding::getGallery($wedding['id']);
            foreach ($gallery as $img): ?>
                <div style="aspect-ratio: 1/1; overflow: hidden; border-radius: 15px;">
                    <img src="<?= $img ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="gifts" style="background-color: #fcf9f5;">
        <h3>Wedding Gift</h3>
        <p style="margin-bottom: 40px;">Bagi keluarga dan kerabat yang ingin mengirimkan hadiah, dapat melalui nomor rekening berikut:</p>
        
        <div class="couple" style="align-items: flex-start; justify-content: center; gap: 30px;">
            <?php if (!empty($wedding['bank1_acc'])): ?>
            <div class="event-card" style="flex: 1; min-width: 240px; max-width: 320px; text-align: center;">
                <p style="font-weight: 700; font-size: 1rem; margin: 0 0 6px; color: var(--dark); letter-spacing: 0.5px;"><?= $wedding['bank1_owner'] ?></p>
                <p style="color: var(--gold); font-weight: 600; margin: 0 0 4px; font-size: 0.9rem;"><?= $wedding['bank1_name'] ?></p>
                <p style="font-size: 1.3rem; font-weight: 700; letter-spacing: 2px; margin: 0 0 16px; color: var(--dark);"><?= $wedding['bank1_acc'] ?></p>
                <button onclick="copyToClipboard('<?= $wedding['bank1_acc'] ?>', this)" class="btn-map" style="border: none; cursor: pointer; font-size: 0.85rem;">Salin Rekening</button>
            </div>
            <?php endif; ?>

            <?php if (!empty($wedding['bank2_acc'])): ?>
            <div class="event-card" style="flex: 1; min-width: 240px; max-width: 320px; text-align: center;">
                <p style="font-weight: 700; font-size: 1rem; margin: 0 0 6px; color: var(--dark); letter-spacing: 0.5px;"><?= $wedding['bank2_owner'] ?></p>
                <p style="color: var(--gold); font-weight: 600; margin: 0 0 4px; font-size: 0.9rem;"><?= $wedding['bank2_name'] ?></p>
                <p style="font-size: 1.3rem; font-weight: 700; letter-spacing: 2px; margin: 0 0 16px; color: var(--dark);"><?= $wedding['bank2_acc'] ?></p>
                <button onclick="copyToClipboard('<?= $wedding['bank2_acc'] ?>', this)" class="btn-map" style="border: none; cursor: pointer; font-size: 0.85rem;">Salin Rekening</button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section id="guestbook">
        <h3>Guestbook & RSVP</h3>

        <?php 
        $msg    = $_GET['msg'] ?? '';
        $reason = $_GET['reason'] ?? '';
        $errorTexts = [
            'ratelimit' => 'Kamu sudah mengirim pesan. Tunggu 5 menit sebelum mengirim lagi.',
            'name'      => 'Nama tidak valid. Minimal 2 karakter.',
            'email'     => 'Alamat email tidak valid.',
            'message'   => 'Pesan terlalu pendek (min 5 karakter) atau terlalu panjang (maks 500).',
        ];
        ?>

        <?php if ($msg === 'sent'): ?>
        <div style="background:#dcfce7; color:#166534; padding:14px 20px; border-radius:10px; max-width:600px; margin:0 auto 20px; font-weight:600; font-size:0.9rem;">
            ✅ Ucapan kamu berhasil terkirim! Terima kasih 💐
        </div>
        <?php elseif ($msg === 'error'): ?>
        <div style="background:#fee2e2; color:#991b1b; padding:14px 20px; border-radius:10px; max-width:600px; margin:0 auto 20px; font-weight:600; font-size:0.9rem;">
            ⚠️ <?= $errorTexts[$reason] ?? 'Terjadi kesalahan. Coba lagi.' ?>
        </div>
        <?php endif; ?>

        <div class="event-card" style="text-align: left; max-width: 600px; margin: auto;">
            <form action="<?= $base ?>api/guestbook" method="POST" id="guestbookForm">
                <input type="hidden" name="wedding_id" value="<?= $wedding['id'] ?>">
                <input type="hidden" name="slug" value="<?= $wedding['slug'] ?>">
                
                <!-- Honeypot: hidden from humans, bots fill this -->
                <div style="position:absolute; left:-9999px; top:-9999px;" aria-hidden="true">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                    <div>
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.85rem;">Nama Tamu *</label>
                        <input type="text" name="name" required minlength="2" maxlength="100"
                            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; font-family:inherit;">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.85rem;">Email * <small style="color:#999; font-weight:400;">(untuk avatar, tidak ditampilkan)</small></label>
                        <input type="email" name="email" required
                            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; font-family:inherit;">
                    </div>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.85rem;">Konfirmasi Kehadiran</label>
                    <div style="display:flex; gap:10px;">
                        <label style="flex:1; padding:10px; border:2px solid #ddd; border-radius:8px; display:flex; align-items:center; gap:8px; cursor:pointer; transition:0.2s;" id="lbl-hadir">
                            <input type="radio" name="attendance" value="1" checked onchange="highlightRsvp()"> 
                            <span>✅ Akan Hadir</span>
                        </label>
                        <label style="flex:1; padding:10px; border:2px solid #ddd; border-radius:8px; display:flex; align-items:center; gap:8px; cursor:pointer; transition:0.2s;" id="lbl-tidak">
                            <input type="radio" name="attendance" value="0" onchange="highlightRsvp()">
                            <span>❌ Tidak Dapat Hadir</span>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:0.85rem;">Pesan / Doa Restu * <small id="charCount" style="color:#999; font-weight:400;">(0/500)</small></label>
                    <textarea name="message" required minlength="5" maxlength="500" id="msgArea"
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; height:100px; box-sizing:border-box; font-family:inherit; resize:vertical;"></textarea>
                </div>

                <button type="submit" class="btn-map" style="width:100%; border:none; cursor:pointer; font-size:1rem; padding:14px;">
                    💌 Kirim Ucapan
                </button>
            </form>
        </div>

        <!-- Comment List -->
        <div style="margin-top:50px; max-width:600px; margin-left:auto; margin-right:auto;">
            <?php 
            $messages = Models\Wedding::getMessages($wedding['id']);
            $total    = count($messages);
            if ($total === 0): ?>
                <p style="text-align:center; color:#999;">Belum ada ucapan. Jadilah yang pertama! 🌸</p>
            <?php else: ?>
                <p style="text-align:center; color:#aaa; font-size:0.82rem; margin-bottom:20px;">
                    Menampilkan <span id="visibleCount">0</span> dari <?= $total ?> ucapan
                </p>

                <div id="commentList">
                <?php foreach ($messages as $i => $m): 
                    $avatar  = Models\Wedding::gravatarUrl($m['email'] ?? '', 60);
                    $isHadir = (bool)$m['attendance'];
                ?>
                <div class="comment-item" data-index="<?= $i ?>"
                     style="display:none; flex-direction:row; gap:15px; background:white; padding:18px;
                            border-radius:14px; margin-bottom:14px;
                            box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #f0f0f0;
                            align-items:flex-start;">
                    <img src="<?= $avatar ?>" alt="avatar" width="50" height="50"
                         style="border-radius:50%; flex-shrink:0;
                                border:2px solid <?= $isHadir ? '#bbf7d0' : '#fecaca' ?>;">
                    <div style="flex:1; text-align:left;">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:5px;">
                            <strong style="font-size:0.95rem;"><?= $m['name'] ?></strong>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <span style="font-size:0.7rem; padding:3px 8px; border-radius:20px; font-weight:700;
                                    background:<?= $isHadir ? '#dcfce7' : '#fee2e2' ?>;
                                    color:<?= $isHadir ? '#166534' : '#991b1b' ?>;">
                                    <?= $isHadir ? '✅ Hadir' : '❌ Tidak Hadir' ?>
                                </span>
                                <small style="color:#bbb; font-size:0.75rem;"><?= date('d M Y', strtotime($m['created_at'])) ?></small>
                            </div>
                        </div>
                        <p style="margin:6px 0 0; font-size:0.88rem; color:#555; line-height:1.5;"><?= $m['message'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>

                <!-- Show More Button -->
                <div style="text-align:center; margin-top:10px;">
                    <button id="showMoreBtn" onclick="showMoreComments()"
                        style="background:transparent; border:2px solid var(--gold); color:var(--gold);
                               padding:10px 30px; border-radius:30px; font-weight:700; cursor:pointer;
                               font-family:inherit; font-size:0.9rem; transition:0.3s; display:none;"
                        onmouseover="this.style.background='var(--gold)'; this.style.color='white';"
                        onmouseout="this.style.background='transparent'; this.style.color='var(--gold)';">
                        Tampilkan Lainnya
                    </button>
                    <p id="allShownMsg" style="color:#bbb; font-size:0.82rem; display:none;">
                        Semua ucapan sudah ditampilkan 🌸
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="footer">
        <p>Made with Love - Wedding Manager</p>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const original = btn.textContent;
                btn.textContent = 'Tersalin ✓';
                btn.style.background = '#166534';
                setTimeout(() => {
                    btn.textContent = original;
                    btn.style.background = '';
                }, 2000);
            });
        }
        
        // ---- Show More Comments ----
        const PER_PAGE   = 5;
        let shownCount   = 0;
        const items      = document.querySelectorAll('.comment-item');
        const btn        = document.getElementById('showMoreBtn');
        const allMsg     = document.getElementById('allShownMsg');
        const countLabel = document.getElementById('visibleCount');

        function showMoreComments() {
            const next = Math.min(shownCount + PER_PAGE, items.length);
            for (let i = shownCount; i < next; i++) {
                items[i].style.display = 'flex';
            }
            shownCount = next;
            if (countLabel) countLabel.textContent = shownCount;

            if (shownCount >= items.length) {
                btn.style.display = 'none';
                if (allMsg) allMsg.style.display = 'block';
            } else {
                btn.style.display = 'inline-block';
            }
        }

        // Initial render — show first 5
        if (items.length > 0) showMoreComments();


        function highlightRsvp() {
            const hadir = document.querySelector('input[name=attendance][value="1"]').checked;
            document.getElementById('lbl-hadir').style.borderColor = hadir ? '#166534' : '#ddd';
            document.getElementById('lbl-tidak').style.borderColor = hadir ? '#ddd' : '#ef4444';
        }
        highlightRsvp();

        // Character counter
        const msgArea = document.getElementById('msgArea');
        const charCount = document.getElementById('charCount');
        if (msgArea) {
            msgArea.addEventListener('input', () => {
                charCount.textContent = `(${msgArea.value.length}/500)`;
            });
        }

        // Auto-scroll to guestbook on msg
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('msg')) {
            const gb = document.getElementById('guestbook');
            if (gb) gb.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
