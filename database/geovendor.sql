-- =============================================================================
-- GeoVendor Database Schema
-- PostgreSQL 15+ with PostGIS 3.3+ extension
-- -----------------------------------------------------------------------------
-- This schema matches the data structures used by the prototype's dummy
-- data (includes/dummy_data.php). Connect the prototype to a real database
-- by implementing includes/db.php against these tables.
-- =============================================================================

CREATE EXTENSION IF NOT EXISTS postgis;

-- ---------------------------------------------------------------------------
-- Users: MISU Administrator, BPLO Head Officer, BPLO Staff,
--        Business Permit Inspector, Vendor
-- ---------------------------------------------------------------------------
CREATE TABLE users (
    user_id       SERIAL PRIMARY KEY,
    full_name     VARCHAR(150) NOT NULL,
    email         VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(20) NOT NULL CHECK (role IN
                    ('admin', 'bplo_staff', 'head_officer', 'inspector', 'vendor')),
    is_active     BOOLEAN NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ---------------------------------------------------------------------------
-- Vendors: registered businesses with spatial location
-- ---------------------------------------------------------------------------
CREATE TABLE vendors (
    vendor_id     SERIAL PRIMARY KEY,
    business_name VARCHAR(150) NOT NULL,
    owner_name    VARCHAR(150) NOT NULL,
    category      VARCHAR(100),
    barangay      VARCHAR(100) NOT NULL,
    address       VARCHAR(255),
    contact_no    VARCHAR(30),
    location      GEOGRAPHY(POINT, 4326) NOT NULL, -- lat/lng pin from Leaflet.js
    registered_by INTEGER REFERENCES users(user_id),
    created_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_vendors_location ON vendors USING GIST (location);

-- ---------------------------------------------------------------------------
-- Business Permits: one active/historical permit trail per vendor
-- ---------------------------------------------------------------------------
CREATE TABLE permits (
    permit_id     SERIAL PRIMARY KEY,
    vendor_id     INTEGER NOT NULL REFERENCES vendors(vendor_id) ON DELETE CASCADE,
    permit_no     VARCHAR(50) UNIQUE NOT NULL,
    issued_date   DATE NOT NULL,
    expiry_date   DATE NOT NULL,
    status        VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN
                    ('active', 'expiring', 'expired', 'revoked')),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_permits_expiry ON permits (expiry_date);

-- ---------------------------------------------------------------------------
-- Inspections: field visits performed by Business Permit Inspectors
-- ---------------------------------------------------------------------------
CREATE TABLE inspections (
    inspection_id   SERIAL PRIMARY KEY,
    vendor_id       INTEGER NOT NULL REFERENCES vendors(vendor_id) ON DELETE CASCADE,
    inspector_id    INTEGER NOT NULL REFERENCES users(user_id),
    scheduled_date  DATE NOT NULL,
    status          VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN
                      ('pending', 'completed', 'flagged')),
    remarks         TEXT,
    completed_at    TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Notifications: renewal reminders and system alerts
-- ---------------------------------------------------------------------------
CREATE TABLE notifications (
    notification_id SERIAL PRIMARY KEY,
    user_id         INTEGER REFERENCES users(user_id),
    permit_id       INTEGER REFERENCES permits(permit_id),
    type            VARCHAR(20) NOT NULL CHECK (type IN
                      ('info', 'success', 'warning', 'danger')),
    message         TEXT NOT NULL,
    is_read         BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ---------------------------------------------------------------------------
-- System backups log (for the Administrator's Backup & Restore module)
-- ---------------------------------------------------------------------------
CREATE TABLE backup_logs (
    backup_id    SERIAL PRIMARY KEY,
    file_path    VARCHAR(255) NOT NULL,
    backup_type  VARCHAR(20) NOT NULL CHECK (backup_type IN ('automatic', 'manual')),
    file_size_mb NUMERIC(10,2),
    status       VARCHAR(20) NOT NULL DEFAULT 'completed',
    created_at   TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Example spatial query: fetch vendors as GeoJSON for Leaflet.js
-- SELECT vendor_id, business_name, ST_AsGeoJSON(location) AS geom FROM vendors;
