<?php
/**
 * navbar.php
 * -----------------------------------------------------------------------
 * Renders the sticky top bar. Expects the including page to have set:
 *   $pageTitle        (string) shown on the left of the topbar
 * Reads $_SESSION['name'] / $_SESSION['role'] for the user chip and
 * $DUMMY_NOTIFICATIONS (from dummy_data.php) for the bell dropdown count.
 * -----------------------------------------------------------------------
 */

$userName = $_SESSION['name'] ?? 'Guest User';
$roleKey  = $_SESSION['role'] ?? 'vendor';
$initials = '';
foreach (explode(' ', $userName) as $part) {
    $initials .= strtoupper(substr($part, 0, 1));
}
$initials = substr($initials, 0, 2);
$notifCount = isset($DUMMY_NOTIFICATIONS) ? count($DUMMY_NOTIFICATIONS) : 0;
?>
<header class="topbar">
    <div style="display:flex;align-items:center;gap:.9rem;">
        <button id="gvSidebarToggle" class="btn-outline btn btn-sm" style="display:none;" aria-label="Toggle navigation">☰</button>
        <div class="topbar-title"><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?></div>
    </div>
    <div class="topbar-actions">
        <button class="bell-btn" aria-label="Notifications" title="<?php echo $notifCount; ?> notifications">
            <?php if ($notifCount > 0): ?><span class="bell-dot"></span><?php endif; ?>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </button>
        <div class="user-chip">
            <div class="user-avatar"><?php echo htmlspecialchars($initials ?: 'GV'); ?></div>
            <div class="user-meta">
                <div class="name"><?php echo htmlspecialchars($userName); ?></div>
                <div class="role"><?php echo htmlspecialchars(ROLE_LABELS[$roleKey] ?? ''); ?></div>
            </div>
        </div>
    </div>
</header>
