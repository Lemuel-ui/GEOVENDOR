<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['inspector']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$pending = array_filter($DUMMY_INSPECTIONS, fn($i) => $i['status'] === 'Pending');
$completed = array_filter($DUMMY_INSPECTIONS, fn($i) => $i['status'] === 'Completed');
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Field Inspection Overview</h1>
        <p>Your assigned inspections for the Municipality of Manolo Fortich.</p>
    </div>
    <a href="map.php" class="btn btn-accent">Open Inspection Map</a>
</div>

<div class="stat-grid">
    <div class="stat-card" style="--stat-color:var(--color-info);">
        <div class="stat-label">Pending Inspections</div>
        <div class="stat-value"><?php echo count($pending); ?></div>
        <div class="stat-sub">Awaiting field visit</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-success);">
        <div class="stat-label">Completed</div>
        <div class="stat-value"><?php echo count($completed); ?></div>
        <div class="stat-sub">This month</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-danger);">
        <div class="stat-label">Expired Permits Found</div>
        <div class="stat-value">1</div>
        <div class="stat-sub">Flagged during inspection</div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>My Assigned Inspections</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead><tr><th>Inspection ID</th><th>Vendor</th><th>Barangay</th><th>Scheduled Date</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($DUMMY_INSPECTIONS as $ins): ?>
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($ins['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($ins['vendor']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ins['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($ins['scheduled']); ?></td>
                    <td><span class="pin-status <?php echo strtolower($ins['status']); ?>"><?php echo htmlspecialchars($ins['status']); ?></span></td>
                    <td><a href="map.php" class="btn btn-outline btn-sm">Locate on Map</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
