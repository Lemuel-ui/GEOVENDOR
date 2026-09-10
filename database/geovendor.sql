-- ============================================================
-- GeoVendor Database Schema
-- MySQL/MariaDB for XAMPP
-- ============================================================

-- ------------------------------------------------------------
-- USERS
-- MISU Administrator, BPLO Head Officer, BPLO Staff,
-- Business Permit Inspector, Vendor
-- ------------------------------------------------------------

CREATE DATABASE geovendor;
USE geovendor;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM(
        'admin',
        'bplo_staff',
        'head_officer',
        'inspector',
        'vendor'
    ) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- VENDORS
-- Registered businesses with latitude and longitude
-- ------------------------------------------------------------

CREATE TABLE vendors (
    vendor_id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(150) NOT NULL,
    owner_name VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    barangay VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    contact_no VARCHAR(30),

    -- GIS coordinates for Leaflet/OpenStreetMap
    latitude DECIMAL(10,7) NOT NULL,
    longitude DECIMAL(10,7) NOT NULL,

    registered_by INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_user
        FOREIGN KEY (registered_by)
        REFERENCES users(user_id)
        ON DELETE SET NULL
);

CREATE INDEX idx_vendors_latitude ON vendors(latitude);
CREATE INDEX idx_vendors_longitude ON vendors(longitude);

-- ------------------------------------------------------------
-- BUSINESS PERMITS
-- ------------------------------------------------------------

CREATE TABLE permits (
    permit_id INT AUTO_INCREMENT PRIMARY KEY,

    vendor_id INT NOT NULL,

    permit_no VARCHAR(50) UNIQUE NOT NULL,
    issued_date DATE NOT NULL,
    expiry_date DATE NOT NULL,

    status ENUM(
        'active',
        'expiring',
        'expired',
        'revoked'
    ) NOT NULL DEFAULT 'active',

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_permit_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES vendors(vendor_id)
        ON DELETE CASCADE
);

CREATE INDEX idx_permits_expiry
ON permits(expiry_date);

-- ------------------------------------------------------------
-- INSPECTIONS
-- Field visits performed by Business Permit Inspectors
-- ------------------------------------------------------------

CREATE TABLE inspections (
    inspection_id INT AUTO_INCREMENT PRIMARY KEY,

    vendor_id INT NOT NULL,
    inspector_id INT NOT NULL,

    scheduled_date DATE NOT NULL,

    status ENUM(
        'pending',
        'completed',
        'flagged'
    ) NOT NULL DEFAULT 'pending',

    remarks TEXT,

    completed_at DATETIME,

    CONSTRAINT fk_inspection_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES vendors(vendor_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_inspection_inspector
        FOREIGN KEY (inspector_id)
        REFERENCES users(user_id)
        ON DELETE RESTRICT
);

-- ------------------------------------------------------------
-- NOTIFICATIONS
-- Renewal reminders and system alerts
-- ------------------------------------------------------------

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT,
    permit_id INT,

    type ENUM(
        'info',
        'success',
        'warning',
        'danger'
    ) NOT NULL,

    message TEXT NOT NULL,

    is_read TINYINT(1) NOT NULL DEFAULT 0,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_notification_permit
        FOREIGN KEY (permit_id)
        REFERENCES permits(permit_id)
        ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- BACKUP LOGS
-- Administrator's Backup & Restore module
-- ------------------------------------------------------------

CREATE TABLE backup_logs (
    backup_id INT AUTO_INCREMENT PRIMARY KEY,

    file_path VARCHAR(255) NOT NULL,

    backup_type ENUM(
        'automatic',
        'manual'
    ) NOT NULL,

    file_size_mb DECIMAL(10,2),

    status VARCHAR(20) NOT NULL DEFAULT 'completed',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);