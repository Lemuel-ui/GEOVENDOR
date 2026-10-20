<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['inspector']);
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$myInspections = get_inspections_for_inspector($pdo, (int)$_SESSION['user_id']);
$pending = array_filter($myInspections, fn($i) => $i['status'] === 'pending');
$completed = array_filter($myInspections, fn($i) => $i['status'] === 'completed');
$flagged = array_filter($myInspections, fn($i) => $i['status'] === 'flagged');
=======
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$pending = array_filter($DUMMY_INSPECTIONS, fn($i) => $i['status'] === 'Pending');
$completed = array_filter($DUMMY_INSPECTIONS, fn($i) => $i['status'] === 'Completed');
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
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
<<<<<<< HEAD
        <div class="stat-sub">On record</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-danger);">
        <div class="stat-label">Flagged for Review</div>
        <div class="stat-value"><?php echo count($flagged); ?></div>
        <div class="stat-sub">Needs BPLO follow-up</div>
=======
        <div class="stat-sub">This month</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-danger);">
        <div class="stat-label">Expired Permits Found</div>
        <div class="stat-value">1</div>
        <div class="stat-sub">Flagged during inspection</div>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>My Assigned Inspections</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead><tr><th>Inspection ID</th><th>Vendor</th><th>Barangay</th><th>Scheduled Date</th><th>Status</th><th></th></tr></thead>
            <tbody>
<<<<<<< HEAD
            <?php foreach ($myInspections as $ins): ?>
=======
            <?php foreach ($DUMMY_INSPECTIONS as $ins): ?>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($ins['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($ins['vendor']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ins['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($ins['scheduled']); ?></td>
<<<<<<< HEAD
                    <td><span class="pin-status <?php echo $ins['status']; ?>"><?php echo htmlspecialchars($ins['status_label']); ?></span></td>
                    <td><a href="map.php" class="btn btn-outline btn-sm">Locate on Map</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$myInspections): ?><tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);">No inspections assigned yet.</td></tr><?php endif; ?>
=======
                    <td><span class="pin-status <?php echo strtolower($ins['status']); ?>"><?php echo htmlspecialchars($ins['status']); ?></span></td>
                    <td><a href="map.php" class="btn btn-outline btn-sm">Locate on Map</a></td>
                </tr>
            <?php endforeach; ?>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
