-- =============================================================================
-- GeoVendor Database Schema
<<<<<<< HEAD
-- MySQL / MariaDB 10.4+ (XAMPP) — phpMyAdmin compatible
-- -----------------------------------------------------------------------------
-- Converted from the original PostgreSQL + PostGIS schema. Spatial data is
-- now stored as plain DECIMAL latitude/longitude columns (no PostGIS
-- extension, no GEOGRAPHY type, no ST_* functions).
--
-- Import this file through phpMyAdmin (XAMPP Control Panel -> Admin -> Import)
-- or via the command line:
--   mysql -u root -p geovendor < geovendor.sql
-- The CREATE DATABASE statement below will create the `geovendor` schema
-- for you if it does not already exist.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS geovendor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE geovendor;
=======
-- PostgreSQL 15+ with PostGIS 3.3+ extension
-- -----------------------------------------------------------------------------
-- This schema matches the data structures used by the prototype's dummy
-- data (includes/dummy_data.php). Connect the prototype to a real database
-- by implementing includes/db.php against these tables.
-- =============================================================================

CREATE EXTENSION IF NOT EXISTS postgis;
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22

-- ---------------------------------------------------------------------------
-- Users: MISU Administrator, BPLO Head Officer, BPLO Staff,
--        Business Permit Inspector, Vendor
-- ---------------------------------------------------------------------------
<<<<<<< HEAD
DROP TABLE IF EXISTS backup_logs;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS inspection_photos;
DROP TABLE IF EXISTS inspections;
DROP TABLE IF EXISTS permits;
DROP TABLE IF EXISTS vendors;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','bplo_staff','head_officer','inspector','vendor') NOT NULL,
    is_active     TINYINT(1) NOT NULL DEFAULT 1,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- Vendors: registered businesses with map location (plain lat/lng, no GIS)
