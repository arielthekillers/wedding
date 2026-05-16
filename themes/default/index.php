<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of <?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
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
        .hero h2 { 
            font-family: 'Alex Brush', cursive; 
            font-size: clamp(2.5rem, 14vw, 6.5rem); 
            margin: 15px 0; 
            color: var(--white); 
            line-height: 1;
            text-shadow: 2px 2px 15px rgba(0,0,0,0.4);
            white-space: nowrap;
        }
        .hero p { font-size: clamp(0.8rem, 4vw, 1.2rem); letter-spacing: 4px; text-transform: uppercase; margin: 10px 0; }
        
        section { 
            padding: 40px 20px 80px; 
            max-width: 800px; 
            margin: auto; 
            scroll-margin-top: 40px; 
        }
        h3 { font-family: 'Alex Brush', cursive; font-size: 3.5rem; color: var(--gold); margin-top: 0; margin-bottom: 30px; }
        
        .couple { display: flex; justify-content: space-around; align-items: center; margin-top: 40px; flex-wrap: wrap; gap: 20px; }
        .person { flex: 1; min-width: 300px; padding: 20px; }
        .person-img {
            width: 220px;
            height: 280px;
            border-radius: 120px 120px 20px 20px; /* Elegant premium arch shape */
            object-fit: cover;
            margin-bottom: 25px;
            border: 8px solid white;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
            outline: 1px solid var(--gold);
            outline-offset: 6px;
            transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #f8f8f8;
        }
        .person:hover .person-img {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(197, 160, 89, 0.15);
            outline-offset: 12px;
        }
        .person h4 { 
            font-family: 'Playfair Display', serif;
            font-size: 2rem; 
            margin: 15px 0 10px; 
            color: var(--dark);
            font-weight: 700;
        }
        .person p { color: #666; line-height: 1.7; font-size: 0.95rem; }
        
        .event-wrapper {
            max-width: 900px;
            margin: 60px auto 0;
            display: flex;
            flex-direction: column;
            gap: 100px;
        }
        .event-item-block { text-align: center; position: relative; }
        .event-item-block h4 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 40px;
            font-weight: 700;
        }
        .event-item-block h4::after {
            content: ''; display: block; width: 50px; height: 1px;
            background: var(--gold); margin: 20px auto 0;
        }
        .event-details-flex {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 60px;
            flex-wrap: wrap;
            margin-top: 50px;
            text-align: left;
        }
        .event-col {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            max-width: 400px;
        }
        .detail-box {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .detail-box i {
            display: flex;
            width: 50px;
            height: 50px;
            background: #fffaf0;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            border: 1px solid #fdf2d8;
            flex-shrink: 0;
            margin: 0;
        }
        .detail-info {
            flex: 1;
        }
        .detail-box b {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .detail-box span {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--dark);
            line-height: 1.6;
            font-weight: 500;
        }
        .btn-map {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 60px;
            padding: 14px 32px;
            background: var(--dark);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s;
            border: 1px solid var(--dark);
        }
        .btn-map:hover {
            background: transparent;
            color: var(--dark);
            transform: translateY(-5px);
        }
        .btn-map {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 30px;
            margin-top: 35px;
            background: var(--gold);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.25);
        }
        .btn-map:hover { 
            background: var(--dark); 
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
            color: white;
        }
        
        .footer { padding: 40px; font-size: 0.9rem; color: #999; }

        /* Opening Overlay Styles */
        #openingOverlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&q=80&w=2069') no-repeat center center/cover;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            transition: opacity 1s ease, visibility 1s;
        }
        #openingOverlay.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        .overlay-content h1 { font-family: 'Alex Brush', cursive; font-size: 4.5rem; margin: 0; text-shadow: 2px 2px 10px rgba(0,0,0,0.3); }
        .overlay-content p { font-size: 1.1rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 30px; }
        .btn-open {
            background: var(--gold);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: pulse-gold 2s infinite;
        }
        @keyframes pulse-gold {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 89, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(197, 160, 89, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 89, 0); }
        }
        body.locked { overflow: hidden; }

        /* Floating Navigation Bar */
        .floating-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 10px 20px;
            border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 9998;
            display: flex;
            gap: 15px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            align-items: center;
        }
        .nav-item {
            color: #64748b;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: 0.3s;
            font-size: 1.2rem;
        }
        .nav-item:hover, .nav-item.active {
            background: var(--gold);
            color: white;
            transform: translateY(-5px);
        }
        .nav-item i { 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-item svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        @media (max-width: 480px) {
            .floating-nav { gap: 10px; padding: 8px 15px; width: 90%; justify-content: center; }
            .nav-item { width: 35px; height: 35px; font-size: 1.1rem; }
        }

        /* Reveal Animations System */
        [data-reveal] {
            opacity: 0;
            transition: all 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: transform, opacity;
        }
        [data-reveal="up"]    { transform: translateY(50px); }
        [data-reveal="down"]  { transform: translateY(-50px); }
        [data-reveal="left"]  { transform: translateX(50px); }
        [data-reveal="right"] { transform: translateX(-50px); }
        [data-reveal="zoom"]  { transform: scale(0.9); }
        
        [data-reveal].active {
            opacity: 1;
            transform: translate(0) scale(1);
        }

        /* Countdown Styles */
        .countdown-container {
            display: flex;
            gap: 12px;
            margin-top: 40px;
            justify-content: center;
            width: 100%;
            max-width: 500px;
            flex-wrap: nowrap;
        }
        .countdown-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 5px;
            border-radius: 18px;
            flex: 1;
            max-width: 90px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .countdown-item span {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
        }
        .countdown-item label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 4px;
            font-weight: 600;
        }
        @media (max-width: 480px) {
            .hero h2 { font-size: clamp(1.5rem, 11vw, 2.8rem); }
            .countdown-container { gap: 6px; padding: 0 10px; }
            .countdown-item { padding: 10px 2px; border-radius: 10px; max-width: 65px; }
            .countdown-item span { font-size: 1.1rem; }
            .countdown-item label { font-size: 0.5rem; letter-spacing: 0.5px; margin-top: 2px; }
        }
    </style>
