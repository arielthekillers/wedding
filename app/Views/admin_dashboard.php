<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --secondary: #ec4899;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 2rem;
            margin: 0;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.5);
        }
        .wedding-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #e2e8f0;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-on { background: #dcfce7; color: #166534; }
        .status-off { background: #fee2e2; color: #991b1b; }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 10px 0;
        }
        .card-link {
            color: var(--primary);
            font-size: 0.9rem;
            text-decoration: none;
            word-break: break-all;
        }
        .card-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }
        .btn-edit { background: #f1f5f9; color: #475569; }
        .btn-delete { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .empty-state { text-align: center; padding: 50px; background: white; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Wedding Manager</h1>
            <a href="admin/create" class="btn btn-primary">+ New Invitation</a>
        </header>

        <div class="wedding-grid">
            <?php if (empty($weddings)): ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <p>No wedding invitations yet. Click "New Invitation" to get started!</p>
                </div>
            <?php else: ?>
                <?php foreach ($weddings as $w): ?>
                    <div class="card">
                        <span class="status-badge <?= $w['is_enabled'] ? 'status-on' : 'status-off' ?>">
                            <?= $w['is_enabled'] ? 'Enabled' : 'Disabled' ?>
                        </span>
                        <div class="card-title"><?= $w['title'] ?></div>
                        <a href="<?= $w['slug'] ?>" target="_blank" class="card-link">
                            <?= $_SERVER['HTTP_HOST'] ?>/<?= $w['slug'] ?>
                        </a>
                        <p style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">
                            Theme: <?= $w['theme_path'] ?> | <?= $w['start_date'] ?> to <?= $w['end_date'] ?>
                        </p>
                        <div class="card-actions">
                            <a href="admin/edit/<?= $w['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                            <a href="admin/guests/<?= $w['id'] ?>" class="btn btn-sm" style="background:#ede9fe; color:#7c3aed; border:1px solid #ddd8fe;">📋 Tamu</a>
                            <a href="admin/delete/<?= $w['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Silahkan konfirmasi penghapusan?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
