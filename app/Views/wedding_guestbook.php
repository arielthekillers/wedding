<?php
$activeTab = 'guestbook';
include __DIR__ . '/partials/wedding_nav.php';

$messages = Models\Wedding::getMessages($wedding['id']);
$gbCount  = count($messages);
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">🗑️ Pesan berhasil dihapus.</div>
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
            <div style="width:42px;height:42px;border-radius:50%;background:var(--grad);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1rem;flex-shrink:0;">
                <?= mb_strtoupper(mb_substr($msg['name'], 0, 1)) ?>
            </div>
            <!-- Content -->
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                    <strong style="font-size:0.93rem;"><?= htmlspecialchars($msg['name']) ?></strong>
                    <span style="font-size:0.72rem;color:#94a3b8;"><?= htmlspecialchars($msg['email'] ?? '') ?></span>
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
            </div>
            <!-- Delete -->
            <a href="?action=deletemsg&msg_id=<?= $msg['id'] ?>"
               onclick="return confirm('Hapus pesan dari <?= htmlspecialchars(addslashes($msg['name'])) ?>?')"
               style="color:#dc2626;font-size:0.78rem;font-weight:600;text-decoration:none;padding:5px 10px;border-radius:7px;background:#fee2e2;white-space:nowrap;flex-shrink:0;">
                🗑️ Hapus
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</div><!-- /.mgmt-content -->
</body>
</html>
