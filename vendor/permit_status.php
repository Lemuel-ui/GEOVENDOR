<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['vendor']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Permit Status';
$activePage = 'permit';
$myBusiness = $DUMMY_VENDORS[0];
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Permit Status</h1>
        <p>Track the current standing and renewal timeline of your business permit.</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3><?php echo htmlspecialchars($myBusiness['name']); ?></h3></div>
    <div class="card-body">
        <table class="gv-table">
            <tbody>
                <tr><th style="width:220px;">Permit Number</th><td class="mono"><?php echo htmlspecialchars($myBusiness['permit_no']); ?></td></tr>
                <tr><th>Current Status</th><td><span class="pin-status <?php echo $myBusiness['permit_status']; ?>"><?php echo ucfirst($myBusiness['permit_status']); ?></span></td></tr>
                <tr><th>Expiry Date</th><td><?php echo htmlspecialchars($myBusiness['expiry']); ?></td></tr>
                <tr><th>Barangay</th><td><?php echo htmlspecialchars($myBusiness['barangay']); ?></td></tr>
            </tbody>
        </table>

        <div class="auth-alert info" style="margin-top:1.5rem;">
            You will automatically receive an email/in-app reminder 30 days before your permit expires. Visit the BPLO office at Manolo Fortich Municipal Hall to process your renewal.
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
