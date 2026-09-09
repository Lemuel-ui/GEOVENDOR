<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'User Accounts';
$activePage = 'accounts';
$created = isset($_POST['create_account']);
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>User Accounts</h1>
        <p>Create and manage login accounts for BPLO Staff, Head Officers, Inspectors, and Vendors.</p>
    </div>
</div>

<?php if ($created): ?>
    <div class="card" style="border-left:4px solid var(--color-success); margin-bottom:1.25rem;">
        <div class="card-body">Account for <strong><?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?></strong> was created. (Prototype only — no data was written.)</div>
    </div>
<?php endif; ?>

<div class="row" style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.25rem;align-items:start;">
    <div class="card">
        <div class="card-header"><h3>All Accounts</h3></div>
        <div class="card-body" style="padding:0;">
            <table class="gv-table">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($DUMMY_USERS as $email => $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                        <td class="mono"><?php echo htmlspecialchars($email); ?></td>
                        <td><span class="badge-soft"><?php echo htmlspecialchars(ROLE_LABELS[$u['role']]); ?></span></td>
                        <td><span class="pin-status active">Active</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">Edit</button>
                            <button class="btn btn-danger-outline btn-sm" onclick="return gvConfirmDelete('Deactivate this account?');">Deactivate</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Create Account</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="full_name">Full Name</label>
                    <input class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="role">Assign Role</label>
                    <select class="form-control" id="role" name="role">
                        <option value="bplo_staff">BPLO Staff</option>
                        <option value="head_officer">BPLO Head Officer</option>
                        <option value="inspector">Business Permit Inspector</option>
                        <option value="vendor">Vendor</option>
                        <option value="admin">MISU Administrator</option>
                    </select>
                </div>
                <button type="submit" name="create_account" class="btn btn-primary" style="width:100%;justify-content:center;">Create Account</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
