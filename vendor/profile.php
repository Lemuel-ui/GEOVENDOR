<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_role(['vendor']);
require_once __DIR__ . '/../includes/dummy_data.php';

$pageTitle = 'Business Profile';
$activePage = 'profile';
$myBusiness = $DUMMY_VENDORS[0];
include __DIR__ . '/../components/head_open.php';
?>
<div class="page-head">
    <div>
        <h1>Business Profile</h1>
        <p>Details on record with the BPLO. Contact the office to request changes.</p>
    </div>
</div>

<div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <div class="card">
        <div class="card-header"><h3>Registered Details</h3></div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">Business Name</label><div><?php echo htmlspecialchars($myBusiness['name']); ?></div></div>
            <div class="form-group"><label class="form-label">Owner</label><div><?php echo htmlspecialchars($myBusiness['owner']); ?></div></div>
            <div class="form-group"><label class="form-label">Category</label><div><?php echo htmlspecialchars($myBusiness['category']); ?></div></div>
            <div class="form-group"><label class="form-label">Barangay</label><div><?php echo htmlspecialchars($myBusiness['barangay']); ?></div></div>
            <div class="form-group"><label class="form-label">Contact Number</label><div><?php echo htmlspecialchars($myBusiness['contact']); ?></div></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Registered Location</h3></div>
        <div class="card-body">
            <div id="gis-map" style="height:320px;"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/map.js"></script>
<script>
    var vendors = [<?php echo json_encode($myBusiness); ?>];
    initGeoVendorMap('gis-map', vendors, { center: [<?php echo $myBusiness['lat']; ?>, <?php echo $myBusiness['lng']; ?>], zoom: 16 });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
