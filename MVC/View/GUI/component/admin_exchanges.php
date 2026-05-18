<?php
require_once __DIR__ . '/admin_common.php';
$exchanges = $controller->viewExchanges();
admin_header('Exchanges', 'exchanges');
?>
<div class="card"><div class="card-head"><div class="card-title">Exchange Requests</div></div><table><thead><tr><th>ID</th><th>User</th><th>Old Product</th><th>New Product</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($exchanges as $e): $id=(int)($e['exchangeID'] ?? 0); $status=$e['status'] ?? 'Pending'; ?><tr><td>#<?= $id ?></td><td><?= h($e['userName'] ?? $e['userID'] ?? '') ?></td><td><?= h($e['oldProductID'] ?? $e['OldProductID'] ?? '') ?></td><td><?= h($e['newProductID'] ?? $e['NewProductID'] ?? '') ?></td><td><?= h($e['reason'] ?? '') ?></td><td><span class="badge badge-<?= h(strtolower($status)) ?>"><?= h($status) ?></span></td><td><button class="btn btn-rose" onclick="applyEx(<?= $id ?>)">Approve</button><button class="btn btn-danger" onclick="denyEx(<?= $id ?>)">Deny</button></td></tr><?php endforeach; ?>
<?php if(!$exchanges): ?><tr><td colspan="7" style="text-align:center;color:var(--muted)">No exchanges found.</td></tr><?php endif; ?>
</tbody></table></div>
<script>function applyEx(id){post('applyExchange',{exchangeID:id}).then(r=>{toast(r.success?'Exchange approved.':'Failed');if(r.success)setTimeout(()=>location.reload(),600)})}function denyEx(id){post('denyExchange',{exchangeID:id}).then(r=>{toast(r.success?'Exchange denied.':'Failed');if(r.success)setTimeout(()=>location.reload(),600)})}</script>
<?php admin_footer(); ?>
