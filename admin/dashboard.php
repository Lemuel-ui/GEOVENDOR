<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$counts = get_permit_counts($DUMMY_VENDORS);
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>System Administration</h1>
        <p>Monitor overall system health, user accounts, and data integrity.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card" style="--stat-color:var(--color-primary);">
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?php echo count($DUMMY_USERS); ?></div>
        <div class="stat-sub">Across all roles</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-success);">
        <div class="stat-label">Registered Vendors</div>
        <div class="stat-value"><?php echo count($DUMMY_VENDORS); ?></div>
        <div class="stat-sub"><?php echo $counts['active']; ?> with active permits</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-info);">
        <div class="stat-label">Last Backup</div>
        <div class="stat-value" style="font-size:1.3rem;">Jul 29, 2026</div>
        <div class="stat-sub">Automatic nightly backup</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-accent);">
        <div class="stat-label">System Status</div>
        <div class="stat-value" style="font-size:1.3rem;">Operational</div>
        <div class="stat-sub">All services running</div>
    </div>
</div>

<div class="row" style="display:grid;grid-template-columns:1.4fr 1fr;gap:1.25rem;align-items:start;">
    <div class="card">
        <div class="card-header">
            <h3>User Accounts</h3>
            <a href="user_accounts.php" class="btn btn-outline btn-sm">Manage Accounts</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="gv-table">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead>
                <tbody>
                <?php foreach ($DUMMY_USERS as $email => $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                        <td class="mono"><?php echo htmlspecialchars($email); ?></td>
                        <td><span class="badge-soft"><?php echo htmlspecialchars(ROLE_LABELS[$u['role']]); ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>System Activity</h3></div>
        <div>
            <?php foreach (array_slice($DUMMY_NOTIFICATIONS, 0, 5) as $n): ?>
                <div class="notif-item">
                    <span class="notif-dot <?php echo $n['type']; ?>"></span>
                    <div>
                        <div class="notif-text"><?php echo htmlspecialchars($n['message']); ?></div>
                        <div class="notif-time"><?php echo htmlspecialchars($n['time']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
