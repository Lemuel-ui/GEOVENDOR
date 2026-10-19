<?php
/**
 * functions.php
 * -----------------------------------------------------------------------
 * Data-access helpers used across every GeoVendor page. All queries use
 * PDO prepared statements against the MySQL/MariaDB `geovendor` database
 * (replaces the old includes/dummy_data.php sample arrays).
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/db.php';

/* ===========================================================================
 * Permit status helpers
 * ======================================================================= */

// Derives the live display status of a permit from its expiry date, unless
// it has been explicitly revoked. "Expiring" = active and due within 30 days.
function permit_display_status(string $expiryDate, string $storedStatus): string {
    if ($storedStatus === 'revoked') {
        return 'revoked';
    }
    $today = new DateTime('today');
    $expiry = new DateTime($expiryDate);
    $daysLeft = (int)$today->diff($expiry)->format('%r%a');

    if ($daysLeft < 0) return 'expired';
    if ($daysLeft <= 30) return 'expiring';
    return 'active';
}

// Tallies active/expiring/expired counts the way the dashboards display them.
function get_permit_counts(array $vendors): array {
    $counts = ['active' => 0, 'expiring' => 0, 'expired' => 0, 'revoked' => 0];
    foreach ($vendors as $v) {
        $status = $v['permit_status'] ?? 'active';
        if (!isset($counts[$status])) $counts[$status] = 0;
        $counts[$status]++;
    }
    return $counts;
}

/* ===========================================================================
 * Users
 * ======================================================================= */

function get_all_users(PDO $pdo): array {
    $stmt = $pdo->query('SELECT user_id, full_name, email, role, is_active, created_at FROM users ORDER BY full_name');
    return $stmt->fetchAll();
}

