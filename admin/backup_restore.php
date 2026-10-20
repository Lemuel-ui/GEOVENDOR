<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['admin']);
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Backup & Restore';
$activePage = 'backup';
$action = $_POST['action'] ?? '';
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'backup') {
        $result = run_backup($pdo, 'manual');
        $message = 'A new backup snapshot was created successfully: ' . $result['file'];
    } elseif ($action === 'restore') {
        $path = $_POST['snapshot'] ?? '';
        $result = run_restore($pdo, $path);
        if ($result['ok']) {
            $message = 'System restore from the selected snapshot completed.';
        } else {
            $message = 'Restore failed: ' . $result['error'];
            $messageType = 'danger';
        }
    }
}

$backups = get_backup_logs($pdo);
=======
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Backup & Restore';
$activePage = 'backup';
$action = $_POST['action'] ?? '';
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Backup &amp; Restore</h1>
<<<<<<< HEAD
        <p>Protect the integrity of vendor, permit, and account data stored in MySQL/MariaDB.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="card" style="border-left:4px solid var(--color-<?php echo $messageType; ?>); margin-bottom:1.25rem;">
        <div class="card-body"><?php echo htmlspecialchars($message); ?></div>
=======
        <p>Protect the integrity of vendor, permit, and account data stored in PostgreSQL/PostGIS.</p>
    </div>
</div>

<?php if ($action === 'backup'): ?>
    <div class="card" style="border-left:4px solid var(--color-success); margin-bottom:1.25rem;">
        <div class="card-body">A new backup snapshot was created successfully. (Prototype only — no real database was touched.)</div>
    </div>
<?php elseif ($action === 'restore'): ?>
    <div class="card" style="border-left:4px solid var(--color-info); margin-bottom:1.25rem;">
        <div class="card-body">System restore from the selected snapshot completed. (Prototype only.)</div>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
    </div>
<?php endif; ?>

<div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <div class="card">
        <div class="card-header"><h3>Create Backup</h3></div>
        <div class="card-body">
<<<<<<< HEAD
            <p style="color:var(--color-text-muted);font-size:.9rem;">Generates a full snapshot of vendor records, permit data, and user accounts as a SQL file under <code>/backups</code>.</p>
=======
            <p style="color:var(--color-text-muted);font-size:.9rem;">Generates a full snapshot of vendor records, permit data, and user accounts.</p>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
            <form method="POST">
                <input type="hidden" name="action" value="backup">
                <button type="submit" class="btn btn-primary">Run Backup Now</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Restore from Snapshot</h3></div>
        <div class="card-body">
<<<<<<< HEAD
            <?php if ($backups): ?>
=======
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="snapshot">Choose a Backup Snapshot</label>
                    <select class="form-control" id="snapshot" name="snapshot">
<<<<<<< HEAD
                        <?php foreach ($backups as $b): ?>
                            <option value="<?php echo htmlspecialchars($b['file_path']); ?>">
                                <?php echo htmlspecialchars(date('Y-m-d h:i A', strtotime($b['created_at']))); ?> &mdash; <?php echo ucfirst($b['backup_type']); ?> Backup
                            </option>
                        <?php endforeach; ?>
=======
                        <option>2026-07-29 02:00 AM — Nightly Auto Backup</option>
                        <option>2026-07-28 02:00 AM — Nightly Auto Backup</option>
                        <option>2026-07-25 09:14 AM — Manual Backup</option>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                    </select>
                </div>
                <input type="hidden" name="action" value="restore">
                <button type="submit" class="btn btn-outline" onclick="return gvConfirmDelete('Restoring will overwrite current data with the selected snapshot. Continue?');">Restore System</button>
            </form>
<<<<<<< HEAD
            <?php else: ?>
                <p class="form-hint">No backups yet. Run one first.</p>
            <?php endif; ?>
=======
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
        </div>
    </div>
</div>

<div class="card" style="margin-top:1.25rem;">
    <div class="card-header"><h3>Backup History</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead><tr><th>Date &amp; Time</th><th>Type</th><th>Size</th><th>Status</th></tr></thead>
            <tbody>
<<<<<<< HEAD
                <?php foreach ($backups as $b): ?>
                <tr>
                    <td class="mono"><?php echo htmlspecialchars(date('Y-m-d h:i A', strtotime($b['created_at']))); ?></td>
                    <td><?php echo ucfirst($b['backup_type']); ?></td>
                    <td><?php echo $b['file_size_mb'] !== null ? number_format((float)$b['file_size_mb'], 2) . ' MB' : '—'; ?></td>
                    <td><span class="pin-status completed"><?php echo htmlspecialchars(ucfirst($b['status'])); ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$backups): ?><tr><td colspan="4" style="text-align:center;color:var(--color-text-muted);">No backups recorded yet.</td></tr><?php endif; ?>
=======
                <tr><td class="mono">2026-07-29 02:00 AM</td><td>Automatic</td><td>18.4 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
                <tr><td class="mono">2026-07-28 02:00 AM</td><td>Automatic</td><td>18.2 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
                <tr><td class="mono">2026-07-25 09:14 AM</td><td>Manual</td><td>17.9 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
