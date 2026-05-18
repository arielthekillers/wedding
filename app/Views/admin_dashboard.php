<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#8b5cf6; --secondary:#ec4899; --grad:linear-gradient(135deg,#8b5cf6,#ec4899); --bg:#f4f6fb; --card:#fff; --text:#1e293b; --border:#e2e8f0; }
        * { box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; background:var(--bg); color:var(--text); margin:0; }

        /* TOPBAR */
        .dashboard-topbar {
            background: var(--grad);
            color: white; padding: 0 32px;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
            box-shadow: 0 4px 20px rgba(139,92,246,0.3);
            position: sticky; top: 0; z-index: 100;
        }
        .dashboard-topbar h1 { margin:0; font-size:1.35rem; font-weight:700; letter-spacing:0.3px; }
        .dashboard-topbar h1 span { font-weight:300; opacity:0.8; }
        .btn-new {
            display:inline-flex; align-items:center; gap:6px;
            padding:9px 20px; border-radius:10px;
            background:rgba(255,255,255,0.2); color:white;
            font-weight:700; font-size:0.88rem; text-decoration:none;
            border: 1.5px solid rgba(255,255,255,0.4);
            transition: background 0.2s;
        }
        .btn-new:hover { background:rgba(255,255,255,0.35); color:white; }

        .container { max-width: 1060px; margin: 36px auto; padding: 0 20px 60px; }

        /* STATS */
        .stats-row { display:flex; gap:14px; margin-bottom:28px; flex-wrap:wrap; }
        .stat-card {
            background:var(--card); border-radius:12px; padding:16px 22px;
            border:1px solid var(--border); box-shadow:0 2px 8px rgba(0,0,0,0.05);
            display:flex; align-items:center; gap:14px; flex:1; min-width:180px;
        }
        .stat-icon { font-size:1.6rem; width:44px; height:44px; background:#ede9fe; border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .stat-num { font-size:1.5rem; font-weight:700; color:var(--primary); }
        .stat-label { font-size:0.78rem; color:#64748b; }

        /* SECTION HEADER */
        .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-header h2 { margin:0; font-size:1.05rem; font-weight:700; color:#475569; }

        /* WEDDING CARDS */
        .wedding-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px,1fr)); gap:18px; }
        .wedding-card {
            background:var(--card); border-radius:16px; padding:22px;
            box-shadow:0 4px 14px rgba(0,0,0,0.06); border:1px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .wedding-card:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(0,0,0,0.1); }
        .card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:10px; }
        .card-emoji { font-size:1.8rem; }
        .status-pill {
            font-size:0.7rem; font-weight:700; padding:3px 10px; border-radius:20px; text-transform:uppercase;
        }
        .status-on  { background:#dcfce7; color:#166534; }
        .status-off { background:#fee2e2; color:#991b1b; }
        .card-title { font-size:1.15rem; font-weight:700; margin:0 0 4px; line-height:1.3; }
        .card-url {
            color:var(--primary); font-size:0.82rem; text-decoration:none; font-weight:500;
            display:inline-block; margin-bottom:8px; word-break:break-all;
        }
        .card-url:hover { text-decoration:underline; }
        .card-meta { font-size:0.78rem; color:#94a3b8; }
        .card-actions { margin-top:18px; display:flex; gap:8px; align-items:center; }
        .btn-manage {
            display:inline-flex; align-items:center; gap:5px;
            padding:9px 18px; border-radius:9px;
            background:var(--grad); color:white;
            font-weight:700; font-size:0.85rem; text-decoration:none;
            box-shadow:0 4px 10px rgba(139,92,246,0.3);
            transition:transform 0.2s, box-shadow 0.2s; flex:1; justify-content:center;
        }
        .btn-manage:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(139,92,246,0.4); color:white; }
        .btn-preview {
            display:inline-flex; align-items:center; gap:4px;
            padding:9px 14px; border-radius:9px;
            background:#eff6ff; color:#2563eb;
            font-weight:600; font-size:0.82rem; text-decoration:none;
            border:1px solid #bfdbfe; transition:background 0.2s;
        }
        .btn-preview:hover { background:#dbeafe; color:#2563eb; }
        .btn-del {
            display:inline-flex; align-items:center;
            padding:9px 10px; border-radius:9px;
            background:#fee2e2; color:#dc2626;
            font-size:0.85rem; text-decoration:none; border:1px solid #fecaca;
            transition:background 0.2s;
        }
        .btn-del:hover { background:#fecaca; color:#b91c1c; }

        /* EMPTY */
        .empty-state { text-align:center; padding:70px 20px; background:var(--card); border-radius:16px; border:2px dashed var(--border); grid-column:1/-1; }
        .empty-state .icon { font-size:3.5rem; margin-bottom:14px; }
        .empty-state p { color:#94a3b8; margin:6px 0 20px; }
        .btn-create-big {
            display:inline-flex; align-items:center; gap:6px;
            padding:12px 28px; border-radius:10px;
            background:var(--grad); color:white;
            font-weight:700; font-size:1rem; text-decoration:none;
            box-shadow:0 4px 12px rgba(139,92,246,0.35);
        }
    </style>
</head>
<body>
    <!-- ── TOPBAR ── -->
    <div class="dashboard-topbar">
        <h1>Palovite <span><?= $_SESSION['role'] === 'admin' ? 'Admin' : 'Client' ?></span></h1>
        <div style="display:flex; gap:12px; align-items:center;">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin/users" class="btn-new" style="background:transparent; border-color:transparent;">👥 Manage Users</a>
                <a href="admin/create" class="btn-new">+ Buat Undangan</a>
            <?php endif; ?>
            <a href="<?= $GLOBALS['base'] ?>logout" class="btn-new" style="background:transparent;">Logout</a>
        </div>
    </div>

    <div class="container">

        <!-- ── STATS ROW ── -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon">💍</div>
                <div>
                    <div class="stat-num"><?= count($weddings) ?></div>
                    <div class="stat-label">Total Undangan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div>
                    <div class="stat-num"><?= count(array_filter($weddings, fn($w) => $w['is_enabled'])) ?></div>
                    <div class="stat-label">Undangan Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🌐</div>
                <div>
                    <div class="stat-num"><?= $_SERVER['HTTP_HOST'] ?></div>
                    <div class="stat-label">Server</div>
                </div>
            </div>
        </div>

        <!-- ── WEDDING LIST ── -->
        <div class="section-header">
            <h2>Daftar Undangan</h2>
        </div>

        <div class="wedding-grid">
            <?php if (empty($weddings)): ?>
            <div class="empty-state">
                <div class="icon">💍</div>
                <h3 style="margin:0 0 8px;">Belum ada undangan</h3>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <p>Buat undangan digital pertama Anda sekarang!</p>
                    <a href="admin/create" class="btn-create-big">+ Buat Undangan Pertama</a>
                <?php else: ?>
                    <p>Anda belum memiliki undangan yang terhubung dengan akun ini. Silakan hubungi admin.</p>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <?php foreach ($weddings as $w): ?>
            <div class="wedding-card">
                <div class="card-top">
                    <div class="card-emoji">💍</div>
                    <span class="status-pill <?= $w['is_enabled'] ? 'status-on' : 'status-off' ?>">
                        <?= $w['is_enabled'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </div>
                <div class="card-title"><?= htmlspecialchars($w['title']) ?></div>
                <a href="<?= $w['slug'] ?>" target="_blank" class="card-url">
                    🌐 <?= $_SERVER['HTTP_HOST'] ?>/<?= htmlspecialchars($w['slug']) ?>
                </a>
                <div class="card-meta">
                    📅 <?= $w['start_date'] ?> &nbsp;→&nbsp; <?= $w['end_date'] ?>
                    &nbsp;|&nbsp; 🎨 <?= htmlspecialchars($w['theme_path']) ?>
                </div>
                <div class="card-actions">
                    <a href="admin/detail/<?= $w['id'] ?>" class="btn-manage">⚙️ Kelola</a>
                    <a href="<?= $GLOBALS['base'] ?>preview/<?= $w['slug'] ?>" target="_blank" class="btn-preview">🌐 Preview</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/delete/<?= $w['id'] ?>" class="btn-del"
                       onclick="return confirm('Hapus undangan <?= htmlspecialchars(addslashes($w['title'])) ?>? Semua data akan dihapus.')">🗑️</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
