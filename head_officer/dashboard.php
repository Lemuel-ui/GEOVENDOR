<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['head_officer']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$counts = get_permit_counts($DUMMY_VENDORS);
$pendingInspections = array_filter($DUMMY_INSPECTIONS, fn($i) => $i['status'] === 'Pending');
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Supervisory Overview</h1>
        <p>Municipality-wide permit compliance and inspection status at a glance.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card" style="--stat-color:var(--color-primary);">
        <div class="stat-label">Total Vendors</div>
        <div class="stat-value"><?php echo count($DUMMY_VENDORS); ?></div>
        <div class="stat-sub">Across all barangays</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-success);">
        <div class="stat-label">Compliance Rate</div>
        <div class="stat-value"><?php echo round(($counts['active'] / count($DUMMY_VENDORS)) * 100); ?>%</div>
        <div class="stat-sub">Vendors with active permits</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-accent);">
        <div class="stat-label">Expiring Soon</div>
        <div class="stat-value"><?php echo $counts['expiring']; ?></div>
        <div class="stat-sub">Requires renewal follow-up</div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-info);">
        <div class="stat-label">Pending Inspections</div>
        <div class="stat-value"><?php echo count($pendingInspections); ?></div>
        <div class="stat-sub">Assigned to inspectors</div>
    </div>
</div>

<div class="row" style="display:grid;grid-template-columns:1.6fr 1fr;gap:1.25rem;align-items:start;">
    <div class="card">
        <div class="card-header">
            <h3>Permit Compliance by Barangay</h3>
            <a href="permit_monitoring.php" class="btn btn-outline btn-sm">Full Monitoring View</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="gv-table">
                <thead><tr><th>Barangay</th><th>Vendors</th><th>Active</th><th>Expiring</th><th>Expired</th></tr></thead>
                <tbody>
                <?php
                $byBarangay = [];
                foreach ($DUMMY_VENDORS as $v) {
                    $byBarangay[$v['barangay']]['total'] = ($byBarangay[$v['barangay']]['total'] ?? 0) + 1;
                    $byBarangay[$v['barangay']][$v['permit_status']] = ($byBarangay[$v['barangay']][$v['permit_status']] ?? 0) + 1;
                }
                foreach ($byBarangay as $brgy => $data): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($brgy); ?></strong></td>
                        <td><?php echo $data['total']; ?></td>
                        <td><?php echo $data['active'] ?? 0; ?></td>
                        <td><?php echo $data['expiring'] ?? 0; ?></td>
                        <td><?php echo $data['expired'] ?? 0; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Recent Inspection Reports</h3></div>
        <div>
            <?php foreach (array_slice($DUMMY_INSPECTIONS, 0, 4) as $ins): ?>
                <div class="notif-item">
                    <span class="notif-dot <?php echo $ins['status'] === 'Completed' ? 'success' : 'info'; ?>"></span>
                    <div>
                        <div class="notif-text"><strong><?php echo htmlspecialchars($ins['vendor']); ?></strong> &mdash; <?php echo htmlspecialchars($ins['status']); ?></div>
                        <div class="notif-time">Scheduled <?php echo htmlspecialchars($ins['scheduled']); ?> &middot; Brgy. <?php echo htmlspecialchars($ins['barangay']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
