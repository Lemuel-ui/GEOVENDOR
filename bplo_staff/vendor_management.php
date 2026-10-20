<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['bplo_staff']);
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$pageTitle = 'Manage Vendors';
$activePage = 'vendors';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_vendor_id'])) {
    $vendorId = (int)$_POST['toggle_vendor_id'];
    $active = $_POST['toggle_to'] === '1';
    set_vendor_active($pdo, $vendorId, $active);
    $message = $active ? 'Vendor record reactivated.' : 'Vendor record deactivated.';
}

$vendors = get_all_vendors($pdo, false);
=======
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Manage Vendors';
$activePage = 'vendors';
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Manage Vendors</h1>
        <p>Search, review, and update registered vendor records.</p>
    </div>
    <a href="vendor_registration.php" class="btn btn-accent">+ Register New Vendor</a>
</div>

<<<<<<< HEAD
<?php if ($message): ?>
    <div class="card" style="border-left:4px solid var(--color-info); margin-bottom:1.25rem;">
        <div class="card-body"><?php echo htmlspecialchars($message); ?></div>
    </div>
<?php endif; ?>

=======
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:.75rem;">
        <div class="toolbar" style="margin:0;">
            <input type="text" class="form-control" id="vendorSearch" placeholder="Search by vendor name or barangay...">
            <select class="form-control" id="statusFilter" style="max-width:180px;">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expiring">Expiring</option>
                <option value="expired">Expired</option>
            </select>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="gv-table" id="vendorTable">
            <thead>
                <tr>
                    <th>Vendor ID</th><th>Business Name</th><th>Owner</th><th>Category</th>
                    <th>Barangay</th><th>Permit No.</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
<<<<<<< HEAD
            <?php foreach ($vendors as $v): ?>
                <tr data-name="<?php echo strtolower($v['name']); ?>" data-barangay="<?php echo strtolower($v['barangay']); ?>" data-status="<?php echo $v['permit_status']; ?>">
                    <td class="vendor-id mono"><?php echo htmlspecialchars($v['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($v['name']); ?></strong> <?php if (!$v['is_active']): ?><span class="badge-soft">inactive</span><?php endif; ?></td>
=======
            <?php foreach ($DUMMY_VENDORS as $v): ?>
                <tr data-name="<?php echo strtolower($v['name']); ?>" data-barangay="<?php echo strtolower($v['barangay']); ?>" data-status="<?php echo $v['permit_status']; ?>">
                    <td class="vendor-id mono"><?php echo htmlspecialchars($v['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($v['name']); ?></strong></td>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                    <td><?php echo htmlspecialchars($v['owner']); ?></td>
                    <td><?php echo htmlspecialchars($v['category']); ?></td>
                    <td><?php echo htmlspecialchars($v['barangay']); ?></td>
                    <td class="mono"><?php echo htmlspecialchars($v['permit_no']); ?></td>
                    <td><span class="pin-status <?php echo $v['permit_status']; ?>"><?php echo ucfirst($v['permit_status']); ?></span></td>
                    <td>
<<<<<<< HEAD
                        <a class="btn btn-outline btn-sm" href="vendor_edit.php?id=<?php echo $v['vendor_id']; ?>" title="View / Edit business profile">Edit</a>
                        <form method="POST" style="display:inline;" onsubmit="return <?php echo $v['is_active'] ? "gvConfirmDelete('Deactivate this vendor record?')" : 'true'; ?>;">
                            <input type="hidden" name="toggle_vendor_id" value="<?php echo $v['vendor_id']; ?>">
                            <input type="hidden" name="toggle_to" value="<?php echo $v['is_active'] ? '0' : '1'; ?>">
                            <button type="submit" class="btn <?php echo $v['is_active'] ? 'btn-danger-outline' : 'btn-outline'; ?> btn-sm"><?php echo $v['is_active'] ? 'Deactivate' : 'Reactivate'; ?></button>
                        </form>
=======
                        <button class="btn btn-outline btn-sm" type="button" title="View / Edit business profile">Edit</button>
                        <button class="btn btn-danger-outline btn-sm" type="button" onclick="return gvConfirmDelete('Deactivate this vendor record?');">Deactivate</button>
>>>>>>> 657f04eb7ee59654ac1e196f78d0751cdb126d22
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    var searchInput = document.getElementById('vendorSearch');
    var statusSelect = document.getElementById('statusFilter');
    var rows = document.querySelectorAll('#vendorTable tbody tr');

    function applyFilters() {
        var q = searchInput.value.toLowerCase().trim();
        var status = statusSelect.value;
        rows.forEach(function (row) {
            var matchesText = !q || row.dataset.name.includes(q) || row.dataset.barangay.includes(q);
            var matchesStatus = !status || row.dataset.status === status;
            row.style.display = (matchesText && matchesStatus) ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', applyFilters);
    statusSelect.addEventListener('change', applyFilters);
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
