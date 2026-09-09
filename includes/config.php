<?php
/**
 * config.php
 * -----------------------------------------------------------------------
 * Central configuration file for GeoVendor.
 * In production this would hold DB credentials pulled from environment
 * variables. For this prototype we only start the session and define
 * a BASE_URL constant so links/assets work regardless of folder depth.
 * -----------------------------------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjust this if you deploy the project under a different folder name
// e.g. http://localhost/GeoVendor
define('BASE_URL', '/GeoVendor');

// Human-readable labels for each role stored in $_SESSION['role']
define('ROLE_LABELS', [
    'admin'        => 'MISU Administrator',
    'bplo_staff'   => 'BPLO Staff',
    'head_officer' => 'BPLO Head Officer',
    'inspector'    => 'Business Permit Inspector',
    'vendor'       => 'Vendor',
]);

// Landing dashboard for each role after login
define('ROLE_HOME', [
    'admin'        => BASE_URL . '/admin/dashboard.php',
    'bplo_staff'   => BASE_URL . '/bplo_staff/dashboard.php',
    'head_officer' => BASE_URL . '/head_officer/dashboard.php',
    'inspector'    => BASE_URL . '/inspector/dashboard.php',
    'vendor'       => BASE_URL . '/vendor/dashboard.php',
]);
