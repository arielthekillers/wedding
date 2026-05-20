<?php
$activeTab = 'guestbook';
include __DIR__ . '/partials/wedding_nav.php';

$messages = Models\Wedding::getMessages($wedding['id']);
$gbCount  = count($messages);
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">🗑️ Pesan berhasil dihapus.</div>
<?php endif; ?>
<?php if (isset($_GET['replied'])): ?>
<div class="alert alert-success">💬 Balasan berhasil disimpan.</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-card-header" style="justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="icon">📝</div>
            <div>
                <h2>Buku Tamu</h2>
                <p>Pesan dan ucapan dari tamu undangan</p>
            </div>
        </div>
        <span style="background:#ede9fe;color:var(--primary);font-weight:700;font-size:0.82rem;padding:5px 14px;border-radius:20px;">
            <?= $gbCount ?> pesan
        </span>
    </div>

    <?php if (empty($messages)): ?>
    <div style="text-align:center;padding:60px 20px;">
        <div style="font-size:3rem;margin-bottom:12px;">📭</div>
        <h3 style="margin:0 0 6px;color:#475569;">Belum ada pesan</h3>
        <p style="color:#94a3b8;margin:0;">Tamu belum menulis ucapan di buku tamu.</p>
    </div>
    <?php else: ?>
    <div style="padding:0 24px 24px;">
        <?php foreach ($messages as $msg):
            $att = match((string)($msg['attendance'] ?? '1')) {
                '1'  => ['label'=>'Hadir', 'color'=>'#dcfce7','text'=>'#166534'],
                '0'  => ['label'=>'Tidak Hadir', 'color'=>'#fee2e2','text'=>'#991b1b'],
                default => ['label'=>'Ragu-ragu','color'=>'#fff7ed','text'=>'#92400e'],
            };
        ?>
        <div style="border:1px solid var(--border);border-radius:12px;padding:16px 18px;margin-top:14px;background:white;display:flex;gap:16px;align-items:flex-start;">
            <!-- Avatar -->
            <img src="<?= Models\Wedding::gravatarUrl($msg['email'] ?? '', 42) ?>" alt="avatar" width="42" height="42" style="border-radius:50%; flex-shrink:0; object-fit:cover; border:2px solid <?= $att['color'] ?>;">
            <!-- Content -->
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                    <strong style="font-size:0.93rem;"><?= htmlspecialchars($msg['name']) ?></strong>
                    <span style="font-size:0.72rem;color:#94a3b8;"><?= htmlspecialchars($msg['email'] ?? '') ?></span>
                    <?php if (!empty($msg['circle'])): ?>
                    <span style="background:#f1f5f9;color:#475569;font-size:0.7rem;font-weight:600;padding:2px 9px;border-radius:20px;" title="Kota / Kantor / Circle">
                        📍 <?= htmlspecialchars($msg['circle']) ?>
                    </span>
                    <?php endif; ?>
                    <span style="background:<?= $att['color'] ?>;color:<?= $att['text'] ?>;font-size:0.7rem;font-weight:700;padding:2px 9px;border-radius:20px;">
                        <?= $att['label'] ?>
                    </span>
                </div>
                <p style="margin:0;color:#475569;font-size:0.88rem;line-height:1.5;">
                    <?= htmlspecialchars($msg['message'] ?? '') ?>
                </p>
                <div style="margin-top:6px;font-size:0.75rem;color:#94a3b8;">
                    <?= isset($msg['created_at']) ? date('d M Y, H:i', strtotime($msg['created_at'])) : '' ?>
                </div>

                <!-- Existing Reply -->
                <?php if (!empty($msg['reply'])): ?>
                <div style="margin-top:12px; background:#f4f6fb; padding:12px; border-radius:8px; border-left:3px solid var(--primary);">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <strong style="font-size:0.8rem; color:var(--primary);">Balasan Anda:</strong>
                        <?php if (!empty($msg['replied_at'])): ?>
                            <span style="font-size:0.7rem; color:#94a3b8;"><?= date('d M Y, H:i', strtotime($msg['replied_at'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <p style="margin:4px 0 0; font-size:0.85rem; color:#475569;"><?= nl2br(htmlspecialchars($msg['reply'])) ?></p>
                </div>
                <?php endif; ?>

                <!-- Reply Form (Hidden by default) -->
                <form method="POST" id="reply-form-<?= $msg['id'] ?>" style="display:none; margin-top:12px; width:100%;">
                    <input type="hidden" name="action" value="replymsg">
                    <input type="hidden" name="msg_id" value="<?= $msg['id'] ?>">
                    <textarea name="reply" class="form-control" placeholder="Tulis balasan Anda..." style="font-size:0.85rem; padding:10px; min-height:70px;" required><?= htmlspecialchars($msg['reply'] ?? '') ?></textarea>
                    <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:8px;">
                        <button type="button" class="btn btn-sm btn-ghost" onclick="toggleReplyForm(<?= $msg['id'] ?>)">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary">Kirim Balasan</button>
                    </div>
                </form>
            </div>
            <!-- Actions -->
            <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end; flex-shrink:0;">
                <a href="?action=deletemsg&msg_id=<?= $msg['id'] ?>"
                   onclick="return confirm('Hapus pesan dari <?= htmlspecialchars(addslashes($msg['name'])) ?>?')"
                   style="color:#dc2626;font-size:0.78rem;font-weight:600;text-decoration:none;padding:5px 10px;border-radius:7px;background:#fee2e2;white-space:nowrap;width:100%;text-align:center;">
                    🗑️ Hapus
                </a>
                <button type="button" onclick="toggleReplyForm(<?= $msg['id'] ?>)"
                        style="color:var(--primary);font-size:0.78rem;font-weight:600;border:none;cursor:pointer;padding:5px 10px;border-radius:7px;background:#ede9fe;white-space:nowrap;width:100%;">
                    💬 Balas
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form.style.display === 'none') {
        form.style.display = 'block';
        form.querySelector('textarea').focus();
    } else {
        form.style.display = 'none';
    }
}
</script>

</div><!-- /.mgmt-content -->
</body>
</html>
