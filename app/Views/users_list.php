<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Palovite</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#8b5cf6; --secondary:#ec4899; --grad:linear-gradient(135deg,#8b5cf6,#ec4899); --bg:#f4f6fb; --card:#fff; --text:#1e293b; --border:#e2e8f0; }
        * { box-sizing:border-box; margin: 0; padding: 0; font-family:'Outfit',sans-serif; }
        body { background:var(--bg); color:var(--text); }

        /* TOPBAR */
        .dashboard-topbar {
            background: var(--grad);
            color: white; padding: 0 32px;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
            box-shadow: 0 4px 20px rgba(139,92,246,0.3);
            position: sticky; top: 0; z-index: 100;
        }
        .dashboard-topbar h1 { font-size:1.35rem; font-weight:700; }
        .dashboard-topbar h1 span { font-weight:300; opacity:0.8; }
        
        .topbar-right { display: flex; gap: 12px; align-items: center; }
        .btn-outline {
            padding: 8px 16px; border-radius: 8px; color: white;
            border: 1px solid rgba(255,255,255,0.4); text-decoration: none; font-size: 0.9rem;
            transition: 0.2s;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.2); }

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header-flex h2 { font-size: 1.5rem; font-weight: 700; color: #334155; }
        
        .card {
            background: var(--card); border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04); border: 1px solid var(--border);
            margin-bottom: 30px;
        }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid var(--border); }
        th { font-weight: 600; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        td { font-size: 0.95rem; color: #334155; }
        tr:last-child td { border-bottom: none; }
        
        .role-badge {
            padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        }
        .role-admin { background: #fee2e2; color: #991b1b; }
        .role-client { background: #e0e7ff; color: #3730a3; }

        .btn-del { color: #ef4444; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .btn-del:hover { text-decoration: underline; }

        /* FORM */
        .form-row { display: flex; gap: 16px; align-items: flex-end; }
        .form-group { flex: 1; }
        .form-label { display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #64748b; }
        .form-control {
            width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border);
            font-size: 0.95rem; outline: none; transition: 0.2s;
        }
        .form-control:focus { border-color: var(--primary); }
        .btn-primary {
            background: var(--primary); color: white; border: none; padding: 11px 20px;
            border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;
        }
        .btn-primary:hover { background: #7c3aed; }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    </style>
</head>
<body>
    <div class="dashboard-topbar">
        <h1>Palovite <span>Admin</span></h1>
        <div class="topbar-right">
            <a href="<?= $GLOBALS['base'] ?>admin" class="btn-outline">&larr; Dashboard</a>
            <a href="<?= $GLOBALS['base'] ?>logout" class="btn-outline">Logout</a>
        </div>
    </div>

    <div class="container">
        
        <div class="header-flex">
            <h2>Manajemen User (Klien)</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Berhasil menyimpan data user!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Create User Form -->
        <div class="card">
            <h3 style="margin-bottom:16px; font-size:1.1rem; color:#334155;">Tambah Klien Baru</h3>
            <form action="<?= $GLOBALS['base'] ?>admin/users" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Hanya huruf dan angka">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Biarkan kosong agar = username">
                    </div>
                    <div class="form-group" style="max-width: 150px;">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="client">Client</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">+ Tambah</button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Dibuat Pada</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($u['username']) ?></td>
                        <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                        <td style="color:#64748b;"><?= date('d M Y H:i', strtotime($u['created_at'])) ?></td>
                        <td style="text-align: right;">
                            <?php if ($u['username'] !== 'admin' && $u['id'] !== $_SESSION['user_id']): ?>
                                <a href="<?= $GLOBALS['base'] ?>admin/users?action=delete&id=<?= $u['id'] ?>" class="btn-del" onclick="return confirm('Hapus user ini? Wedding yang terkait dengan user ini akan di-set menjadi tanpa pemilik.')">Hapus</a>
                            <?php else: ?>
                                <span style="color:#cbd5e1; font-size:0.85rem;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
