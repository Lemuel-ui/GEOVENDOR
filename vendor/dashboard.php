<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['vendor']);
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$myBusiness = get_vendor_for_user($pdo, (int)$_SESSION['user_id']);
$notifications = get_notifications($pdo, 5);
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</h1>
        <p>Here is the current status of your registered business.</p>
    </div>
</div>

<?php if (!$myBusiness): ?>
    <div class="card"><div class="card-body">No business record is linked to your account yet. Please visit the BPLO office to complete your registration.</div></div>
<?php else: ?>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card" style="--stat-color:var(--color-primary);">
        <div class="stat-label">Business Name</div>
        <div class="stat-value" style="font-size:1.25rem;"><?php echo htmlspecialchars($myBusiness['name']); ?></div>
        <div class="stat-sub">Barangay <?php echo htmlspecialchars($myBusiness['barangay']); ?></div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-success);">
        <div class="stat-label">Permit No.</div>
        <div class="stat-value mono" style="font-size:1.25rem;"><?php echo htmlspecialchars($myBusiness['permit_no']); ?></div>
        <div class="stat-sub">Expires <?php echo htmlspecialchars($myBusiness['expiry']); ?></div>
    </div>
    <div class="stat-card" style="--stat-color:var(--color-accent);">
        <div class="stat-label">Permit Status</div>
        <div class="stat-value" style="font-size:1.25rem;"><span class="pin-status <?php echo $myBusiness['permit_status']; ?>"><?php echo ucfirst($myBusiness['permit_status']); ?></span></div>
        <div class="stat-sub">Renew before it lapses</div>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Recent Updates</h3></div>
    <div>
        <?php foreach ($notifications as $n): ?>
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

<?php include __DIR__ . '/../components/footer.php'; ?>
