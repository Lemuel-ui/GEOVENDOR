<?php
/**
 * login/index.php
 * -----------------------------------------------------------------------
 * Handles the "Access the System (Login)" use case shared by all five
 * actors (MISU Administrator, BPLO Head Officer, BPLO Staff, Business
 * Permit Inspector, Vendor). Authenticates against the demo accounts in
 * dummy_data.php — swap for a real password_verify() + DB lookup later.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dummy_data.php';

$error = '';

// Friendly message for redirected users (expired session / wrong role)
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'session_expired') $error = 'Please log in to continue.';
    if ($_GET['msg'] === 'unauthorized')    $error = 'You are not authorized to view that page.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } elseif (!isset($DUMMY_USERS[$email]) || $DUMMY_USERS[$email]['password'] !== $password) {
        $error = 'Invalid email or password.';
    } else {
        $user = $DUMMY_USERS[$email];
        $_SESSION['user_id'] = $email;
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        header('Location: ' . ROLE_HOME[$user['role']]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log In · GeoVendor</title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/auth.css">
</head>
<body>
<div class="auth-shell">

    <!-- Left: map-plate visual -->
    <div class="auth-visual">
        <div class="brand">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#E8871E" stroke-width="1.8">
                <path d="M12 22s7-7.58 7-12A7 7 0 0 0 5 10c0 4.42 7 12 7 12z" fill="rgba(232,135,30,0.2)"/>
                <circle cx="12" cy="10" r="2.6" fill="#E8871E" stroke="none"/>
            </svg>
            <div>
                <div class="brand-text">GeoVendor</div>
                <div class="brand-sub">Municipality of Manolo Fortich</div>
            </div>
        </div>

        <div class="pitch">
            <h1>Know where every vendor is. Know every permit's status.</h1>
            <p>GeoVendor brings interactive vendor mapping and business permit monitoring into one platform for the Business Permits and Licensing Office (BPLO) &mdash; built with Leaflet.js and PostGIS.</p>
        </div>

        <div>
            <div class="pin-legend">
                <span><i style="background:#2E8B57"></i> Active Permit</span>
                <span><i style="background:#E8871E"></i> Expiring Soon</span>
                <span><i style="background:#C1443C"></i> Expired</span>
            </div>
            <p class="footnote" style="margin-top:1.25rem;">Capstone Project &middot; Institute for Computer Studies &middot; Northern Bukidnon State College</p>
        </div>
    </div>

    <!-- Right: login form -->
    <div class="auth-form-col">
        <div class="auth-card">
            <div class="kicker">BPLO Portal Access</div>
            <h2>Welcome back</h2>
            <p class="sub">Sign in with your GeoVendor account to continue.</p>

            <?php if ($error): ?>
                <div class="auth-alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input class="form-control" type="email" id="email" name="email" placeholder="you@manolofortich.gov.ph" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Log In</button>
            </form>
                <table>
                    <tr><td class="role">Administrator</td><td>admin@manolofortich.gov.ph</td></tr>
                    <tr><td class="role">BPLO Staff</td><td>staff@manolofortich.gov.ph</td></tr>
                    <tr><td class="role">Head Officer</td><td>head@manolofortich.gov.ph</td></tr>
                    <tr><td class="role">Inspector</td><td>inspector@manolofortich.gov.ph</td></tr>
                    <tr><td class="role">Vendor</td><td>vendor@manolofortich.gov.ph</td></tr>
                </table>
                <p class="form-hint">Password for account: <code>geovendor2026</code></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
