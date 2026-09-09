<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['bplo_staff']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Permit Management';
$activePage = 'permits';
$renewed = isset($_POST['renew_permit_id']);
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
        <div class="card-body">Permit <strong class="mono"><?php echo htmlspecialchars($_POST['renew_permit_id']); ?></strong> was marked as renewed. (Prototype only.)</div>
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
            <?php foreach ($DUMMY_VENDORS as $v): ?>
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($v['permit_no']); ?></td>
                    <td><strong><?php echo htmlspecialchars($v['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($v['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($v['expiry']); ?></td>
                    <td><span class="pin-status <?php echo $v['permit_status']; ?>"><?php echo ucfirst($v['permit_status']); ?></span></td>
                    <td>
                        <?php if ($v['permit_status'] !== 'active'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="renew_permit_id" value="<?php echo htmlspecialchars($v['permit_no']); ?>">
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
