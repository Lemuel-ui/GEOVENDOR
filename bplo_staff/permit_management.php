<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['bplo_staff']);
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Permit Management';
$activePage = 'permits';
$renewed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renew_permit_id'])) {
    $permitId = (int)$_POST['renew_permit_id'];
    $newExpiry = $_POST['new_expiry'] ?: date('Y-m-d', strtotime('+1 year'));
    renew_permit($pdo, $permitId, $newExpiry);
    $renewed = true;
}

$vendors = get_all_vendors($pdo);
=======
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Permit Management';
$activePage = 'permits';
$renewed = isset($_POST['renew_permit_id']);
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Permit Management</h1>
        <p>Track and update the status of every business permit on record.</p>
    </div>
</div>

<?php if ($renewed): ?>
    <div class="card" style="border-left:4px solid var(--color-success); margin-bottom:1.25rem;">
<<<<<<< HEAD
        <div class="card-body">Permit <strong class="mono"><?php echo htmlspecialchars($_POST['permit_no_label'] ?? ''); ?></strong> was marked as renewed and its expiry date was updated.</div>
=======
        <div class="card-body">Permit <strong class="mono"><?php echo htmlspecialchars($_POST['renew_permit_id']); ?></strong> was marked as renewed. (Prototype only.)</div>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>All Business Permits</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead>
                <tr><th>Permit No.</th><th>Vendor</th><th>Barangay</th><th>Expiry Date</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
<<<<<<< HEAD
            <?php foreach ($vendors as $v): ?>
=======
            <?php foreach ($DUMMY_VENDORS as $v): ?>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($v['permit_no']); ?></td>
                    <td><strong><?php echo htmlspecialchars($v['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($v['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($v['expiry']); ?></td>
                    <td><span class="pin-status <?php echo $v['permit_status']; ?>"><?php echo ucfirst($v['permit_status']); ?></span></td>
                    <td>
<<<<<<< HEAD
                        <?php if ($v['permit_status'] !== 'active' && $v['permit_id']): ?>
                        <form method="POST" style="display:flex;gap:.4rem;align-items:center;">
                            <input type="hidden" name="renew_permit_id" value="<?php echo $v['permit_id']; ?>">
                            <input type="hidden" name="permit_no_label" value="<?php echo htmlspecialchars($v['permit_no']); ?>">
                            <input type="date" name="new_expiry" class="form-control btn-sm" style="max-width:150px;" value="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
=======
                        <?php if ($v['permit_status'] !== 'active'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="renew_permit_id" value="<?php echo htmlspecialchars($v['permit_no']); ?>">
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                            <button type="submit" class="btn btn-accent btn-sm">Mark as Renewed</button>
                        </form>
                        <?php else: ?>
                            <span class="badge-soft">Up to date</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