-- ---------------------------------------------------------------------------
CREATE TABLE vendors (
    vendor_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    business_name  VARCHAR(150) NOT NULL,
    owner_name     VARCHAR(150) NOT NULL,
    category       VARCHAR(100),
    barangay       VARCHAR(100) NOT NULL,
    address        VARCHAR(255),
    contact_no     VARCHAR(30),
    latitude       DECIMAL(10,7) NOT NULL,   -- pin dropped on Leaflet.js map
    longitude      DECIMAL(10,7) NOT NULL,   -- pin dropped on Leaflet.js map
    registered_by  INT UNSIGNED NULL,
    vendor_user_id INT UNSIGNED NULL,        -- links to the vendor's own login account
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_vendors_registered_by FOREIGN KEY (registered_by) REFERENCES users(user_id) ON DELETE SET NULL,
    CONSTRAINT fk_vendors_user FOREIGN KEY (vendor_user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_vendors_latlng (latitude, longitude),
    INDEX idx_vendors_barangay (barangay)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
=======
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
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22

-- ---------------------------------------------------------------------------
-- Business Permits: one active/historical permit trail per vendor
-- ---------------------------------------------------------------------------
CREATE TABLE permits (
<<<<<<< HEAD
    permit_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id    INT UNSIGNED NOT NULL,
    permit_no    VARCHAR(50) NOT NULL UNIQUE,
    issued_date  DATE NOT NULL,
    expiry_date  DATE NOT NULL,
    status       ENUM('active','expiring','expired','revoked') NOT NULL DEFAULT 'active',
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_permits_vendor FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id) ON DELETE CASCADE,
    INDEX idx_permits_expiry (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
=======
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
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22

-- ---------------------------------------------------------------------------
-- Inspections: field visits performed by Business Permit Inspectors
-- ---------------------------------------------------------------------------
CREATE TABLE inspections (
<<<<<<< HEAD
    inspection_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id       INT UNSIGNED NOT NULL,
    inspector_id    INT UNSIGNED NOT NULL,
    scheduled_date  DATE NOT NULL,
    status          ENUM('pending','completed','flagged') NOT NULL DEFAULT 'pending',
    remarks         TEXT NULL,
    completed_at    TIMESTAMP NULL,
    CONSTRAINT fk_inspections_vendor FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id) ON DELETE CASCADE,
    CONSTRAINT fk_inspections_inspector FOREIGN KEY (inspector_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- Inspection Photos: field photos uploaded by inspectors
-- ---------------------------------------------------------------------------
CREATE TABLE inspection_photos (
    photo_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inspection_id INT UNSIGNED NOT NULL,
    file_path     VARCHAR(255) NOT NULL,
    uploaded_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_photos_inspection FOREIGN KEY (inspection_id) REFERENCES inspections(inspection_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
=======
    inspection_id   SERIAL PRIMARY KEY,
    vendor_id       INTEGER NOT NULL REFERENCES vendors(vendor_id) ON DELETE CASCADE,
    inspector_id    INTEGER NOT NULL REFERENCES users(user_id),
    scheduled_date  DATE NOT NULL,
    status          VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN
                      ('pending', 'completed', 'flagged')),
    remarks         TEXT,
    completed_at    TIMESTAMP
);
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22

-- ---------------------------------------------------------------------------
-- Notifications: renewal reminders and system alerts
-- ---------------------------------------------------------------------------
CREATE TABLE notifications (
<<<<<<< HEAD
    notification_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNSIGNED NULL,
    permit_id        INT UNSIGNED NULL,
    type             ENUM('info','success','warning','danger') NOT NULL DEFAULT 'info',
    message          TEXT NOT NULL,
    is_read          TINYINT(1) NOT NULL DEFAULT 0,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_notif_permit FOREIGN KEY (permit_id) REFERENCES permits(permit_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- System backups log (Administrator's Backup & Restore module)
-- ---------------------------------------------------------------------------
CREATE TABLE backup_logs (
    backup_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    file_path    VARCHAR(255) NOT NULL,
    backup_type  ENUM('automatic','manual') NOT NULL DEFAULT 'manual',
    file_size_mb DECIMAL(10,2) NULL,
    status       VARCHAR(20) NOT NULL DEFAULT 'completed',
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE business_location_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    requested_address VARCHAR(255) NOT NULL,
    requested_barangay VARCHAR(100) NOT NULL,
    requested_lat DECIMAL(10,7) NOT NULL,
    requested_lng DECIMAL(10,7) NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected')
        NOT NULL DEFAULT 'Pending',
    requested_by INT NOT NULL,
    reviewed_by INT DEFAULT NULL,
    review_note TEXT DEFAULT NULL,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_location_request_status (status),
    INDEX idx_location_request_business (business_id)
);

-- =============================================================================
-- Seed data — five demo accounts (one per role) and sample vendor / permit /
-- inspection / notification records around Manolo Fortich, Bukidnon.
-- Password for every demo account: geovendor2026
-- =============================================================================

INSERT INTO users (full_name, email, password_hash, role) VALUES
('Rhea Fernandez',     'admin@manolofortich.gov.ph',     '$2y$12$fZDpSy.UMMMHRQ0NHAW7nuOxoNqXF4A0gUFxW8n1akzbN1XN3SZ4u', 'admin'),
('Jomar Villareal',    'staff@manolofortich.gov.ph',     '$2y$12$fZDpSy.UMMMHRQ0NHAW7nuOxoNqXF4A0gUFxW8n1akzbN1XN3SZ4u', 'bplo_staff'),
('Atty. Liza Cabahug', 'head@manolofortich.gov.ph',      '$2y$12$fZDpSy.UMMMHRQ0NHAW7nuOxoNqXF4A0gUFxW8n1akzbN1XN3SZ4u', 'head_officer'),
('Ronel Dagooc',       'inspector@manolofortich.gov.ph', '$2y$12$fZDpSy.UMMMHRQ0NHAW7nuOxoNqXF4A0gUFxW8n1akzbN1XN3SZ4u', 'inspector'),
('Cristy Amora',       'vendor@manolofortich.gov.ph',    '$2y$12$fZDpSy.UMMMHRQ0NHAW7nuOxoNqXF4A0gUFxW8n1akzbN1XN3SZ4u', 'vendor');

-- vendor_user_id links "Cristy Amora" (vendor login) to her own vendor record below
INSERT INTO vendors (business_name, owner_name, category, barangay, address, contact_no, latitude, longitude, registered_by, vendor_user_id) VALUES
('Amora Sari-Sari Store',   'Cristy Amora',        'Retail / Sari-Sari Store',          'Poblacion',  'Purok 3, Poblacion',   '0917-234-5567', 8.3706000, 124.8681000, 2, 5),
('Villareal Eatery',        'Jomar Villareal Sr.', 'Food / Carinderia',                 'Alae',       'Zone 2, Alae',         '0918-556-2231', 8.3892000, 124.8452000, 2, NULL),
('Dagooc Hardware Supply',  'Ronel Dagooc',        'Hardware / Construction Supplies',  'Dahilayan',  'Sitio Centro, Dahilayan','0920-874-1120', 8.3255000, 124.8590000, 2, NULL),
('Cabahug Fresh Produce',   'Liza Cabahug',        'Wet Market / Vegetables',           'Sankanan',   'Public Market, Sankanan','0906-441-9082', 8.3781000, 124.8734000, 2, NULL),
('Fernandez Auto Parts',    'Rhea Fernandez',      'Automotive Parts',                  'Poblacion',  'National Highway, Poblacion','0933-120-4478', 8.3722000, 124.8659000, 2, NULL),
('Sto. Niño Bakery',        'Marites Oyao',        'Food / Bakery',                     'Damilag',    'Purok 1, Damilag',     '0945-661-7734', 8.3610000, 124.8517000, 2, NULL);

-- Permit expiry dates are relative to CURDATE() so the demo data always
-- shows a realistic mix of active / expiring / expired permits.
INSERT INTO permits (vendor_id, permit_no, issued_date, expiry_date, status) VALUES
(1, 'BP-2026-0341', DATE_SUB(CURDATE(), INTERVAL 225 DAY), DATE_ADD(CURDATE(), INTERVAL 140 DAY), 'active'),
(2, 'BP-2025-1187', DATE_SUB(CURDATE(), INTERVAL 345 DAY), DATE_ADD(CURDATE(), INTERVAL 20 DAY),  'active'),
(3, 'BP-2025-0892', DATE_SUB(CURDATE(), INTERVAL 400 DAY), DATE_SUB(CURDATE(), INTERVAL 30 DAY),  'expired'),
(4, 'BP-2026-0455', DATE_SUB(CURDATE(), INTERVAL 210 DAY), DATE_ADD(CURDATE(), INTERVAL 160 DAY), 'active'),
(5, 'BP-2025-0765', DATE_SUB(CURDATE(), INTERVAL 335 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY),  'active'),
(6, 'BP-2026-0198', DATE_SUB(CURDATE(), INTERVAL 190 DAY), DATE_ADD(CURDATE(), INTERVAL 200 DAY), 'active');

-- Inspections assigned to the demo inspector account (user_id 4 = Ronel Dagooc)
INSERT INTO inspections (vendor_id, inspector_id, scheduled_date, status, remarks, completed_at) VALUES
(2, 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'pending',   NULL, NULL),
(3, 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 'pending',   NULL, NULL),
(5, 4, DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'pending',   NULL, NULL),
(1, 4, DATE_SUB(CURDATE(), INTERVAL 9 DAY), 'completed', 'Business permit posted and visible. Sanitation compliant.', DATE_SUB(NOW(), INTERVAL 9 DAY));

INSERT INTO notifications (user_id, permit_id, type, message) VALUES
(NULL, 2, 'warning', 'Permit BP-2025-1187 (Villareal Eatery) is expiring soon.'),
(NULL, 3, 'danger',  'Permit BP-2025-0892 (Dagooc Hardware Supply) has expired.'),
(NULL, 5, 'warning', 'Permit BP-2025-0765 (Fernandez Auto Parts) is expiring soon.'),
(NULL, NULL, 'success', 'Vendor "Sto. Niño Bakery" was successfully registered.'),
(NULL, NULL, 'info', 'Inspection at Amora Sari-Sari Store was marked as Completed.');

INSERT INTO backup_logs (file_path, backup_type, file_size_mb, status, created_at) VALUES
('backups/geovendor_20260729_0200.sql', 'automatic', 18.40, 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('backups/geovendor_20260728_0200.sql', 'automatic', 18.20, 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('backups/geovendor_20260725_0914.sql', 'manual',    17.90, 'completed', DATE_SUB(NOW(), INTERVAL 6 DAY));

-- Example query: fetch vendors for Leaflet.js (plain lat/lng, no PostGIS)
-- SELECT vendor_id, business_name, latitude, longitude FROM vendors WHERE is_active = 1;
=======
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
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
