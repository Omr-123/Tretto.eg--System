<?php
require_once __DIR__ . '/admin_common.php';
$locations = $controller->viewStoreLocations();
admin_header('Store Locations', 'locations');
?>
<div class="form-box">
    <h2 style="margin-bottom:12px">Add Store Location</h2>
    <form id="locForm">
        <div class="grid">
            <div class="group"><label>City</label><input name="City" required></div>
            <div class="group"><label>Address</label><input name="Address" required></div>
        </div><button type="button" class="btn btn-rose" onclick="addLoc()">Add Location</button>
    </form>
</div>
<div class="card">
    <div class="card-head">
        <div class="card-title">Locations</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>City</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $l):
                $id = (int) ($l['BranchID'] ?? $l['storeID'] ?? 0); ?>
                <tr>
                    <td>#<?= $id ?></td>
                    <td><input id="city<?= $id ?>" value="<?= h($l['City'] ?? $l['city'] ?? '') ?>"></td>
                    <td><input id="addr<?= $id ?>" value="<?= h($l['Address'] ?? $l['address'] ?? '') ?>"></td>
                    <td><button class="btn btn-warning" onclick="editLoc(<?= $id ?>)">Save</button><button
                            class="btn btn-danger" onclick="delLoc(<?= $id ?>)">Delete</button></td>
                </tr><?php endforeach; ?>
            <?php if (!$locations): ?>
                <tr>
                    <td colspan="4" style="text-align:center;color:var(--muted)">No locations found.</td>
                </tr><?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    function addLoc() { post('addStoreLocation', Object.fromEntries(new FormData(document.getElementById('locForm')))).then(r => { toast(r.success ? 'Location added.' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
    function editLoc(id) { post('editStoreLocation', { BranchID: id, City: document.getElementById('city' + id).value, Address: document.getElementById('addr' + id).value }).then(r => { toast(r.success ? 'Location updated.' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
    function delLoc(id) { if (!confirm('Delete this location?')) return; post('deleteStoreLocation', { BranchID: id }).then(r => { toast(r.success ? 'Location deleted.' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
</script>
<?php admin_footer(); ?>