function get_user_by_email(PDO $pdo, string $email): ?array {
    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function create_user(PDO $pdo, string $fullName, string $email, string $password, string $role): array {
    $existing = $pdo->prepare('SELECT user_id FROM users WHERE email = ? LIMIT 1');
    $existing->execute([$email]);
    if ($existing->fetch()) {
        return ['ok' => false, 'error' => 'An account with that email already exists.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$fullName, $email, $hash, $role]);
    return ['ok' => true, 'user_id' => (int)$pdo->lastInsertId()];
}

function set_user_active(PDO $pdo, int $userId, bool $active): void {
    $stmt = $pdo->prepare('UPDATE users SET is_active = ? WHERE user_id = ?');
    $stmt->execute([$active ? 1 : 0, $userId]);
}

/* ===========================================================================
 * Vendors (joined with their latest permit, mirroring the old dummy_data
 * shape: id, name, owner, category, barangay, lat, lng, permit_no,
 * permit_status, expiry, contact)
 * ======================================================================= */

const VENDOR_SELECT = "
    SELECT v.vendor_id, v.business_name, v.owner_name, v.category, v.barangay,
           v.address, v.contact_no, v.latitude, v.longitude, v.vendor_user_id, v.is_active,
           p.permit_id, p.permit_no, p.issued_date, p.expiry_date, p.status AS permit_stored_status
    FROM vendors v
    LEFT JOIN permits p ON p.permit_id = (
        SELECT p2.permit_id FROM permits p2 WHERE p2.vendor_id = v.vendor_id
        ORDER BY p2.issued_date DESC, p2.permit_id DESC LIMIT 1
    )
";

function map_vendor_row(array $row): array {
    $status = $row['permit_stored_status']
        ? permit_display_status($row['expiry_date'], $row['permit_stored_status'])
        : 'active';
    return [
        'vendor_id'     => (int)$row['vendor_id'],
        'id'            => 'VND-' . str_pad((string)$row['vendor_id'], 4, '0', STR_PAD_LEFT),
        'name'          => $row['business_name'],
        'owner'         => $row['owner_name'],
        'category'      => $row['category'],
        'barangay'      => $row['barangay'],
        'address'       => $row['address'],
        'contact'       => $row['contact_no'],
        'lat'           => (float)$row['latitude'],
        'lng'           => (float)$row['longitude'],
        'permit_id'     => $row['permit_id'] ? (int)$row['permit_id'] : null,
        'permit_no'     => $row['permit_no'] ?? '—',
        'permit_status' => $status,
        'expiry'        => $row['expiry_date'] ?? '—',
        'is_active'     => (bool)$row['is_active'],
    ];
}

function get_all_vendors(PDO $pdo, bool $activeOnly = true): array {
    $sql = VENDOR_SELECT . ($activeOnly ? ' WHERE v.is_active = 1' : '') . ' ORDER BY v.business_name';
    $rows = $pdo->query($sql)->fetchAll();
    return array_map('map_vendor_row', $rows);
}

function get_vendor(PDO $pdo, int $vendorId): ?array {
    $stmt = $pdo->prepare(VENDOR_SELECT . ' WHERE v.vendor_id = ?');
    $stmt->execute([$vendorId]);
    $row = $stmt->fetch();
    return $row ? map_vendor_row($row) : null;
}

// Looks up the vendor record tied to a logged-in Vendor user account.
function get_vendor_for_user(PDO $pdo, int $userId): ?array {
    $stmt = $pdo->prepare(VENDOR_SELECT . ' WHERE v.vendor_user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return $row ? map_vendor_row($row) : null;
}

function create_vendor(PDO $pdo, array $data): array {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO vendors (business_name, owner_name, category, barangay, address, contact_no, latitude, longitude, registered_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['business_name'], $data['owner_name'], $data['category'], $data['barangay'],
            $data['address'], $data['contact_no'], $data['lat'], $data['lng'], $data['registered_by'],
        ]);
        $vendorId = (int)$pdo->lastInsertId();

        $stmt = $pdo->prepare(
            'INSERT INTO permits (vendor_id, permit_no, issued_date, expiry_date, status) VALUES (?, ?, CURDATE(), ?, ?)'
        );
        $stmt->execute([$vendorId, $data['permit_no'], $data['expiry_date'], 'active']);

        $stmt = $pdo->prepare("INSERT INTO notifications (type, message) VALUES ('success', ?)");
        $stmt->execute(['Vendor "' . $data['business_name'] . '" was successfully registered.']);

        $pdo->commit();
        return ['ok' => true, 'vendor_id' => $vendorId];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $msg = str_contains($e->getMessage(), 'permit_no') ? 'That permit number is already in use.' : 'Could not save the vendor record.';
        return ['ok' => false, 'error' => $msg];
    }
}

function update_vendor(PDO $pdo, int $vendorId, array $data): void {
    $stmt = $pdo->prepare(
        'UPDATE vendors SET business_name = ?, owner_name = ?, category = ?, barangay = ?, address = ?, contact_no = ?, latitude = ?, longitude = ?
         WHERE vendor_id = ?'
    );
    $stmt->execute([
        $data['business_name'], $data['owner_name'], $data['category'], $data['barangay'],
        $data['address'], $data['contact_no'], $data['lat'], $data['lng'], $vendorId,
    ]);
}

function set_vendor_active(PDO $pdo, int $vendorId, bool $active): void {
    $stmt = $pdo->prepare('UPDATE vendors SET is_active = ? WHERE vendor_id = ?');
    $stmt->execute([$active ? 1 : 0, $vendorId]);
}

/* ===========================================================================
 * Permits
 * ======================================================================= */

function renew_permit(PDO $pdo, int $permitId, string $newExpiryDate): bool {
    $stmt = $pdo->prepare("UPDATE permits SET status = 'active', issued_date = CURDATE(), expiry_date = ? WHERE permit_id = ?");
    return $stmt->execute([$newExpiryDate, $permitId]);
}

function revoke_permit(PDO $pdo, int $permitId): bool {
    $stmt = $pdo->prepare("UPDATE permits SET status = 'revoked' WHERE permit_id = ?");
    return $stmt->execute([$permitId]);
}

/* ===========================================================================
 * Inspections
 * ======================================================================= */

const INSPECTION_SELECT = "
    SELECT i.inspection_id, i.vendor_id, i.inspector_id, i.scheduled_date, i.status, i.remarks, i.completed_at,
           v.business_name, v.barangay
    FROM inspections i
    JOIN vendors v ON v.vendor_id = i.vendor_id
";

function map_inspection_row(array $row): array {
    return [
        'inspection_id'  => (int)$row['inspection_id'],
        'id'             => 'INS-' . str_pad((string)$row['inspection_id'], 3, '0', STR_PAD_LEFT),
        'vendor_id'      => (int)$row['vendor_id'],
        'vendor'         => $row['business_name'],
        'barangay'       => $row['barangay'],
        'scheduled'      => $row['scheduled_date'],
        'status'         => $row['status'],
        'status_label'   => ucfirst($row['status'] === 'flagged' ? 'Flagged for Review' : $row['status']),
        'remarks'        => $row['remarks'],
        'completed_at'   => $row['completed_at'],
    ];
}

function get_all_inspections(PDO $pdo): array {
    $rows = $pdo->query(INSPECTION_SELECT . ' ORDER BY i.scheduled_date DESC')->fetchAll();
    return array_map('map_inspection_row', $rows);
}

function get_inspections_for_inspector(PDO $pdo, int $inspectorId): array {
    $stmt = $pdo->prepare(INSPECTION_SELECT . ' WHERE i.inspector_id = ? ORDER BY (i.status = "pending") DESC, i.scheduled_date ASC');
    $stmt->execute([$inspectorId]);
    return array_map('map_inspection_row', $stmt->fetchAll());
}

function update_inspection_status(PDO $pdo, int $inspectionId, string $status, string $remarks): bool {
    $stmt = $pdo->prepare('UPDATE inspections SET status = ?, remarks = ?, completed_at = NOW() WHERE inspection_id = ?');
    $ok = $stmt->execute([$status, $remarks, $inspectionId]);

    if ($ok && $status === 'flagged') {
        $vendorStmt = $pdo->prepare('SELECT business_name FROM vendors v JOIN inspections i ON i.vendor_id = v.vendor_id WHERE i.inspection_id = ?');
        $vendorStmt->execute([$inspectionId]);
        $vendorName = $vendorStmt->fetchColumn();
        $notif = $pdo->prepare("INSERT INTO notifications (type, message) VALUES ('danger', ?)");
        $notif->execute(["Inspection at $vendorName was flagged for review."]);
    }
    return $ok;
}

function add_inspection_photo(PDO $pdo, int $inspectionId, string $relativePath): void {
    $stmt = $pdo->prepare('INSERT INTO inspection_photos (inspection_id, file_path) VALUES (?, ?)');
    $stmt->execute([$inspectionId, $relativePath]);
}

function get_inspection_photos(PDO $pdo, int $inspectionId): array {
    $stmt = $pdo->prepare('SELECT file_path, uploaded_at FROM inspection_photos WHERE inspection_id = ? ORDER BY uploaded_at DESC');
    $stmt->execute([$inspectionId]);
    return $stmt->fetchAll();
}

/* ===========================================================================
 * Notifications
 * ======================================================================= */

function get_notifications(PDO $pdo, int $limit = 10): array {
    $stmt = $pdo->prepare('SELECT notification_id AS id, type, message, created_at FROM notifications ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['time'] = time_ago($r['created_at']);
    }
    return $rows;
}

function count_notifications(PDO $pdo): int {
    return (int)$pdo->query('SELECT COUNT(*) FROM notifications')->fetchColumn();
}

function time_ago(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hour' . (floor($diff / 3600) > 1 ? 's' : '') . ' ago';
    $days = floor($diff / 86400);
    return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
}

/* ===========================================================================
 * Backup & Restore (admin/backup_restore.php)
 * ======================================================================= */

function get_backup_logs(PDO $pdo): array {
    return $pdo->query('SELECT backup_id, file_path, backup_type, file_size_mb, status, created_at FROM backup_logs ORDER BY created_at DESC')->fetchAll();
}

// Lightweight logical backup: dumps every table's rows as INSERT statements
// into a timestamped .sql file under /backups (no external mysqldump needed).
function run_backup(PDO $pdo, string $type = 'manual'): array {
    $dir = __DIR__ . '/../backups/';
    if (!is_dir($dir)) mkdir($dir, 0775, true);

    $filename = 'geovendor_' . date('Ymd_His') . '.sql';
    $path = $dir . $filename;
    $tables = ['users', 'vendors', 'permits', 'inspections', 'inspection_photos', 'notifications'];

    $out = "-- GeoVendor backup — " . date('Y-m-d H:i:s') . "\nSET FOREIGN_KEY_CHECKS=0;\n\n";
    foreach ($tables as $table) {
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll();
        if (!$rows) continue;
        $columns = array_keys($rows[0]);
        $colList = '`' . implode('`, `', $columns) . '`';
        $out .= "TRUNCATE TABLE `$table`;\n";
        foreach ($rows as $row) {
            $vals = array_map(fn($v) => $v === null ? 'NULL' : $pdo->quote((string)$v), $row);
            $out .= "INSERT INTO `$table` ($colList) VALUES (" . implode(', ', $vals) . ");\n";
        }
        $out .= "\n";
    }
    $out .= "SET FOREIGN_KEY_CHECKS=1;\n";

    file_put_contents($path, $out);
    $sizeMb = round(filesize($path) / (1024 * 1024), 2);

    $stmt = $pdo->prepare('INSERT INTO backup_logs (file_path, backup_type, file_size_mb, status) VALUES (?, ?, ?, ?)');
    $stmt->execute(['backups/' . $filename, $type, $sizeMb, 'completed']);

    return ['ok' => true, 'file' => $filename];
}

function run_restore(PDO $pdo, string $relativePath): array {
    $fullPath = __DIR__ . '/../' . ltrim($relativePath, '/');
    if (!is_file($fullPath)) {
        return ['ok' => false, 'error' => 'Backup file not found.'];
    }
    $sql = file_get_contents($fullPath);
    try {
        $pdo->exec($sql);
        return ['ok' => true];
    } catch (PDOException $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}
