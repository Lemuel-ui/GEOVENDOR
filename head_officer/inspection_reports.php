<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['head_officer']);
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Inspection Reports';
$activePage = 'inspections';
$inspections = get_all_inspections($pdo);
=======
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Inspection Reports';
$activePage = 'inspections';
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Inspection Reports</h1>
        <p>Review field inspections carried out by Business Permit Inspectors.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead>
<<<<<<< HEAD
                <tr><th>Inspection ID</th><th>Vendor</th><th>Barangay</th><th>Scheduled Date</th><th>Status</th><th>Remarks</th></tr>
            </thead>
            <tbody>
            <?php foreach ($inspections as $ins): ?>
=======
                <tr><th>Inspection ID</th><th>Vendor</th><th>Barangay</th><th>Scheduled Date</th><th>Status</th></tr>
            </thead>
            <tbody>
            <?php foreach ($DUMMY_INSPECTIONS as $ins): ?>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($ins['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($ins['vendor']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ins['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($ins['scheduled']); ?></td>
<<<<<<< HEAD
                    <td><span class="pin-status <?php echo $ins['status']; ?>"><?php echo htmlspecialchars($ins['status_label']); ?></span></td>
                    <td><?php echo $ins['remarks'] ? htmlspecialchars($ins['remarks']) : '<span class="form-hint">—</span>'; ?></td>
=======
                    <td><span class="pin-status <?php echo strtolower($ins['status']); ?>"><?php echo htmlspecialchars($ins['status']); ?></span></td>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
