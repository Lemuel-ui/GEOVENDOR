<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['inspector']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Inspection Management';
$activePage = 'inspections';
$updated = isset($_POST['update_inspection_id']);
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Inspection Management</h1>
        <p>Update the results of your field inspections.</p>
    </div>
</div>

<?php if ($updated): ?>
    <div class="card" style="border-left:4px solid var(--color-success); margin-bottom:1.25rem;">
        <div class="card-body">Inspection <strong class="mono"><?php echo htmlspecialchars($_POST['update_inspection_id']); ?></strong> was updated to "<?php echo htmlspecialchars($_POST['new_status'] ?? ''); ?>". (Prototype only.)</div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Assigned Inspections</h3></div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table">
            <thead><tr><th>Inspection ID</th><th>Vendor</th><th>Barangay</th><th>Scheduled</th><th>Status</th><th>Update</th></tr></thead>
            <tbody>
            <?php foreach ($DUMMY_INSPECTIONS as $ins): ?>
                <tr>
                    <td class="mono"><?php echo htmlspecialchars($ins['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($ins['vendor']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ins['barangay']); ?></td>
                    <td><?php echo htmlspecialchars($ins['scheduled']); ?></td>
                    <td><span class="pin-status <?php echo strtolower($ins['status']); ?>"><?php echo htmlspecialchars($ins['status']); ?></span></td>
                    <td>
                        <?php if ($ins['status'] === 'Pending'): ?>
                        <form method="POST" style="display:flex;gap:.4rem;">
                            <input type="hidden" name="update_inspection_id" value="<?php echo htmlspecialchars($ins['id']); ?>">
                            <select name="new_status" class="form-control btn-sm" style="max-width:150px;">
                                <option>Completed</option>
                                <option>Flagged for Review</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </form>
                        <?php else: ?>
                            <span class="badge-soft">No action needed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
