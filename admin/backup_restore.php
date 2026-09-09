<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Backup & Restore';
$activePage = 'backup';
$action = $_POST['action'] ?? '';
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Backup &amp; Restore</h1>
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
    </div>
<?php endif; ?>

<div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <div class="card">
        <div class="card-header"><h3>Create Backup</h3></div>
        <div class="card-body">
            <p style="color:var(--color-text-muted);font-size:.9rem;">Generates a full snapshot of vendor records, permit data, and user accounts.</p>
            <form method="POST">
                <input type="hidden" name="action" value="backup">
                <button type="submit" class="btn btn-primary">Run Backup Now</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Restore from Snapshot</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="snapshot">Choose a Backup Snapshot</label>
                    <select class="form-control" id="snapshot" name="snapshot">
                        <option>2026-07-29 02:00 AM — Nightly Auto Backup</option>
                        <option>2026-07-28 02:00 AM — Nightly Auto Backup</option>
                        <option>2026-07-25 09:14 AM — Manual Backup</option>
                    </select>
                </div>
                <input type="hidden" name="action" value="restore">
                <button type="submit" class="btn btn-outline" onclick="return gvConfirmDelete('Restoring will overwrite current data with the selected snapshot. Continue?');">Restore System</button>
            </form>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1.25rem;">
    <div class="card-header"><h3>Backup History</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead><tr><th>Date &amp; Time</th><th>Type</th><th>Size</th><th>Status</th></tr></thead>
            <tbody>
                <tr><td class="mono">2026-07-29 02:00 AM</td><td>Automatic</td><td>18.4 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
                <tr><td class="mono">2026-07-28 02:00 AM</td><td>Automatic</td><td>18.2 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
                <tr><td class="mono">2026-07-25 09:14 AM</td><td>Manual</td><td>17.9 MB</td><td><span class="pin-status completed">Completed</span></td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