</head>
<body class="locked">

    <!-- Opening Overlay -->
    <div id="openingOverlay">
        <div class="overlay-content">
            <p>The Wedding of</p>
            <h1><?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></h1>
            <div style="margin-top: 20px; font-size: 0.9rem; opacity: 0.9;">
                Kepada Yth. Bapak/Ibu/Saudara/i:<br>
                <strong style="font-size: 1.2rem; display: block; margin-top: 5px;"><?= isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan' ?></strong>
            </div>
            <button class="btn-open" onclick="bukaUndangan()" style="margin: 40px auto 0;">
                <span>💌 Buka Undangan</span>
            </button>
        </div>
    </div>

    <div id="hero" class="hero">
        <div data-reveal="zoom">
            <p>The Wedding of</p>
            <h2><?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></h2>
            <p><?= $wedding['akad_date'] ?></p>

            <?php if (!empty($wedding['start_date'])): ?>
            <div id="countdown" class="countdown-container">
                <div class="countdown-item">
                    <span id="days">00</span>
                    <label>Hari</label>
                </div>
                <div class="countdown-item">
                    <span id="hours">00</span>
                    <label>Jam</label>
                </div>
                <div class="countdown-item">
                    <span id="minutes">00</span>
                    <label>Menit</label>
                </div>
                <div class="countdown-item">
                    <span id="seconds">00</span>
                    <label>Detik</label>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <section id="couple" data-reveal="up">
        <h3>The Couple</h3>
        <div class="couple">
            <div class="person" data-reveal="left" data-reveal-delay="300">
                <?php if (!empty($wedding['bride_image'])): ?>
                    <img src="<?= $wedding['bride_image'] ?>" class="person-img" alt="Bride">
                <?php endif; ?>
                <h4><?= $wedding['bride_name'] ?></h4>
                <p>Putri <?= $wedding['bride_order'] ?> dari pasangan<br><b>Bapak <?= $wedding['bride_father'] ?> & Ibu <?= $wedding['bride_mother'] ?></b></p>
            </div>
            <div style="font-family: 'Alex Brush', cursive; font-size: 3.5rem; color: #e5e7eb; margin: 20px 0;" data-reveal="zoom" data-reveal-delay="500">&</div>
            <div class="person" data-reveal="right" data-reveal-delay="300">
                <?php if (!empty($wedding['groom_image'])): ?>
                    <img src="<?= $wedding['groom_image'] ?>" class="person-img" alt="Groom">
                <?php endif; ?>
                <h4><?= $wedding['groom_name'] ?></h4>
                <p>Putra <?= $wedding['groom_order'] ?> dari pasangan<br><b>Bapak <?= $wedding['groom_father'] ?> & Ibu <?= $wedding['groom_mother'] ?></b></p>
            </div>
        </div>
    </section>

    <?php 
    $loveStories = Models\Wedding::getLoveStories($wedding['id']);
    if (!empty($loveStories)): ?>
    <section id="love-story" data-reveal="up" style="background-color: #fffefb;">
        <h3>Cerita Cinta</h3>
        <p style="margin-top:-20px; margin-bottom:40px; color:#888; font-size:0.95rem;">
            Banyak cerita yang kami lalui sehingga akhirnya kami bisa bersatu, cerita akan akan menjadi kenangan selalu, dan cerita itu kami bagi untuk anda.
        </p>

        <div style="position:relative; max-width:700px; margin:auto; padding-left:30px;">
            <!-- vertical line -->
            <div style="position:absolute; left:14px; top:0; bottom:0; width:2px; background:linear-gradient(to bottom,#ddd6fe,#fce7f3);"></div>

            <?php foreach ($loveStories as $ls): ?>
            <div style="position:relative; display:flex; gap:18px; margin-bottom:36px; align-items:flex-start;">
                <!-- dot -->
                <div style="position:absolute; left:-24px; top:18px; width:14px; height:14px; border-radius:50%; background:linear-gradient(135deg,#8b5cf6,#ec4899); box-shadow:0 0 0 3px #fce7f3; flex-shrink:0;"></div>

                <?php if (!empty($ls['image_url'])): ?>
                <img src="<?= htmlspecialchars($ls['image_url']) ?>"
                     alt="<?= htmlspecialchars($ls['title']) ?>"
                     style="width:80px; height:80px; border-radius:50%; object-fit:cover; flex-shrink:0; border:3px solid #fce7f3;">
                <?php else: ?>
                <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#ede9fe,#fce7f3); display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0;">💕</div>
                <?php endif; ?>

                <div style="text-align:left; flex:1;">
                    <div style="font-size:0.78rem; color:var(--gold); font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:4px;">
                        📅 <?= date('l, d F Y', strtotime($ls['story_date'])) ?>
                    </div>
                    <h4 style="font-family:'Alex Brush',cursive; font-size:1.7rem; margin:0 0 8px; color:#1a1a1a;"><?= htmlspecialchars($ls['title']) ?></h4>
                    <p style="font-size:0.9rem; color:#666; line-height:1.7; margin:0;"><?= nl2br(htmlspecialchars($ls['content'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <section id="events" data-reveal="up" style="background-color: #fff;">
        <h3 style="font-family: 'Playfair Display', serif; font-weight: 700; font-style: italic;">Save the Date</h3>
        
            <div class="event-wrapper">
                <!-- Akad Block -->
                <div class="event-item-block" data-reveal="up">
                    <h4>Akad Nikah</h4>
                    <div class="event-details-flex">
                        <div class="event-col">
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></i>
                                <div class="detail-info">
                                    <b>Hari / Tanggal</b>
                                    <span><?= $wedding['akad_date'] ?></span>
                                </div>
                            </div>
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></i>
                                <div class="detail-info">
                                    <b>Lokasi</b>
                                    <span><?= $wedding['akad_place'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="event-col">
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></i>
                                <div class="detail-info">
                                    <b>Waktu</b>
                                    <span><?= $wedding['akad_time'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($wedding['akad_map_link'])): ?>
                        <a href="<?= $wedding['akad_map_link'] ?>" target="_blank" class="btn-map">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                            Buka Google Maps
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Resepsi Block -->
                <div class="event-item-block" data-reveal="up" data-reveal-delay="200">
                    <h4>Resepsi Pernikahan</h4>
                    <div class="event-details-flex">
                        <div class="event-col">
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></i>
                                <div class="detail-info">
                                    <b>Hari / Tanggal</b>
                                    <span><?= $wedding['reception_date'] ?></span>
                                </div>
                            </div>
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></i>
                                <div class="detail-info">
                                    <b>Lokasi</b>
                                    <span><?= $wedding['reception_place'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="event-col">
                            <div class="detail-box">
                                <i><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></i>
                                <div class="detail-info">
                                    <b>Waktu</b>
                                    <span><?= $wedding['reception_time'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($wedding['reception_map_link'])): ?>
                        <a href="<?= $wedding['reception_map_link'] ?>" target="_blank" class="btn-map">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                            Buka Google Maps
                        </a>
                    <?php endif; ?>
                </div>
            </div>
    </section>

    <section id="gallery" data-reveal="up">
        <h3>Our Gallery</h3>
        <?php 
        $gallery = Models\Wedding::getGallery($wedding['id']);
        if (!empty($gallery)): ?>
        <div class="gallery-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; margin-top:30px;">
            <?php foreach ($gallery as $i => $img):
                $thumb = !empty($img['thumb_url']) ? $img['thumb_url'] : $img['image_url'];
                $full  = $img['image_url'];
                $delay = ($i % 4) * 150; // Stagger effect
            ?>
                <div style="aspect-ratio:1/1; overflow:hidden; border-radius:12px; cursor:pointer; position:relative;"
                     data-reveal="zoom" data-reveal-delay="<?= $delay ?>"
                     onclick="openLightbox(<?= $i ?>)">
                    <img src="<?= htmlspecialchars($thumb) ?>"
                         loading="lazy"
                         style="width:100%; height:100%; object-fit:cover; transition:transform 0.35s, filter 0.35s;"
                         onmouseover="this.style.transform='scale(1.08)'; this.style.filter='brightness(1.1)';"
                         onmouseout="this.style.transform='scale(1)'; this.style.filter='none';">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Lightbox -->
        <div id="lightbox" onclick="closeLightbox()"
             style="display:none; position:fixed; inset:0; z-index:9999;
                    background:rgba(0,0,0,0.92); align-items:center; justify-content:center;
                    flex-direction:column; gap:16px; padding:20px;">
            <button onclick="event.stopPropagation(); shiftLightbox(-1)"
                    style="position:fixed; left:20px; top:50%; transform:translateY(-50%);
                           background:rgba(255,255,255,0.15); border:none; color:white;
                           width:48px; height:48px; border-radius:50%; font-size:1.4rem; cursor:pointer;">‹</button>
            <img id="lbImg" src="" alt=""
                 style="max-width:90vw; max-height:82vh; border-radius:12px; object-fit:contain;
                        box-shadow:0 20px 60px rgba(0,0,0,0.5);">
            <button onclick="event.stopPropagation(); shiftLightbox(1)"
                    style="position:fixed; right:20px; top:50%; transform:translateY(-50%);
                           background:rgba(255,255,255,0.15); border:none; color:white;
                           width:48px; height:48px; border-radius:50%; font-size:1.4rem; cursor:pointer;">›</button>
            <button onclick="closeLightbox()"
                    style="position:fixed; top:16px; right:20px; background:rgba(255,255,255,0.15);
                           border:none; color:white; width:38px; height:38px; border-radius:50%;
                           font-size:1.1rem; cursor:pointer;">✕</button>
            <p id="lbCounter" style="color:rgba(255,255,255,0.5); font-size:0.8rem; margin:0;"></p>
        </div>

        <script>
        const lbImages = [<?php echo implode(',', array_map(fn($g) => json_encode($g['image_url']), $gallery)); ?>];
        let lbIdx = 0;
        function openLightbox(i) {
            lbIdx = i;
            document.getElementById('lbImg').src = lbImages[i];
            document.getElementById('lbCounter').textContent = (i+1) + ' / ' + lbImages.length;
            const lb = document.getElementById('lightbox');
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = '';
        }
        function shiftLightbox(dir) {
            lbIdx = (lbIdx + dir + lbImages.length) % lbImages.length;
            document.getElementById('lbImg').src = lbImages[lbIdx];
            document.getElementById('lbCounter').textContent = (lbIdx+1) + ' / ' + lbImages.length;
        }
        document.addEventListener('keydown', e => {
            if (document.getElementById('lightbox').style.display === 'flex') {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft')  shiftLightbox(-1);
                if (e.key === 'ArrowRight') shiftLightbox(1);
            }
        });
        </script>
        <?php endif; ?>
    </section>

    <section id="gifts" data-reveal="up" style="background-color: #fcf9f5;">
        <h3>Wedding Gift</h3>
        <p style="margin-bottom: 40px;">Bagi keluarga dan kerabat yang ingin mengirimkan hadiah, dapat melalui nomor rekening berikut:</p>
        
        <div class="couple" style="align-items: flex-start; justify-content: center; gap: 40px; margin-top: 40px;">
            <?php if (!empty($wedding['bank1_acc'])): ?>
            <div data-reveal="up" style="flex: 1; min-width: 280px; max-width: 350px; text-align: center; padding: 40px 20px; background: #fff; border-bottom: 2px solid var(--gold);">
                <p style="font-weight: 700; font-size: 1.1rem; margin: 0 0 8px; color: var(--dark); letter-spacing: 1px; text-transform: uppercase;"><?= $wedding['bank1_owner'] ?></p>
                <p style="color: var(--gold); font-weight: 600; margin: 0 0 10px; font-size: 0.95rem;"><?= $wedding['bank1_name'] ?></p>
                <p style="font-size: 1.5rem; font-weight: 700; letter-spacing: 3px; margin: 0 0 25px; color: var(--dark); font-family: 'Playfair Display', serif;"><?= $wedding['bank1_acc'] ?></p>
                <button onclick="copyToClipboard('<?= $wedding['bank1_acc'] ?>', this)" class="btn-map" style="border: none; cursor: pointer; font-size: 0.8rem; padding: 10px 24px;">Salin Rekening</button>
            </div>
            <?php endif; ?>

            <?php if (!empty($wedding['bank2_acc'])): ?>
            <div data-reveal="up" data-reveal-delay="200" style="flex: 1; min-width: 280px; max-width: 350px; text-align: center; padding: 40px 20px; background: #fff; border-bottom: 2px solid var(--gold);">
                <p style="font-weight: 700; font-size: 1.1rem; margin: 0 0 8px; color: var(--dark); letter-spacing: 1px; text-transform: uppercase;"><?= $wedding['bank2_owner'] ?></p>
                <p style="color: var(--gold); font-weight: 600; margin: 0 0 10px; font-size: 0.95rem;"><?= $wedding['bank2_name'] ?></p>
                <p style="font-size: 1.5rem; font-weight: 700; letter-spacing: 3px; margin: 0 0 25px; color: var(--dark); font-family: 'Playfair Display', serif;"><?= $wedding['bank2_acc'] ?></p>
                <button onclick="copyToClipboard('<?= $wedding['bank2_acc'] ?>', this)" class="btn-map" style="border: none; cursor: pointer; font-size: 0.8rem; padding: 10px 24px;">Salin Rekening</button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section id="guestbook" data-reveal="up">
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

        <div style="text-align: left; max-width: 700px; margin: auto; padding: 40px; background: #fffaf5; border-radius: 4px;">
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
                        
                        <?php if (!empty($m['reply'])): ?>
                        <div style="margin-top:12px; background:#fef9f2; padding:10px 14px; border-radius:10px; border-left:3px solid var(--gold); display:flex; gap:10px; align-items:flex-start;">
                            <div style="font-size:1.2rem; line-height:1;">✨</div>
                            <div>
                                <strong style="font-size:0.8rem; color:var(--gold); display:block; margin-bottom:2px;">Balasan Pengantin:</strong>
                                <p style="margin:0; font-size:0.85rem; color:#666; line-height:1.5;"><?= nl2br(htmlspecialchars($m['reply'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
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

    <!-- Floating Navigation -->
    <nav class="floating-nav">
        <a href="#hero" class="nav-item" title="Home"><i><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></i></a>
        <a href="#couple" class="nav-item" title="Couple"><i><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></i></a>
        <a href="#events" class="nav-item" title="Events"><i><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></i></a>
        <a href="#gallery" class="nav-item" title="Gallery"><i><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></i></a>
        <a href="#gifts" class="nav-item" title="Gifts"><i><svg viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg></i></a>
        <a href="#guestbook" class="nav-item" title="Guestbook"><i><svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></i></a>
    </nav>

    <?php if (!empty($wedding['music_url'])): 
        $yt_id = null;
        if (preg_match("/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/\s]{11})/", $wedding['music_url'], $matches)) {
            $yt_id = $matches[1];
        }
    ?>
    <!-- Background Music Player -->
    <div id="musicControls" style="position:fixed; bottom:20px; left:20px; z-index:9999;">
        <?php if ($yt_id): ?>
            <div id="ytPlayer" style="position: absolute; left: -9999px; top: -9999px;"></div>
        <?php else: ?>
            <audio id="bgMusic" loop preload="auto">
                <source src="<?= htmlspecialchars($wedding['music_url']) ?>" type="audio/mpeg">
            </audio>
        <?php endif; ?>

        <button id="musicToggleBtn" onclick="toggleMusic()"
            style="width:50px; height:50px; border-radius:50%; border:none;
                   background:var(--gold); color:white; font-size:1.2rem;
                   box-shadow:0 4px 10px rgba(0,0,0,0.2); cursor:pointer;
                   display:flex; align-items:center; justify-content:center;
                   transition:transform 0.3s; animation: pulse 2s infinite;">
            🎵
        </button>
    </div>
    <?php endif; ?>
    <style>
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(212,175,55,0.7); } 70% { box-shadow: 0 0 0 10px rgba(212,175,55,0); } 100% { box-shadow: 0 0 0 0 rgba(212,175,55,0); } }
        .playing { animation: spin 4s linear infinite !important; }
        .paused { animation: none !important; }
    </style>
    <script>
        // Music Player Variables & Helpers
        let isPlaying = false;
        let ytPlayer = null;
        <?php if (!empty($wedding['music_url'])): 
            $yt_id = null;
            if (preg_match("/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/\s]{11})/", $wedding['music_url'], $matches)) {
                $yt_id = $matches[1];
            }
        ?>
        const musicBtn = document.getElementById('musicToggleBtn');
        const isYT = <?= $yt_id ? 'true' : 'false' ?>;

        <?php if ($yt_id): ?>
            // YouTube IFrame API
            const tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            function onYouTubeIframeAPIReady() {
                ytPlayer = new YT.Player('ytPlayer', {
                    height: '0', width: '0',
                    videoId: '<?= $yt_id ?>',
                    playerVars: { 
                        'autoplay': 0, 'loop': 1, 'playlist': '<?= $yt_id ?>', 'controls': 0,
                        'origin': window.location.origin
                    },
                    events: {
                        'onStateChange': (e) => {
                            if (e.data === YT.PlayerState.PLAYING) {
                                musicBtn.classList.add('playing');
                                isPlaying = true;
                            } else {
                                musicBtn.classList.remove('playing');
                                isPlaying = false;
                            }
                        }
                    }
                });
            }
        <?php endif; ?>

        function toggleMusic() {
            if (isYT) {
                if (!ytPlayer) return;
                isPlaying ? ytPlayer.pauseVideo() : ytPlayer.playVideo();
            } else {
                const bgMusic = document.getElementById('bgMusic');
                if (isPlaying) {
                    bgMusic.pause();
                    musicBtn.classList.remove('playing');
                    musicBtn.classList.add('paused');
                    isPlaying = false;
                } else {
                    bgMusic.play().then(() => {
                        musicBtn.classList.remove('paused');
                        musicBtn.classList.add('playing');
                        isPlaying = true;
                    }).catch(e => console.log('Autoplay blocked:', e));
                }
            }
        }
        <?php else: ?>
        function toggleMusic() { console.log('No music URL provided'); }
        <?php endif; ?>

        function bukaUndangan() {
            const overlay = document.getElementById('openingOverlay');
            if (overlay) {
                overlay.classList.add('fade-out');
                document.body.classList.remove('locked');
                setTimeout(() => overlay.remove(), 1000);
            }
            // Start Music if exists
            toggleMusic();
        }

        // Keep the click fallback
        document.body.addEventListener('click', function initAudio() {
            if (!isPlaying && !document.getElementById('openingOverlay')) toggleMusic();
        }, { once: true });

        // Custom Smooth Scroll
        function smoothScrollTo(target, duration = 1000) {
            const targetElement = document.querySelector(target);
            if (!targetElement) return;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
            const startPosition = window.pageYOffset;
            const distance = targetPosition - startPosition;
            let startTime = null;
            function animation(currentTime) {
                if (startTime === null) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const ease = t => 1 - Math.pow(1 - t, 4);
                const scroll = ease(Math.min(timeElapsed / duration, 1)) * distance + startPosition;
                window.scrollTo(0, scroll);
                if (timeElapsed < duration) requestAnimationFrame(animation);
            }
            requestAnimationFrame(animation);
        }

        document.querySelectorAll('.nav-item').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                smoothScrollTo(this.getAttribute('href'));
            });
        });

        // Reveal Animation logic
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const delay = el.getAttribute('data-reveal-delay') || 0;
                        setTimeout(() => el.classList.add('active'), delay);
                        observer.unobserve(el);
                    }
                });
            }, observerOptions);
            document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
        });

        // Countdown Timer Logic
        <?php if (!empty($wedding['start_date'])): ?>
        (function() {
            const targetDate = new Date("<?= $wedding['start_date'] ?>").getTime();
            const daysEl = document.getElementById('days'), hoursEl = document.getElementById('hours'),
                  minsEl = document.getElementById('minutes'), secsEl = document.getElementById('seconds'),
                  countdownContainer = document.getElementById('countdown');
            function updateCountdown() {
                const now = new Date().getTime(), distance = targetDate - now;
                if (distance < 0) {
                    if (countdownContainer) countdownContainer.innerHTML = '<div style="font-weight:700; letter-spacing:2px;">HARI BAHAGIA TELAH TIBA! 🌸</div>';
                    return;
                }
                const d = Math.floor(distance / (1000 * 60 * 60 * 24)),
                      h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                      m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                      s = Math.floor((distance % (1000 * 60)) / 1000);
                if (daysEl) daysEl.textContent = d.toString().padStart(2, '0');
                if (hoursEl) hoursEl.textContent = h.toString().padStart(2, '0');
                if (minsEl) minsEl.textContent = m.toString().padStart(2, '0');
                if (secsEl) secsEl.textContent = s.toString().padStart(2, '0');
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
        <?php endif; ?>
    </script>

</body>
</html>
