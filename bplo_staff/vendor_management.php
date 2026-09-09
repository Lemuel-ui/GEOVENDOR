<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['bplo_staff']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Manage Vendors';
$activePage = 'vendors';
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Manage Vendors</h1>
        <p>Search, review, and update registered vendor records.</p>
    </div>
    <a href="vendor_registration.php" class="btn btn-accent">+ Register New Vendor</a>
</div>

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
            <?php foreach ($DUMMY_VENDORS as $v): ?>
                <tr data-name="<?php echo strtolower($v['name']); ?>" data-barangay="<?php echo strtolower($v['barangay']); ?>" data-status="<?php echo $v['permit_status']; ?>">
                    <td class="vendor-id mono"><?php echo htmlspecialchars($v['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($v['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($v['owner']); ?></td>
                    <td><?php echo htmlspecialchars($v['category']); ?></td>
                    <td><?php echo htmlspecialchars($v['barangay']); ?></td>
                    <td class="mono"><?php echo htmlspecialchars($v['permit_no']); ?></td>
                    <td><span class="pin-status <?php echo $v['permit_status']; ?>"><?php echo ucfirst($v['permit_status']); ?></span></td>
                    <td>
                        <button class="btn btn-outline btn-sm" type="button" title="View / Edit business profile">Edit</button>
                        <button class="btn btn-danger-outline btn-sm" type="button" onclick="return gvConfirmDelete('Deactivate this vendor record?');">Deactivate</button>
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
