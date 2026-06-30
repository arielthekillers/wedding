<?php
$activeTab = 'guestbook';
include __DIR__ . '/partials/wedding_nav.php';

$messages = Models\Wedding::getMessages($wedding['id']);
$gbCount  = count($messages);
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">Pesan berhasil dihapus.</div>
<?php endif; ?>
<?php if (isset($_GET['replied'])): ?>
<div class="alert alert-success">Balasan berhasil disimpan.</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-card-header" style="justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 text-blue-600"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div>
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
        <div class="flex justify-center text-blue-600 mb-3"><svg viewBox="0 0 24 24" width="40" height="40" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg></div>
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
                    <span style="background:#f1f5f9;color:#475569;font-size:0.7rem;font-weight:600;padding:2px 9px;border-radius:20px; display:inline-flex; align-items:center; gap:2px;" title="Kota / Kantor / Circle">
                        <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-2.5 h-2.5 inline-block"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg> <?= htmlspecialchars($msg['circle']) ?>
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
                   style="color:#dc2626;font-size:0.78rem;font-weight:600;text-decoration:none;padding:5px 10px;border-radius:7px;background:#fee2e2;white-space:nowrap;width:100%;text-align:center; display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                    <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus
                </a>
                <button type="button" onclick="toggleReplyForm(<?= $msg['id'] ?>)"
                        style="color:var(--primary);font-size:0.78rem;font-weight:600;border:none;cursor:pointer;padding:5px 10px;border-radius:7px;background:#ede9fe;white-space:nowrap;width:100%; display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                    <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Balas
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
