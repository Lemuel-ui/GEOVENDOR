# GeoVendor
### Web-Based Vendor Mapping and Business Permit Monitoring System
Business Permits and Licensing Office (BPLO) — Municipality of Manolo Fortich, Bukidnon

A full-stack PHP application for vendor mapping and business permit monitoring,
built to run entirely on **XAMPP** (Apache + PHP + MySQL/MariaDB) — no
PostgreSQL or PostGIS required.

## Tech stack

| Layer            | Technology                              |
|-------------------|------------------------------------------|
| Local server      | XAMPP (Apache + MySQL/MariaDB)          |
| Back-end          | PHP 8+ (PDO, prepared statements)        |
| Database          | MySQL / MariaDB (via phpMyAdmin)         |
| Front-end         | HTML5, CSS3, Bootstrap 5.3, vanilla JS   |
| GIS mapping       | Leaflet.js 1.9 + OpenStreetMap tiles     |
| Dev environment   | VS Code                                  |

## Folder structure

```
GeoVendor/
├── assets/
│   ├── css/            → style.css (design system), auth.css (login screen)
│   ├── js/              → main.js (shared UI behavior), map.js (Leaflet helper)
│
├── components/           → navbar.php, sidebar.php, footer.php, head_open.php
├── includes/              → config.php, db.php, functions.php, auth_check.php
├── database/             → geovendor.sql (MySQL/MariaDB schema + seed data)
├── backups/               → generated SQL backups (created by Backup & Restore)
├── uploads/inspections/   → inspection photos uploaded by inspectors
│
├── admin/                 → MISU Administrator pages
├── bplo_staff/            → BPLO Staff pages
├── head_officer/          → BPLO Head Officer pages
├── inspector/              → Business Permit Inspector pages
├── vendor/                 → Vendor pages
│
├── login/index.php        → Shared login screen (all 5 roles)
├── logout/index.php
├── index.php               → Entry point / router
└── README.md
```

## Running locally with XAMPP

1. **Copy the project.** Place the `GeoVendor` folder inside your XAMPP
   `htdocs` directory, e.g. `C:\xampp\htdocs\GeoVendor` (Windows) or
   `/Applications/XAMPP/htdocs/GeoVendor` (macOS) / `/opt/lampp/htdocs/GeoVendor` (Linux).

2. **Start services.** Open the XAMPP Control Panel and start **Apache**
   and **MySQL**.

3. **Create the database.** Open phpMyAdmin at `http://localhost/phpmyadmin`,
   click **Import**, choose `database/geovendor.sql`, and click **Go**.
   This creates the `geovendor` database, all tables, and seeds five demo
   accounts plus sample vendors/permits/inspections. (The script also runs
   `CREATE DATABASE IF NOT EXISTS geovendor`, so you don't need to create
   it manually first.)

4. **Check the DB credentials.** `includes/config.php` defaults to XAMPP's
   out-of-the-box MySQL settings (`root` / no password / `localhost:3306`).
   Update `DB_USER` / `DB_PASS` there if your MySQL install is different.

5. **Make `backups/` and `uploads/inspections/` writable** by the web
   server (they already exist in this project; on Linux/macOS you may need
   `chmod -R 775 backups uploads`).

6. **Open the app.** Visit `http://localhost/GeoVendor/` in your browser.
   You'll be redirected to the login screen.

> If you deploy the project under a different folder name, update the
> `BASE_URL` constant in `includes/config.php`.

## Demo accounts

All demo accounts use the password: **`geovendor2026`**

| Role                        | Email                              |
|------------------------------|--------------------------------------|
| MISU Administrator           | admin@manolofortich.gov.ph          |
| BPLO Staff                   | staff@manolofortich.gov.ph          |
| BPLO Head Officer            | head@manolofortich.gov.ph           |
| Business Permit Inspector    | inspector@manolofortich.gov.ph      |
| Vendor                       | vendor@manolofortich.gov.ph         |

Passwords are stored as bcrypt hashes in `users.password_hash` and checked
with PHP's `password_verify()` — nothing is stored or compared in plain text.

## Modules implemented per role

- **MISU Administrator** — Dashboard, User Accounts (create/deactivate real
  accounts), Backup & Restore (generates real SQL snapshots under
  `/backups`), Vendor Map
- **BPLO Staff** — Dashboard, Vendor Registration (map pin-drop, writes to
  `vendors` + `permits`), Vendor Management (edit / deactivate), Permit
  Management (renew with a new expiry date), Vendor Map
- **BPLO Head Officer** — Dashboard, Permit Monitoring, Inspection Reports,
  Vendor Map
- **Business Permit Inspector** — Dashboard, Inspection Map, Inspection
  Management (mark completed/flagged, add remarks, upload a photo)
- **Vendor** — Dashboard, Business Profile, Permit Status (scoped to the
  vendor record linked to their account via `vendors.vendor_user_id`)

## Database notes

- Vendor coordinates are stored as plain `DECIMAL(10,7)` `latitude` /
  `longitude` columns — there is no PostGIS `geography` type and no
  `ST_*` spatial functions anywhere in the schema or code. Leaflet.js
  reads these columns directly.
- Permit "status" (active / expiring / expired) is computed live from
  `permits.expiry_date` (see `permit_display_status()` in
  `includes/functions.php`) so the dashboards always reflect today's date,
  unless a permit has been explicitly revoked.
- All queries go through PDO prepared statements
  (`PDO::ATTR_EMULATE_PREPARES => false`) in `includes/functions.php`.
- To reset the demo data, re-import `database/geovendor.sql` — it drops
  and recreates every table.

## Notes

- This is a working PHP + MySQL application: form submissions persist to
  the database (vendor registration, permit renewal, account creation,
  inspection updates, photo uploads, backups) rather than only showing
  confirmation banners.
- All seed data (vendors, permits, inspections, notifications, accounts)
  is sample data representative of Manolo Fortich, Bukidnon.
