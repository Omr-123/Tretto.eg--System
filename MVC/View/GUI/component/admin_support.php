<?php
require_once __DIR__ . '/admin_common.php';
$support = $controller->viewSupport();
admin_header('Support', 'support');
?>
<div class="form-box"><h2 style="margin-bottom:12px">Add Support Ticket</h2><form id="supForm"><div class="grid"><div class="group"><label>User ID</label><input type="number" name="userID" required></div><div class="group"><label>Status</label><select name="status"><option>Open</option><option>In Progress</option><option>Resolved</option><option>Closed</option></select></div><div class="group full"><label>Message / Issue</label><textarea name="message" rows="3" required></textarea></div></div><button type="button" class="btn btn-rose" onclick="addSup()">Add Support</button></form></div>
<div class="card"><div class="card-head"><div class="card-title">Support Tickets</div></div><table><thead><tr><th>ID</th><th>User</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($support as $s): $id=(int)($s['supportID'] ?? $s['support_ID'] ?? 0); ?><tr><td>#<?= $id ?></td><td><?= h($s['userName'] ?? $s['userID'] ?? '') ?></td><td><textarea id="msg<?= $id ?>"><?= h($s['message'] ?? $s['issue'] ?? '') ?></textarea></td><td><select id="st<?= $id ?>"><option <?= ($s['status']??'')==='Open'?'selected':'' ?>>Open</option><option <?= ($s['status']??'')==='In Progress'?'selected':'' ?>>In Progress</option><option <?= ($s['status']??'')==='Resolved'?'selected':'' ?>>Resolved</option><option <?= ($s['status']??'')==='Closed'?'selected':'' ?>>Closed</option></select></td><td><button class="btn btn-warning" onclick="editSup(<?= $id ?>)">Save</button><button class="btn btn-danger" onclick="delSup(<?= $id ?>)">Delete</button></td></tr><?php endforeach; ?>
<?php if(!$support): ?><tr><td colspan="5" style="text-align:center;color:var(--muted)">No support tickets found.</td></tr><?php endif; ?>
</tbody></table></div>
<script>
function addSup(){post('addSupport',Object.fromEntries(new FormData(document.getElementById('supForm')))).then(r=>{toast(r.success?'Support added.':'Failed');if(r.success)setTimeout(()=>location.reload(),600)})}
function editSup(id){post('modifySupport',{supportID:id,message:document.getElementById('msg'+id).value,status:document.getElementById('st'+id).value}).then(r=>{toast(r.success?'Support updated.':'Failed');if(r.success)setTimeout(()=>location.reload(),600)})}
function delSup(id){if(!confirm('Delete this support ticket?'))return;post('deleteSupport',{supportID:id}).then(r=>{toast(r.success?'Support deleted.':'Failed');if(r.success)setTimeout(()=>location.reload(),600)})}
</script>
<?php admin_footer(); ?>
