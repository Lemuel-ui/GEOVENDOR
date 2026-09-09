<?php
/**
 * dummy_data.php
 * -----------------------------------------------------------------------
 * Sample data used throughout the prototype so every screen has
 * something realistic to display without a live database. Replace with
 * real PostgreSQL/PostGIS queries once the backend is built.
 * -----------------------------------------------------------------------
 */

// Demo login accounts — one per role. Password for all demo accounts: "geovendor2026"
$DUMMY_USERS = [
    'admin@manolofortich.gov.ph'   => ['role' => 'admin',        'name' => 'Rhea Fernandez',   'password' => 'geovendor2026'],
    'staff@manolofortich.gov.ph'   => ['role' => 'bplo_staff',    'name' => 'Jomar Villareal',  'password' => 'geovendor2026'],
    'head@manolofortich.gov.ph'    => ['role' => 'head_officer',  'name' => 'Atty. Liza Cabahug','password' => 'geovendor2026'],
    'inspector@manolofortich.gov.ph' => ['role' => 'inspector',   'name' => 'Ronel Dagooc',      'password' => 'geovendor2026'],
    'vendor@manolofortich.gov.ph'  => ['role' => 'vendor',        'name' => 'Cristy Amora',      'password' => 'geovendor2026'],
];

// Vendors registered in the system, with coordinates around Manolo Fortich, Bukidnon
$DUMMY_VENDORS = [
    [
        'id' => 'VND-1001', 'name' => 'Amora Sari-Sari Store', 'owner' => 'Cristy Amora',
        'category' => 'Retail / Sari-Sari Store', 'barangay' => 'Poblacion',
        'lat' => 8.3706, 'lng' => 124.8681,
        'permit_no' => 'BP-2026-0341', 'permit_status' => 'active', 'expiry' => '2027-01-15',
        'contact' => '0917-234-5567',
    ],
    [
        'id' => 'VND-1002', 'name' => 'Villareal Eatery', 'owner' => 'Jomar Villareal Sr.',
        'category' => 'Food / Carinderia', 'barangay' => 'Alae',
        'lat' => 8.3892, 'lng' => 124.8452,
        'permit_no' => 'BP-2025-1187', 'permit_status' => 'expiring', 'expiry' => '2026-08-20',
        'contact' => '0918-556-2231',
    ],
    [
        'id' => 'VND-1003', 'name' => 'Dagooc Hardware Supply', 'owner' => 'Ronel Dagooc',
        'category' => 'Hardware / Construction Supplies', 'barangay' => 'Dahilayan',
        'lat' => 8.3255, 'lng' => 124.8590,
        'permit_no' => 'BP-2025-0892', 'permit_status' => 'expired', 'expiry' => '2026-06-30',
        'contact' => '0920-874-1120',
    ],
    [
        'id' => 'VND-1004', 'name' => 'Cabahug Fresh Produce', 'owner' => 'Liza Cabahug',
        'category' => 'Wet Market / Vegetables', 'barangay' => 'Sankanan',
        'lat' => 8.3781, 'lng' => 124.8734,
        'permit_no' => 'BP-2026-0455', 'permit_status' => 'active', 'expiry' => '2027-02-11',
        'contact' => '0906-441-9082',
    ],
    [
        'id' => 'VND-1005', 'name' => 'Fernandez Auto Parts', 'owner' => 'Rhea Fernandez',
        'category' => 'Automotive Parts', 'barangay' => 'Poblacion',
        'lat' => 8.3722, 'lng' => 124.8659,
        'permit_no' => 'BP-2025-0765', 'permit_status' => 'expiring', 'expiry' => '2026-08-30',
        'contact' => '0933-120-4478',
    ],
    [
        'id' => 'VND-1006', 'name' => 'Sto. Niño Bakery', 'owner' => 'Marites Oyao',
        'category' => 'Food / Bakery', 'barangay' => 'Damilag',
        'lat' => 8.3610, 'lng' => 124.8517,
        'permit_no' => 'BP-2026-0198', 'permit_status' => 'active', 'expiry' => '2027-03-05',
        'contact' => '0945-661-7734',
    ],
];

// Pending inspections assigned to Business Permit Inspectors
$DUMMY_INSPECTIONS = [
    ['id' => 'INS-501', 'vendor_id' => 'VND-1002', 'vendor' => 'Villareal Eatery', 'barangay' => 'Alae', 'scheduled' => '2026-08-04', 'status' => 'Pending'],
    ['id' => 'INS-502', 'vendor_id' => 'VND-1003', 'vendor' => 'Dagooc Hardware Supply', 'barangay' => 'Dahilayan', 'scheduled' => '2026-08-05', 'status' => 'Pending'],
    ['id' => 'INS-503', 'vendor_id' => 'VND-1005', 'vendor' => 'Fernandez Auto Parts', 'barangay' => 'Poblacion', 'scheduled' => '2026-08-06', 'status' => 'Pending'],
    ['id' => 'INS-499', 'vendor_id' => 'VND-1001', 'vendor' => 'Amora Sari-Sari Store', 'barangay' => 'Poblacion', 'scheduled' => '2026-07-22', 'status' => 'Completed'],
];

// Notification feed shown across roles
$DUMMY_NOTIFICATIONS = [
    ['id' => 1, 'type' => 'warning', 'message' => 'Permit BP-2025-1187 (Villareal Eatery) expires in 21 days.', 'time' => '2 hours ago'],
    ['id' => 2, 'type' => 'danger',  'message' => 'Permit BP-2025-0892 (Dagooc Hardware Supply) has expired.', 'time' => '1 day ago'],
    ['id' => 3, 'type' => 'warning', 'message' => 'Permit BP-2025-0765 (Fernandez Auto Parts) expires in 31 days.', 'time' => '1 day ago'],
    ['id' => 4, 'type' => 'success', 'message' => 'Vendor "Sto. Niño Bakery" was successfully registered.', 'time' => '3 days ago'],
    ['id' => 5, 'type' => 'info',    'message' => 'Inspection INS-499 marked as Completed by R. Dagooc.', 'time' => '5 days ago'],
];

// Helper: counts used on dashboard summary cards
function get_permit_counts($vendors) {
    $counts = ['active' => 0, 'expiring' => 0, 'expired' => 0];
    foreach ($vendors as $v) {
        $counts[$v['permit_status']]++;
    }
    return $counts;
}
