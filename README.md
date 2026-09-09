# GeoVendor
### Web-Based Vendor Mapping and Business Permit Monitoring System
Business Permits and Licensing Office (BPLO) — Municipality of Manolo Fortich, Bukidnon

This is a **front-end/UI prototype** built to accompany the GeoVendor capstone
proposal. It demonstrates every module and user role described in the
research paper using PHP includes, Bootstrap 5, and Leaflet.js, running on
**dummy/sample data** — no live database is required to explore it.

## Tech stack

| Layer            | Technology                              |
|-------------------|------------------------------------------|
| Front-end         | HTML5, CSS3, Bootstrap 5.3, vanilla JS   |
| GIS mapping       | Leaflet.js 1.9 + OpenStreetMap tiles     |
| Back-end          | PHP 8.2+                                 |
| Database (target) | PostgreSQL 15+ with PostGIS 3.3+         |
| Local server      | XAMPP (Apache + PHP)                     |

## Folder structure

```
GeoVendor/
├── assets/
│   ├── css/          → style.css (design system), auth.css (login screen)
│   ├── js/            → main.js (shared UI behavior), map.js (Leaflet helper)
│   ├── images/
│   └── icons/
│
├── components/         → navbar.php, sidebar.php, footer.php, head_open.php
├── includes/            → config.php, db.php, auth_check.php, dummy_data.php
├── database/           → geovendor.sql (PostgreSQL + PostGIS schema)
│
├── admin/               → MISU Administrator pages
├── bplo_staff/          → BPLO Staff pages
├── head_officer/        → BPLO Head Officer pages
├── inspector/            → Business Permit Inspector pages
├── vendor/               → Vendor pages
│
├── login/index.php      → Shared login screen (all 5 roles)
├── logout/index.php
├── index.php             → Entry point / router
└── README.md
```

## Running locally with XAMPP

1. Copy the `GeoVendor` folder into your XAMPP `htdocs` directory, e.g.
   `C:\xampp\htdocs\GeoVendor` or `/Applications/XAMPP/htdocs/GeoVendor`.
2. Start **Apache** from the XAMPP Control Panel (a database is not required
   for this prototype — everything runs on sample data).
3. Open a browser and visit: `http://localhost/GeoVendor/`
4. You will be redirected to the login screen.

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

## Modules implemented per role

- **MISU Administrator** — Dashboard, User Accounts, Backup & Restore, Vendor Map
- **BPLO Staff** — Dashboard, Vendor Registration (with map pin-drop), Vendor
  Management, Permit Management, Vendor Map
- **BPLO Head Officer** — Dashboard, Permit Monitoring, Inspection Reports, Vendor Map
- **Business Permit Inspector** — Dashboard, Inspection Map, Inspection Management
- **Vendor** — Dashboard, Business Profile, Permit Status

## Connecting a real database

1. Run `database/geovendor.sql` against a PostgreSQL database with the
   PostGIS extension enabled.
2. Implement the connection in `includes/db.php` (a commented example using
   PDO is already provided there).
3. Replace the arrays in `includes/dummy_data.php` with real queries
   (e.g. `SELECT ..., ST_AsGeoJSON(location) FROM vendors`) inside each page.
4. Swap the plaintext password check in `login/index.php` for
   `password_verify()` against `users.password_hash`.

## Notes

- This prototype focuses on **UI/UX and information architecture** — form
  submissions show confirmation banners but do not persist data.
- All data (vendors, permits, inspections, notifications, accounts) is
  sample data representative of Manolo Fortich, Bukidnon.
# GEOVENDOR
