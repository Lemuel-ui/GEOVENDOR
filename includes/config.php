<?php
/**
 * config.php
 * -----------------------------------------------------------------------
 * Central configuration file for GeoVendor.
 * Starts the session, defines BASE_URL, role labels/home pages, and the
 * MySQL/MariaDB (XAMPP) database credentials used by includes/db.php.
 * -----------------------------------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjust this if you deploy the project under a different folder name
// e.g. http://localhost/GeoVendor
define('BASE_URL', '/GeoVendor');

// ---------------------------------------------------------------------------
// Database connection settings (XAMPP defaults: host=localhost, user=root,
// empty password, default MySQL port 3306). Update if your XAMPP MySQL
// install uses different credentials.
// ---------------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'geovendor');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Where inspection photos are stored (must be writable by the web server)
define('UPLOAD_DIR', __DIR__ . '/../uploads/inspections/');
define('UPLOAD_URL', BASE_URL . '/uploads/inspections/');

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
