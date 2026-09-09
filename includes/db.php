<?php
/**
 * db.php
 * -----------------------------------------------------------------------
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
}
