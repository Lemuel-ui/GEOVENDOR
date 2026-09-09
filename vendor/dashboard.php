<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['vendor']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
// In a real system this would be looked up by the logged-in vendor's account.
// For the prototype we show the first sample vendor record.
$myBusiness = $DUMMY_VENDORS[0];
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</h1>
        <p>Here is the current status of your registered business.</p>
    </div>
</div>

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

<div class="card">
    <div class="card-header"><h3>Recent Updates</h3></div>
    <div>
        <?php foreach (array_slice($DUMMY_NOTIFICATIONS, 3, 2) as $n): ?>
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
