<?php
/**
 * db.php
 * -----------------------------------------------------------------------
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
}
