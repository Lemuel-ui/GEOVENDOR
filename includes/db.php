<?php
/**
 * db.php
 * -----------------------------------------------------------------------
<<<<<<< HEAD
 * MySQL / MariaDB (XAMPP) connection using PDO.
 *
 * Replaces the old PostgreSQL + PostGIS stub. get_db() returns a shared
 * PDO instance connected to the `geovendor` database defined in
 * includes/config.php. Every page includes this (via includes/functions.php)
 * and calls get_db() to run prepared-statement queries.
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/config.php';

function get_db(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die(
                '<div style="font-family:sans-serif;max-width:640px;margin:4rem auto;padding:1.5rem 2rem;' .
                'border:1px solid #C1443C;border-radius:8px;background:#FFF5F4;color:#5B2B27;">' .
                '<h2 style="margin-top:0;">Database connection failed</h2>' .
                '<p>GeoVendor could not connect to the MySQL/MariaDB database.</p>' .
                '<ol><li>Start <strong>Apache</strong> and <strong>MySQL</strong> in the XAMPP Control Panel.</li>' .
                '<li>Open phpMyAdmin and import <code>database/geovendor.sql</code> if you have not already.</li>' .
                '<li>Check the credentials in <code>includes/config.php</code>.</li></ol>' .
                '<p class="mono" style="font-size:.85rem;color:#8a1f1f;">' . htmlspecialchars($e->getMessage()) . '</p>' .
                '</div>'
            );
        }
    }

    return $pdo;
=======
 * PostgreSQL + PostGIS connection stub.
 *
 * This prototype runs entirely on dummy arrays (see dummy_data.php) so
 * it works out of the box on XAMPP without a database. When the real
 * GeoVendor backend is implemented, replace the body of get_db() with
 * a real PDO connection, e.g.:
 *
 *   $dsn  = "pgsql:host=localhost;port=5432;dbname=geovendor";
 *   $pdo  = new PDO($dsn, 'geovendor_user', 'geovendor_pass', [
 *       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 *   ]);
 *
 * PostGIS-specific notes for the real implementation:
 *  - Vendor coordinates should be stored as a `geography(Point,4326)`
 *    column, e.g. ST_SetSRID(ST_MakePoint(lng, lat), 4326).
 *  - Use ST_AsGeoJSON(location) when sending vendor pins to Leaflet.js.
 * -----------------------------------------------------------------------
 */

function get_db() {
    // Prototype mode: no live database connection is made.
    // Returning null keeps every page functional using dummy_data.php.
    return null;
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
}
