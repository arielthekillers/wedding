<!DOCTYPE html>
<html lang="en" class="<?= isset($_GET['msg']) ? '' : 'locked' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of <?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Amiri:wght@400;700&family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        :root {
            --gold: #c5a059;
            --dark: #374151;
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
            font-size: clamp(2rem, 10vw, 6.5rem); 
            margin: 15px 0; 
            color: var(--white); 
            line-height: 1;
            text-shadow: 2px 2px 15px rgba(0,0,0,0.4);
            white-space: nowrap;
        }
        .hero p { font-size: clamp(0.8rem, 4vw, 1.2rem); letter-spacing: 4px; text-transform: uppercase; margin: 10px 0; }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        html {
            scroll-snap-type: y mandatory;
        }
        html.locked, body.locked {
            overflow: hidden !important;
            height: 100% !important;
        }
        section { 
            min-height: 100vh;
            width: 100%;
            scroll-snap-align: start;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            box-sizing: border-box;
        }
        /* Guestbook section lebih sempit padding-nya agar form lebih lebar */
        section#guestbook { padding-left: 10px; padding-right: 10px; }
        /* Optimized Backgrounds */
        section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--section-bg);
            background-size: cover;
            background-position: center;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none;
            filter: grayscale(50%);
        }
        .section-inner {
            max-width: 900px;
            width: 100%;
            margin: auto;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        
        /* Nuances */
        #couple { background-color: #fcfaf7; }
        #love-story { background-color: #fff9fb; }
        #events { background-color: #ffffff; }
        #gallery { background-color: #111; color: white; }
        #gallery h3 { color: #fff; }
        #gifts { background-color: #fbf8f3; }
        #guestbook {
            background-color: #f8f8f8;
            height: auto;
            min-height: 100vh;
            padding: 80px 20px 100px;
        }

        h3 { font-family: 'Alex Brush', cursive; font-size: clamp(1.8rem, 6vw, 2.8rem); color: var(--gold); margin-top: 0; margin-bottom: 15px; position: relative; }

        /* Modern Gallery Image Slider */
        .gallery-slider-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border: 2px solid var(--gold);
        }
        .gallery-slider {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none; /* Hide scrollbar Firefox */
            -ms-overflow-style: none; /* Hide scrollbar IE/Edge */
            width: 100%;
            scroll-behavior: smooth;
        }
        .gallery-slider::-webkit-scrollbar {
            display: none; /* Hide scrollbar Chrome/Safari */
        }
        .gallery-slide {
            flex: 0 0 100%;
            width: 100%;
            scroll-snap-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            height: 320px;
        }
        .gallery-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.5s ease;
        }
        .gallery-slide img:hover {
            transform: scale(1.05);
        }
        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .slider-btn:hover {
            background: var(--gold);
            transform: translateY(-50%) scale(1.1);
        }
        .slider-btn.prev { left: 12px; }
        .slider-btn.next { right: 12px; }
        
        .gallery-dots {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 10;
        }
        .gallery-dots .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .gallery-dots .dot.active {
            background: var(--gold) !important;
            transform: scale(1.2);
        }

        /* Horizontal Slider for Guestbook */
        .comment-slider {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 12px;
            padding: 8px 4px;
            width: 100%;
            scrollbar-width: none; /* Hide scrollbar for premium feel */
        }
        .comment-slider::-webkit-scrollbar {
            display: none; /* Hide scrollbar for premium feel */
        }
        .comment-item {
            flex: 0 0 88%;
            scroll-snap-align: center;
            margin-bottom: 0 !important;
            background: #ffffff;
            border: 1px solid rgba(212, 175, 55, 0.22);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            transition: all 0.4s ease;
            box-sizing: border-box;
        }
        .comment-item:only-child {
            flex: 0 0 100%;
        }
        .couple { display: flex; justify-content: center; align-items: flex-start; gap: 8px; width: 100%; margin-top: 10px; flex-wrap: nowrap; }
        .person { text-align: center; flex: 1; min-width: 0; }
        .person-img { 
            width: 145px; height: 185px; 
            border-radius: 80px 80px 18px 18px; 
            object-fit: cover; 
            border: 4px solid white; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            margin-bottom: 10px;
            transition: transform 0.3s;
        }
        .person h4 { font-family: 'Playfair Display', serif; font-size: 1rem; margin: 0 0 5px; color: var(--dark); }
        .person p { font-size: 0.68rem; line-height: 1.4; color: #666; margin: 0; }

        /* Couple Quote Block */
        .couple-quote {
            margin: 0 auto 18px;
            max-width: 340px;
            text-align: center;
            padding: 0 18px;
        }
        .bismillah {
            font-family: 'Amiri', 'Traditional Arabic', serif;
            font-size: 1.15rem;
            font-style: italic;
            color: #5a6272;
            line-height: 1.8;
            margin: 0 0 6px;
            direction: rtl;
        }
        .couple-verse {
            font-size: 0.75rem;
            font-style: normal;
            font-weight: 400;
            color: #5a6272;
            line-height: 1.7;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        
        /* Story Slider */
        .story-slider {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 15px;
            width: 100%;
            padding: 10px 0;
            scrollbar-width: none;
        }
        .story-slider::-webkit-scrollbar { display: none; }
        .story-card {
            flex: 0 0 80%;
            scroll-snap-align: center;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: left;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .story-card img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .story-card h4 { font-family: 'Playfair Display', serif; font-size: 1.1rem; margin: 0 0 5px; }
        .story-card p { font-size: 0.75rem; color: #666; line-height: 1.4; margin: 0; }

        .story-intro, .story-outro {
            font-size: 0.8rem;
            line-height: 1.6;
            color: #64748b;
            max-width: 85%;
            margin: 0 auto;
            font-style: italic;
        }
        .story-intro { margin-bottom: 20px; }
        .story-outro { margin-top: 15px; }

        .event-wrapper {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 500px;
            margin-top: 5px;
        }
        .event-item-block {
            position: relative;
            overflow: hidden;
            padding: 15px 20px;
            border-radius: 20px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            text-align: center;
            z-index: 1;
        }
        .event-item-block > * {
            position: relative;
            z-index: 2;
        }
        .event-item-block::before {
            content: '';
            position: absolute;
            bottom: -15px;
            right: -15px;
            width: 100px;
            height: 100px;
            opacity: 0.08;
            background-repeat: no-repeat;
            background-size: contain;
            pointer-events: none;
            z-index: 0;
            transition: transform 0.5s ease;
        }
        .event-item-block:hover::before {
            transform: scale(1.1) rotate(-10deg);
        }
        .event-item-block:nth-child(1)::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='40' cy='50' r='22' stroke='%23d4af37' stroke-width='4' fill='none'/%3E%3Ccircle cx='60' cy='50' r='22' stroke='%23d4af37' stroke-width='4' fill='none'/%3E%3C/svg%3E");
        }
        .event-item-block:nth-child(2)::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath d='M35 25 L45 25 L43 55 L37 55 Z' stroke='%23d4af37' stroke-width='4' fill='none' /%3E%3Cpath d='M40 55 L40 75' stroke='%23d4af37' stroke-width='4' /%3E%3Cpath d='M33 75 L47 75' stroke='%23d4af37' stroke-width='4' /%3E%3Cpath d='M65 25 L55 25 L57 55 L63 55 Z' stroke='%23d4af37' stroke-width='4' fill='none' /%3E%3Cpath d='M60 55 L60 75' stroke='%23d4af37' stroke-width='4' /%3E%3Cpath d='M53 75 L67 75' stroke='%23d4af37' stroke-width='4' /%3E%3Ccircle cx='50' cy='20' r='2' fill='%23d4af37' /%3E%3Ccircle cx='47' cy='14' r='3' fill='%23d4af37' /%3E%3Ccircle cx='53' cy='14' r='1.5' fill='%23d4af37' /%3E%3C/svg%3E");
        }
        .event-item-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 5px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }
        .event-item-block h4 { font-size: 1rem; margin-top: 2px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 2px; color: var(--gold); border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; display: inline-block; }
        .detail-box { display: flex; flex-direction: column; align-items: center; gap: 5px; margin-bottom: 12px; }
        .detail-box i { width: 20px; height: 20px; color: var(--gold); margin-bottom: 2px; }
        .detail-box i svg { width: 20px; height: 20px; display: block; }
        .detail-info b { font-size: 0.6rem; display: block; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .detail-info span { font-size: 0.8rem; font-weight: 600; color: var(--dark); line-height: 1.3; }
        .btn-map-bottom { 
            display: inline-flex; align-items: center; gap: 8px; 
            padding: 8px 20px; font-size: 0.75rem; 
            background: white; border: 1px solid var(--gold); 
            color: var(--gold); border-radius: 50px; 
            text-decoration: none; font-weight: 600; 
            margin-top: 5px; transition: 0.3s;
        }
        .btn-map-bottom:hover { background: var(--gold); color: white; }
        .btn-map-bottom svg { width: 14px; height: 14px; }
        
        .btn-reminder {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: var(--dark);
            border: 1.5px solid var(--gold);
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
        }
        .btn-reminder:hover {
            background: var(--gold) !important;
            color: white !important;
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.2) !important;
            transform: translateY(-2px);
        }
        .btn-reminder svg {
            color: var(--gold);
            transition: stroke 0.3s, color 0.3s;
        }
        .btn-reminder:hover svg {
            color: white !important;
            stroke: white !important;
        }
        
        .gift-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            padding: 10px 15px;
        }
        .gift-card {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding: 16px 18px;
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-align: center;
            transition: all 0.3s ease;
            z-index: 1;
        }
        .gift-card > * {
            position: relative;
            z-index: 2;
        }
        .gift-card::before {
            content: '';
            position: absolute;
            bottom: -10px;
            right: -10px;
            width: 80px;
            height: 80px;
            opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect x='15' y='25' width='70' height='50' rx='6' stroke='%23d4af37' stroke-width='4' fill='none'/%3E%3Cline x1='15' y1='40' x2='85' y2='40' stroke='%23d4af37' stroke-width='4'/%3E%3Crect x='25' y='52' width='15' height='10' rx='2' fill='%23d4af37'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: contain;
            pointer-events: none;
            z-index: 0;
            transition: transform 0.5s ease;
        }
        .gift-card:hover::before {
            transform: scale(1.1) rotate(-10deg);
        }
        .gift-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border-color: var(--gold);
        }
        .bank-badge {
            display: inline-block;
            padding: 3px 12px;
            background: #fffaf0;
            border: 1px solid #fdf2d8;
            border-radius: 50px;
            color: var(--gold);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }
        .acc-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            letter-spacing: 1px;
            margin: 4px 0;
            display: block;
        }
        .acc-owner {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-copy {
            background: transparent;
            color: var(--gold);
            border: 1px solid var(--gold);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: auto;
            min-width: 140px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin: 0 auto;
        }
        .btn-copy:hover {
            background: var(--gold);
            color: white;
        }
        .btn-copy svg { width: 14px; height: 14px; }

        /* Map Button Styling */
        .btn-map {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            background: #ffffff;
            color: var(--dark);
            text-decoration: none;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            border: 2px solid var(--dark);
            margin-top: 20px;
        }
        .btn-map:hover {
            background: var(--dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .btn-map svg { width: 18px; height: 18px; }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 10001;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }
        
        .footer { padding: 40px; font-size: 0.9rem; color: #999; }

        /* Guestbook & RSVP Redesign */
        .form-card {
            background: #ffffff;
            padding: 20px 16px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            width: 100%;
            max-width: 100%;
            margin: auto;
            text-align: left;
            border: 1px solid #f1f5f9;
            position: relative;
        }

        /* ── Floating Label System ── */
        .form-group {
            position: relative;
            margin-bottom: 12px;
        }
        .form-group label {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            font-size: 0.82rem;
            color: #94a3b8;
            pointer-events: none;
            transition: all 0.2s ease;
            background: transparent;
            padding: 0 3px;
            font-weight: 500;
            letter-spacing: 0;
            text-transform: none;
            margin-bottom: 0;
        }
        /* Textarea label starts from top */
        .form-group.is-textarea label {
            top: 14px;
            transform: none;
        }
        /* Floated state: focused or has content */
        .form-group.floated label,
        .form-group input:focus ~ label,
        .form-group textarea:focus ~ label {
            top: -8px;
            transform: none;
            font-size: 0.65rem;
            color: var(--gold);
            background: #fff;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .form-group.is-textarea.floated label,
        .form-group.is-textarea input:focus ~ label,
        .form-group.is-textarea textarea:focus ~ label {
            top: -8px;
            transform: none;
        }
        .input-premium {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.25s;
            background: #fff;
            color: var(--dark);
            box-sizing: border-box;
        }
        .input-premium::placeholder { color: transparent; }
        .input-premium:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.1);
        }
        .rsvp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        @media (max-width: 480px) {
            .rsvp-grid.form-inputs { grid-template-columns: 1fr; gap: 0; }
        }
        /* RSVP standalone wrapper — dipisah dari .form-group agar floating label CSS tidak mengganggu */
        .rsvp-wrapper {
            display: block;
            margin-bottom: 12px;
        }
        .rsvp-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 7px;
        }
        /* RSVP Toggle Pill */
        .rsvp-toggle {
            display: flex;
            background: #f1f5f9;
            border-radius: 50px;
            padding: 4px;
            gap: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        .rsvp-pill-option {
            flex: 1;
            position: relative;
            cursor: pointer;
        }
        .rsvp-pill-option input {
            position: absolute;
            opacity: 0;
            width: 0; height: 0;
        }
        .rsvp-pill-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.25s;
            user-select: none;
        }
        .rsvp-pill-box svg { width: 14px; height: 14px; }
        .rsvp-pill-option input:checked + .rsvp-pill-box {
            background: white;
            color: var(--dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        /* Hadir checked = green tint */
        .rsvp-pill-option.is-hadir input:checked + .rsvp-pill-box {
            color: #166534;
        }
        .rsvp-pill-option.is-hadir input:checked + .rsvp-pill-box svg {
            stroke: #166534;
        }
        /* Absen checked = red tint */
        .rsvp-pill-option.is-absen input:checked + .rsvp-pill-box {
            color: #991b1b;
        }
        .rsvp-pill-option.is-absen input:checked + .rsvp-pill-box svg {
            stroke: #991b1b;
        }

        .btn-submit {
            background: var(--dark);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.4s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 0.8rem;
        }
        .btn-submit:hover {
            background: var(--gold);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.2);
        }
        @keyframes pulse-gold {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 89, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(197, 160, 89, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(197, 160, 89, 0); }
        }
        body.locked { overflow: hidden; }

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
        .overlay-content h1 { font-family: 'Alex Brush', cursive; font-size: clamp(2rem, 10vw, 4.5rem); margin: 0; text-shadow: 2px 2px 10px rgba(0,0,0,0.3); white-space: nowrap; }
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
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px 5px;
            border-radius: 18px;
            flex: 1;
            max-width: 90px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .countdown-item span {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }
        .countdown-item label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
            color: var(--text);
        }
        #musicControls {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
        }
        @media (max-width: 480px) {
            .hero h2 { font-size: clamp(1.5rem, 11vw, 2.8rem); }
            .countdown-container { gap: 6px; padding: 0 10px; }
            .countdown-item { padding: 10px 2px; border-radius: 10px; max-width: 65px; }
            .countdown-item span { font-size: 1.1rem; }
            .countdown-item label { font-size: 0.5rem; letter-spacing: 0.5px; margin-top: 2px; }
            #musicControls {
                bottom: 85px;
                left: 20px;
            }
        }

        /* ─── DYNAMIC SECTION SETTINGS ─── */
        <?php
        $secSettings = json_decode($wedding['section_settings'] ?? '{}', true) ?: [];
        // Helper: normalize bg_image paths to fix DOCUMENT_ROOT vs subdomain mismatch
        $normBg = function($path) {
            if (empty($path)) return $path;
            if (preg_match('#(uploads/.+)$#', $path, $m)) {
                $b = isset($GLOBALS['base']) ? rtrim($GLOBALS['base'], '/') : '';
                return $b . '/' . $m[1];
            }
            return $path;
        };
        $sectionsMap = [
            'hero'       => '.hero, #openingOverlay',
            'countdown'  => '#countdown-section',
            'couple'     => '#couple',
            'love_story' => '#love-story',
            'events'     => '#events',
            'gallery'    => '#gallery',
            'gifts'      => '#gifts',
            'guestbook'  => '#guestbook'
        ];
        
        foreach ($sectionsMap as $key => $selector) {
            if (!empty($secSettings[$key])) {
                $cfg = $secSettings[$key];
                
                $op = isset($cfg['bg_opacity']) && $cfg['bg_opacity'] !== '' ? (float)$cfg['bg_opacity'] : ($key === 'hero' ? 0.70 : 0.08);

                if ($key === 'hero') {
                    // For Hero and Overlay, we strip the default background and use ::before for proper opacity blending
                    if (!empty($cfg['bg_image'])) {
                        $bgColor = !empty($cfg['bg_color']) ? $cfg['bg_color'] : '#1e293b'; // Default dark bg if none set
                        $bgImgUrl = $normBg($cfg['bg_image']);
                        
                        $selectors = explode(',', $selector);
                        $beforeSelectors = array_map(function($s) { return trim($s) . '::before'; }, $selectors);
                        $beforeSelector = implode(', ', $beforeSelectors);
                        
                        echo "$selector { background: $bgColor !important; }\n";
                        echo "$beforeSelector { 
                            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                            background: url('{$bgImgUrl}') no-repeat center center/cover !important; 
                            opacity: {$op} !important; 
                            z-index: 0; pointer-events: none; filter: none !important; 
                        }\n";
                        echo "#openingOverlay .overlay-content, .hero > div { position: relative; z-index: 2; }\n";
                    } else {
                        // If only color/opacity changed but no image, apply color
                        if (!empty($cfg['bg_color'])) {
                            echo "$selector { background-color: {$cfg['bg_color']} !important; }\n";
                        }
                    }
                } else {
                    // Background Color
                    if (!empty($cfg['bg_color'])) {
                        echo "$selector { background-color: {$cfg['bg_color']} !important; }\n";
                    }
                    
                    if (!empty($cfg['bg_image'])) {
                        $bgImgUrl = $normBg($cfg['bg_image']);
                        echo "$selector { --section-bg: url('{$bgImgUrl}') !important; }\n";
                    }
                    echo "$selector::before { opacity: {$op} !important; }\n";
                }
            }
        }
        ?>
    </style>
</head>
<body class="<?= isset($_GET['msg']) ? '' : 'locked' ?>">

    <!-- ─── 1. SAMPUL PEMBUKA (OPENING OVERLAY) ─── -->
    <!-- Layar penutup penuh sebelum tombol 'Buka Undangan' diklik -->
    <?php if (!isset($_GET['msg'])): ?>
    <div id="openingOverlay">
        <div class="overlay-content">
            <p>The Wedding of</p>
            <h1><?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></h1>
            <div style="margin-top: 20px; font-size: 0.9rem; opacity: 0.9;">
                Kepada Yth. Bapak/Ibu/Saudara/i:<br>
                <strong style="font-size: 1.2rem; display: block; margin-top: 5px;"><?= isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan' ?></strong>
            </div>
            <button class="btn-open" onclick="bukaUndangan()" style="margin: 40px auto 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- ─── 2. HAMPARAN UTAMA (HERO SECTION / SAMPUL UTAMA) ─── -->
    <!-- Layar utama tanpa tanggal, menampilkan text 'Kepada Yth...' dan nama mempelai -->
    <section id="hero" class="hero" style="scroll-snap-align: start;">
        <div data-reveal="zoom">
            <p>The Wedding of</p>
            <h2><?= $wedding['bride_nickname'] ?> & <?= $wedding['groom_nickname'] ?></h2>
            <div style="margin-top: 30px; font-size: 0.95rem; opacity: 0.9;">
                Kepada Yth. Bapak/Ibu/Saudara/i:<br>
                <strong style="font-size: 1.25rem; display: block; margin-top: 5px; color: var(--gold);"><?= isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan' ?></strong>
            </div>
        </div>
    </section>

    <!-- ─── 3. HITUNG MUNDUR (COUNTDOWN SECTION) ─── -->
    <!-- Bagian hitung mundur, sekarang menampilkan tanggal akad di atas jam hitung mundur -->
    <?php 
    if (!empty($wedding['start_date'])): 
        $gcalUrl = '#';
        $countdownDateText = '';
        try {
            $startDate = new DateTime($wedding['start_date']);
            $endDate = clone $startDate;
            $endDate->modify('+3 hours'); // Durasi default 3 jam
            
            $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $base . $wedding['slug'];
            
            $title = "Pernikahan " . $wedding['bride_nickname'] . " & " . $wedding['groom_nickname'];
            $details = "Mohon doa restu Anda dalam acara pernikahan kami.\n\nInfo Detail & Peta Lokasi kunjungi:\n" . $currentUrl;
            $location = $wedding['akad_place'] ?? '';
            
            $gcalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE"
                . "&text=" . urlencode($title)
                . "&dates=" . $startDate->format('Ymd\THis') . "/" . $endDate->format('Ymd\THis')
                . "&details=" . urlencode($details)
                . "&location=" . urlencode($location);
            
            // Format start_date to Indonesian date format
            $timestamp = $startDate->getTimestamp();
            $days = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jum\'at',
                'Saturday' => 'Sabtu'
            ];
            $months = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];
            $dayName = $days[date('l', $timestamp)] ?? date('l', $timestamp);
            $dayNum = date('j', $timestamp);
            $monthName = $months[date('F', $timestamp)] ?? date('F', $timestamp);
            $year = date('Y', $timestamp);
            $countdownDateText = "$dayName, $dayNum " . strtoupper($monthName) . " $year";
        } catch (Exception $e) {
            // Fallback
        }
        
        if (empty($countdownDateText)) {
            $countdownDateText = $wedding['akad_date'];
        }
    ?>
    <section id="countdown-section" data-reveal="up" style="scroll-snap-align: start; padding: 60px 20px; text-align: center; position: relative; --section-bg: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner" style="max-width: 800px; margin: 0 auto; position: relative; z-index: 2;">
            <h3 style="margin-bottom: 8px; font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--gold);">Menuju Hari Bahagia</h3>
            <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark); margin-bottom: 25px; letter-spacing: 1px;">Menghitung hari menuju ikatan suci</p>
            <div id="countdown" class="countdown-container" style="margin-top: 0;">
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
            
            <!-- Tombol Pengingat Google Kalender -->
            <?php if ($gcalUrl !== '#'): ?>
            <div style="margin-top: 25px;" data-reveal="up" data-reveal-delay="200">
                <a href="<?= $gcalUrl ?>" target="_blank" class="btn-reminder">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                        <path d="M16 18h.01"></path>
                    </svg>
                    Ingatkan di Google Calendar
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="couple" data-reveal="up" style="scroll-snap-align: start; --section-bg: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3>The Happy Couple</h3>
            <div class="couple-quote" data-reveal="up" data-reveal-delay="100">
                <p class="bismillah">بسم الله الرحمن الرحيم</p>
                <p class="couple-verse">Maha suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.<br>Ya Allah, perkenankanlah kami merangkaikan kasih sayang yang Kau ciptakan dalam rumah tangga kami.</p>
            </div>
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
        </div>
    </section>

    <section id="love-story" data-reveal="up" style="scroll-snap-align: start; --section-bg: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3>Our Love Story</h3>
            
            <?php if (!empty($wedding['story_intro'])): ?>
                <p class="story-intro" data-reveal="up"><?= nl2br(htmlspecialchars($wedding['story_intro'])) ?></p>
            <?php endif; ?>

            <?php 
            $loveStories = Models\Wedding::getLoveStories($wedding['id']);
            if (!empty($loveStories)): ?>
                <div class="story-slider">
                    <?php foreach ($loveStories as $ls): ?>
                    <div class="story-card">
                        <?php if (!empty($ls['image_url'])): ?>
                        <img src="<?= htmlspecialchars($ls['image_url']) ?>">
                        <?php else: ?>
                        <div style="width:60px; height:60px; border-radius:50%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">💕</div>
                        <?php endif; ?>
                        <div>
                            <div style="font-size:0.6rem; color:var(--gold); font-weight:700; text-transform:uppercase;"><?= date('M Y', strtotime($ls['story_date'])) ?></div>
                            <h4><?= htmlspecialchars($ls['title']) ?></h4>
                            <p><?= htmlspecialchars($ls['content']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($wedding['story_outro'])): ?>
                <p class="story-outro" data-reveal="up"><?= nl2br(htmlspecialchars($wedding['story_outro'])) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section id="events" data-reveal="up" style="scroll-snap-align: start; --section-bg: url('https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3 style="font-family: 'Playfair Display', serif; font-weight: 700; font-style: italic; margin-top: 0; margin-bottom: 8px;">Save the Date</h3>
            <div class="event-wrapper">
                <div class="event-item-block" data-reveal="up">
                    <h4>Akad Nikah</h4>
                    <div class="event-item-grid">
                        <div class="detail-box">
                            <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg></i>
                            <div class="detail-info"><b>Hari / Tanggal</b><span><?= $wedding['akad_date'] ?></span></div>
                        </div>
                        <div class="detail-box">
                            <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></i>
                            <div class="detail-info"><b>Waktu</b><span><?= $wedding['akad_time'] ?></span></div>
                        </div>
                    </div>
                    <div class="detail-box">
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path></svg></i>
                        <div class="detail-info"><b>Lokasi</b><span><?= $wedding['akad_place'] ?></span></div>
                    </div>
                    <?php if (!empty($wedding['akad_map_link'])): ?>
                        <a href="<?= $wedding['akad_map_link'] ?>" target="_blank" class="btn-map-bottom">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Buka Google Maps
                        </a>
                    <?php endif; ?>
                </div>

                <div class="event-item-block" data-reveal="up" data-reveal-delay="200">
                    <h4>Resepsi</h4>
                    <div class="event-item-grid">
                        <div class="detail-box">
                            <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg></i>
                            <div class="detail-info"><b>Hari / Tanggal</b><span><?= $wedding['reception_date'] ?></span></div>
                        </div>
                        <div class="detail-box">
                            <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></i>
                            <div class="detail-info"><b>Waktu</b><span><?= $wedding['reception_time'] ?></span></div>
                        </div>
                    </div>
                    <div class="detail-box">
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path></svg></i>
                        <div class="detail-info"><b>Lokasi</b><span><?= $wedding['reception_place'] ?></span></div>
                    </div>
                    <?php if (!empty($wedding['reception_map_link'])): ?>
                        <a href="<?= $wedding['reception_map_link'] ?>" target="_blank" class="btn-map-bottom">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Buka Google Maps
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="gallery" data-reveal="up" style="scroll-snap-align: start; --section-bg: url('https://images.unsplash.com/photo-1465495910676-424343b01393?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3>Our Gallery</h3>
            <?php 
            $gallery = Models\Wedding::getGallery($wedding['id']);
            if (!empty($gallery)): ?>
                <div class="gallery-slider-container">
                    <div class="gallery-slider" id="gallerySlider">
                    <?php foreach ($gallery as $img): ?>
                        <div class="gallery-slide">
                            <img src="<?= $img['image_url'] ?>" alt="Gallery" onclick="openLightbox('<?= $img['image_url'] ?>')">
                        </div>
                    <?php endforeach; ?>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <button class="slider-btn prev" onclick="slideGallery(-1)">&#10094;</button>
                    <button class="slider-btn next" onclick="slideGallery(1)">&#10095;</button>
                    
                    <!-- Slide Indicators / Dots -->
                    <div class="gallery-dots" id="galleryDots">
                        <?php foreach ($gallery as $i => $img): ?>
                            <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)"></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lightbox -->
            <div id="lightbox" onclick="closeLightbox()"
                 style="display:none; position:fixed; inset:0; z-index:9999;
                        background:rgba(0,0,0,0.92); align-items:center; justify-content:center;
                        flex-direction:column; gap:16px; padding:20px;">
                <img id="lbImg" src="" alt=""
                     style="max-width:90vw; max-height:82vh; border-radius:12px; object-fit:contain;
                            box-shadow:0 20px 60px rgba(0,0,0,0.5);">
                <button onclick="closeLightbox()"
                        style="position:fixed; top:16px; right:20px; background:rgba(255,255,255,0.15);
                               border:none; color:white; width:38px; height:38px; border-radius:50%;
                               font-size:1.1rem; cursor:pointer;">✕</button>
            </div>

            <script>
            function openLightbox(url) {
                const img = document.getElementById('lbImg');
                img.src = url;
                const lb = document.getElementById('lightbox');
                lb.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            function closeLightbox() {
                document.getElementById('lightbox').style.display = 'none';
                document.body.style.overflow = '';
            }
            
            // Gallery Slider Functions
            function slideGallery(direction) {
                const slider = document.getElementById('gallerySlider');
                if (!slider) return;
                const slideWidth = slider.offsetWidth;
                slider.scrollBy({
                    left: direction * slideWidth,
                    behavior: 'smooth'
                });
            }
            function goToSlide(slideIndex) {
                const slider = document.getElementById('gallerySlider');
                if (!slider) return;
                const slideWidth = slider.offsetWidth;
                slider.scrollTo({
                    left: slideIndex * slideWidth,
                    behavior: 'smooth'
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('gallerySlider');
                const dots = document.querySelectorAll('#galleryDots .dot');
                if (slider && dots.length > 0) {
                    slider.addEventListener('scroll', () => {
                        const index = Math.round(slider.scrollLeft / slider.offsetWidth);
                        dots.forEach((dot, i) => {
                            if (i === index) {
                                dot.classList.add('active');
                            } else {
                                dot.classList.remove('active');
                            }
                        });
                    });
                }
            });
            </script>
<?php endif; ?>
        </div>
    </section>

    <section id="gifts" data-reveal="up" style="--section-bg: url('https://images.unsplash.com/photo-1513519245088-0e12902e35ca?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3>Wedding Gift</h3>
            <p style="margin-bottom: 40px; color: #64748b;">Doa restu Anda merupakan hadiah terindah bagi kami. Namun jika Anda ingin memberikan hadiah, silakan melalui saluran berikut:</p>
            
            <div class="gift-container">
                <?php if (!empty($wedding['bank1_acc'])): ?>
                <div class="gift-card" data-reveal="up">
                    <span class="bank-badge"><?= $wedding['bank1_name'] ?></span>
                    <span class="acc-number"><?= $wedding['bank1_acc'] ?></span>
                    <p class="acc-owner">A.N <?= $wedding['bank1_owner'] ?></p>
                    <button onclick="copyToClipboard('<?= $wedding['bank1_acc'] ?>')" class="btn-copy">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Salin Rekening
                    </button>
                </div>
                <?php endif; ?>

                <?php if (!empty($wedding['bank2_acc'])): ?>
                <div class="gift-card" data-reveal="up" data-reveal-delay="200">
                    <span class="bank-badge"><?= $wedding['bank2_name'] ?></span>
                    <span class="acc-number"><?= $wedding['bank2_acc'] ?></span>
                    <p class="acc-owner">A.N <?= $wedding['bank2_owner'] ?></p>
                    <button onclick="copyToClipboard('<?= $wedding['bank2_acc'] ?>')" class="btn-copy">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Salin Rekening
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="guestbook" data-reveal="up" style="--section-bg: url('https://images.unsplash.com/photo-1522673607200-1648835ede57?auto=format&fit=crop&q=80&w=1000');">
        <div class="section-inner">
            <h3>Guestbook & Reservation</h3>
            <?php 
            $msg    = $_GET['msg'] ?? '';
            $reason = $_GET['reason'] ?? '';
            $errorTexts = [
                'ratelimit'       => 'Kamu sudah mengirim pesan. Tunggu 5 menit sebelum mengirim lagi.',
                'name'            => 'Nama tidak valid. Minimal 2 karakter.',
                'email'           => 'Alamat email tidak valid.',
                'duplicate_email' => 'Alamat email ini sudah digunakan untuk mengirim ucapan sebelumnya.',
                'message'         => 'Pesan terlalu pendek (min 5 karakter) atau terlalu panjang (maks 500).',
            ];
            ?>

            <?php if ($msg === 'sent'): ?>
            <div id="guestbookAlert" style="background: rgba(253, 251, 247, 0.96); color: #846e53; padding: 16px 24px; border-radius: 12px; max-width: 600px; margin: 0 auto 24px; font-weight: 500; font-size: 0.88rem; border: 1px solid rgba(212, 175, 55, 0.25); box-shadow: 0 10px 30px rgba(132, 110, 83, 0.06); backdrop-filter: blur(8px); text-align: center; font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px; transition: opacity 1.5s ease, transform 1.5s ease; opacity: 1; transform: translateY(0);">
                <span>Terima kasih atas doa restu dan ucapan hangat Anda.</span>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const alertEl = document.getElementById('guestbookAlert');
                    if (alertEl) {
                        setTimeout(() => {
                            alertEl.style.opacity = '0';
                            alertEl.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                alertEl.style.display = 'none';
                            }, 1500);
                        }, 4000);
                    }
                });
            </script>
            <?php elseif ($msg === 'error'): ?>
            <div id="guestbookAlert" style="background: rgba(253, 245, 245, 0.96); color: #b91c1c; padding: 16px 24px; border-radius: 12px; max-width: 600px; margin: 0 auto 24px; font-weight: 500; font-size: 0.88rem; border: 1px solid rgba(185, 28, 28, 0.15); box-shadow: 0 10px 30px rgba(185, 28, 28, 0.05); backdrop-filter: blur(8px); text-align: center; font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px; transition: opacity 1.5s ease, transform 1.5s ease; opacity: 1; transform: translateY(0);">
                <span><?= htmlspecialchars($errorTexts[$reason] ?? 'Terjadi kesalahan. Silakan coba kembali.') ?></span>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const alertEl = document.getElementById('guestbookAlert');
                    if (alertEl) {
                        setTimeout(() => {
                            alertEl.style.opacity = '0';
                            alertEl.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                alertEl.style.display = 'none';
                            }, 1500);
                        }, 4000);
                    }
                });
            </script>
            <?php endif; ?>

            <div class="form-card">
                <form action="<?= $base ?>api/guestbook" method="POST" id="guestbookForm">
                    <input type="hidden" name="wedding_id" value="<?= $wedding['id'] ?>">
                    <input type="hidden" name="slug" value="<?= $wedding['slug'] ?>">
                    
                    <div style="position:absolute; left:-9999px; top:-9999px;" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- Nama & Email -->
                    <div class="rsvp-grid form-inputs">
                        <div class="form-group">
                            <input type="text" name="name" class="input-premium" id="fldName" required minlength="2" maxlength="100" placeholder="Nama Tamu" autocomplete="name">
                            <label for="fldName">Nama Tamu</label>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="input-premium" id="fldEmail" required placeholder="Email" autocomplete="email">
                            <label for="fldEmail">Email (Avatar)</label>
                        </div>
                    </div>

                    <!-- Kota / Kantor / Circle -->
                    <div class="form-group">
                        <input type="text" name="circle" class="input-premium" id="fldCircle" maxlength="100" placeholder="Kota / Kantor / Circle">
                        <label for="fldCircle">Kota / Kantor / Circle</label>
                    </div>

                    <!-- RSVP -->
                    <div class="rsvp-wrapper">
                        <p class="rsvp-label">Konfirmasi Kehadiran</p>
                        <div class="rsvp-toggle">
                            <label class="rsvp-pill-option is-hadir">
                                <input type="radio" name="attendance" value="1" checked onchange="highlightRsvp()">
                                <div class="rsvp-pill-box" id="lbl-hadir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"></path></svg>
                                    Hadir
                                </div>
                            </label>
                            <label class="rsvp-pill-option is-absen">
                                <input type="radio" name="attendance" value="0" onchange="highlightRsvp()">
                                <div class="rsvp-pill-box" id="lbl-tidak">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Absen
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Pesan -->
                    <div class="form-group is-textarea">
                        <textarea name="message" class="input-premium" id="msgArea" required minlength="5" maxlength="500"
                            style="height:75px; resize:none;" placeholder="Tulis ucapan..."></textarea>
                        <label for="msgArea">Pesan / Doa Restu <small id="charCount">(0/500)</small></label>
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        Kirim Ucapan
                    </button>
                </form>
            </div>

        <!-- Comment List Slider -->
        <div style="margin-top:40px; width:100%;">
            <?php 
            $messages = Models\Wedding::getMessages($wedding['id']);
            $total    = count($messages);
            if ($total === 0): ?>
                <p style="text-align:center; color:#999;">Belum ada ucapan. Jadilah yang pertama! 🌸</p>
            <?php else: ?>
                <p style="text-align:center; color:#aaa; font-size:0.75rem; margin-bottom:15px; letter-spacing:1px; text-transform:uppercase;">
                    <?= $total === 1 ? '1 Ucapan Doa Restu' : 'Geser untuk melihat ' . $total . ' ucapan' ?>
                </p>

                <div id="commentList" class="comment-slider">
                <?php foreach ($messages as $m): 
                    $avatar  = Models\Wedding::gravatarUrl($m['email'] ?? '', 60);
                    $isHadir = (bool)$m['attendance'];
                ?>
                <div class="comment-item" style="display: flex; flex-direction: row; gap: 10px; padding: 12px 14px; align-items: flex-start; text-align: left;">
                    <img src="<?= $avatar ?>" alt="avatar" width="38" height="38" style="border-radius: 50%; border: 1.5px solid <?= $isHadir ? '#dcfce7' : '#fee2e2' ?>; flex-shrink: 0; object-fit: cover;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 4px;">
                            <strong style="font-size: 0.85rem; color: #2c3e50; font-weight: 700;"><?= htmlspecialchars($m['name']) ?></strong>
                            <?php if (!empty($m['circle'])): ?>
                                <span style="font-size: 0.72rem; color: #8e9eab; font-weight: 500;">(<?= htmlspecialchars($m['circle']) ?>)</span>
                            <?php endif; ?>
                            <span style="font-size: 0.58rem; padding: 1px 5px; border-radius: 6px; background: <?= $isHadir ? '#dcfce7' : '#fee2e2' ?>; color: <?= $isHadir ? '#166534' : '#991b1b' ?>; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase;">
                                <?= $isHadir ? 'Hadir' : 'Absen' ?>
                            </span>
                        </div>
                        <p style="font-size: 0.8rem; color: #555; line-height: 1.4; margin: 0; word-break: break-word;"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                        <?php if (!empty($m['reply'])): ?>
                        <div style="margin-top: 8px; padding: 8px 10px; background: #faf6ef; border-radius: 8px; border-left: 2.5px solid var(--gold);">
                            <small style="color: var(--gold); font-weight: 700; display: block; margin-bottom: 2px; font-size: 0.7rem;">Balasan:</small>
                            <p style="margin: 0; font-size: 0.76rem; color: #666; line-height: 1.35;"><?= nl2br(htmlspecialchars($m['reply'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
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
        
        function highlightRsvp() {
            // Styling sekarang dihandle oleh CSS :checked selector pada .rsvp-pill-option
            // Fungsi ini tetap ada agar onchange handler tidak error
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

        // ── Floating Label Logic ──────────────────────────────
        function checkFloating(el) {
            const group = el.closest('.form-group');
            if (!group) return;
            if (el.value && el.value.trim() !== '') {
                group.classList.add('floated');
            } else {
                group.classList.remove('floated');
            }
        }
        // Attach to all inputs and textareas in the form
        document.querySelectorAll('#guestbookForm .input-premium').forEach(el => {
            el.addEventListener('input', () => checkFloating(el));
            el.addEventListener('change', () => checkFloating(el));
            // Run immediately in case browser autofills
            checkFloating(el);
        });
        // Handle autofill after slight delay (browser fills after DOMContentLoaded)
        setTimeout(() => {
            document.querySelectorAll('#guestbookForm .input-premium').forEach(el => checkFloating(el));
        }, 300);

        // Auto-scroll logic is handled in DOMContentLoaded below
    </script>

    <!-- Floating Navigation -->
    <nav class="floating-nav">
        <a href="<?= !empty($wedding['start_date']) ? '#countdown-section' : '#couple' ?>" class="nav-item" title="Home"><i><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></i></a>
        <a href="#couple" class="nav-item" title="Couple"><i><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></i></a>
        <a href="#love-story" class="nav-item" title="Story"><i><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></i></a>
        <a href="#events" class="nav-item" title="Events"><i><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></i></a>
        <a href="#gallery" class="nav-item" title="Gallery"><i><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></i></a>
        <a href="#gifts" class="nav-item" title="Gifts"><i><svg viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg></i></a>
        <a href="#guestbook" class="nav-item" title="Reservation"><i><svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></i></a>
    </nav>

    <?php if (!empty($wedding['music_url'])): 
        $yt_id = null;
        if (preg_match("/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/\s]{11})/", $wedding['music_url'], $matches)) {
            $yt_id = $matches[1];
        }
    ?>
    <!-- Background Music Player -->
    <div id="musicControls">
        <?php if ($yt_id): ?>
            <div id="musicPlayerDiv" style="position: absolute; left: -9999px; top: -9999px;"></div>
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
                ytPlayer = new YT.Player('musicPlayerDiv', {
                    height: '0', width: '0',
                    videoId: '<?= $yt_id ?>',
                    playerVars: { 
                        'autoplay': 0, 'loop': 1, 'playlist': '<?= $yt_id ?>', 'controls': 0,
                        'origin': window.location.origin
                    },
                    events: {
                        'onReady': (e) => {
                            console.log('YT Player Ready');
                        },
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
                if (!ytPlayer || typeof ytPlayer.playVideo !== 'function') {
                    console.log('YouTube Player not ready yet');
                    return;
                }
                isPlaying ? ytPlayer.pauseVideo() : ytPlayer.playVideo();
            } else {
                const bgMusic = document.getElementById('bgMusic');
                if (!bgMusic) return;
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
                const htmlEl = document.documentElement;
                const bodyEl = document.body;
                
                // Disable scroll snapping temporarily to prevent automatic jump/snapping on unlock
                htmlEl.style.scrollSnapType = 'none';
                bodyEl.style.scrollSnapType = 'none';
                
                overlay.classList.add('fade-out');
                bodyEl.classList.remove('locked');
                htmlEl.classList.remove('locked');
                
                // Scroll straight to the countdown section (or couple if countdown is empty)
                const target = document.getElementById('countdown-section') || document.getElementById('couple');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                // Re-enable scroll snap after transition
                setTimeout(() => {
                    htmlEl.style.scrollSnapType = '';
                    bodyEl.style.scrollSnapType = '';
                }, 1000);
                
                setTimeout(() => overlay.remove(), 1000);
            }
            // Start Music if exists
            toggleMusic();
        }

        // Keep the click fallback
        document.body.addEventListener('click', function initAudio() {
            if (!isPlaying && !document.getElementById('openingOverlay')) toggleMusic();
        }, { once: true });

        // Reveal Animation logic
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasMsg = urlParams.get('msg');
            
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            
            if (!hasMsg) {
                window.scrollTo(0, 0);
            } else {
                // Scroll to the guestbook section immediately after loading
                setTimeout(() => {
                    const gb = document.getElementById('guestbook');
                    if (gb) {
                        const htmlEl = document.documentElement;
                        const bodyEl = document.body;
                        
                        // Temporarily disable scroll snapping to prevent scroll interference
                        htmlEl.style.scrollSnapType = 'none';
                        bodyEl.style.scrollSnapType = 'none';
                        
                        gb.scrollIntoView({ behavior: 'auto', block: 'start' });
                        
                        // Restore scroll snapping after a short delay
                        setTimeout(() => {
                            htmlEl.style.scrollSnapType = '';
                            bodyEl.style.scrollSnapType = '';
                        }, 500);
                    }
                }, 100);
            }
            
            // Navigation smooth scrolling with scroll-snap conflict workaround
            document.querySelectorAll('.nav-item').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const target = document.querySelector(targetId);
                    if (target) {
                        // Temporarily disable scroll snapping to prevent conflicts during smooth scroll
                        const htmlEl = document.documentElement;
                        const bodyEl = document.body;
                        
                        htmlEl.style.scrollSnapType = 'none';
                        bodyEl.style.scrollSnapType = 'none';
                        
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        
                        // Update active state in navigation
                        document.querySelectorAll('.nav-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        
                        // Push history state if possible
                        if (history.pushState) {
                            history.pushState(null, null, targetId);
                        } else {
                            location.hash = targetId;
                        }
                        
                        // Re-enable scroll snapping after scroll completes (approx 800ms)
                        setTimeout(() => {
                            htmlEl.style.scrollSnapType = '';
                            bodyEl.style.scrollSnapType = '';
                        }, 800);
                    }
                });
            });

            // Active Navigation Highlighting on Scroll
            const navItems = document.querySelectorAll('.nav-item');
            const navSections = document.querySelectorAll('section[id]');
            
            const navObserverOptions = {
                root: null,
                rootMargin: '-50% 0px -50% 0px', // Trigger when section occupies the center of the viewport
                threshold: 0
            };
            
            const navObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        
                        // Tentukan href nav-item mana yang harus diaktifkan
                        // Baik section 'hero' (sampul) maupun 'countdown-section' sama-sama
                        // direpresentasikan oleh tombol Home yang kini href-nya #countdown-section
                        let activeHref;
                        if (id === 'hero' || id === 'countdown-section') {
                            activeHref = '#countdown-section';
                        } else {
                            activeHref = `#${id}`;
                        }
                        
                        navItems.forEach(item => {
                            if (item.getAttribute('href') === activeHref) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    }
                });
            }, navObserverOptions);
            
            navSections.forEach(section => navObserver.observe(section));

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
                    if (daysEl) daysEl.textContent = "00";
                    if (hoursEl) hoursEl.textContent = "00";
                    if (minsEl) minsEl.textContent = "00";
                    if (secsEl) secsEl.textContent = "00";
                    if (countdownContainer && !document.getElementById('happyMessage')) {
                        countdownContainer.insertAdjacentHTML('afterend', '<div id="happyMessage" style="margin-top:20px; font-weight:700; letter-spacing:2px; font-size:1.1rem; text-shadow: 1px 1px 5px rgba(0,0,0,0.5);">HARI BAHAGIA TELAH TIBA! 🌸</div>');
                    }
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
        function copyToClipboard(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            
            const toast = document.getElementById('copyToast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>

    <div id="copyToast" class="toast">
        <span>✅ Nomor rekening berhasil disalin!</span>
    </div>

</body>
</html>
