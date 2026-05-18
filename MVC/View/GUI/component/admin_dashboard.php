<?php
require_once __DIR__ . '/admin_common.php';
$orders = $controller->viewOrders();
$products = $controller->viewProducts();
$refunds = $controller->viewRefunds();
$topSelling = $controller->getTopSelling();
$lowStock = $controller->getLowStock();
$monthlyRevenue = $controller->getMonthlyRevenue();
function product_details(array $p): string
{
    if (($p['category'] ?? '') === 'Bag')
        return 'Capacity: ' . (int) ($p['capacityLiters'] ?? 0) . 'L / Pockets: ' . (int) ($p['numpackets'] ?? 0);
    if (($p['category'] ?? '') === 'Clog')
        return 'Heel: ' . h($p['heelHeight'] ?? 0) . ' / Strap: ' . h($p['strapType'] ?? '');
    if (($p['category'] ?? '') === 'Slipper')
        return 'Softness: ' . h($p['materialsoftness'] ?? '');
    return '-';
}
admin_header('Dashboard', 'dashboard');
?>
<section class="section active" id="sec-dashboard">
    <div class="stats">
        <div class="stat">
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value"><?= number_format($monthlyRevenue, 2) ?> <span class="muted">EGP</span></div>
        </div>
        <div class="stat">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"><?= count($orders) ?></div>
        </div>
        <div class="stat">
            <div class="stat-label">Low Stock</div>
            <div class="stat-value" style="color:var(--danger)"><?= count($lowStock) ?></div>
        </div>
        <div class="stat">
            <div class="stat-label">Products</div>
            <div class="stat-value"><?= count($products) ?></div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <div class="card-title">Recent Orders</div><button class="btn btn-outline" onclick="goTo('orders')">View
                All</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($orders, 0, 5) as $o):
                    $status = $o['status'] ?? 'Pending'; ?>
                    <tr>
                        <td>#<?= (int) ($o['orderID'] ?? 0) ?></td>
                        <td><?= h($o['userName'] ?? 'N/A') ?></td>
                        <td><?= number_format((float) ($o['totalAmount'] ?? 0), 2) ?> EGP</td>
                        <td><span class="badge badge-<?= h(strtolower($status)) ?>"><?= h($status) ?></span></td>
                        <td><?= h($o['orderDate'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$orders): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--muted)">No orders yet.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section" id="sec-products">
    <div class="card">
        <div class="card-head">
            <div class="card-title">All Products</div><button class="btn btn-rose" onclick="goTo('add-product')">➕ Add
                Product</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p):
                    $stock = (int) ($p['stock'] ?? 0);
                    $pid = (int) ($p['PID'] ?? 0); ?>
                    <tr>
                        <td><img class="img-thumb" src="../<?= h(admin_img_url($p['image'] ?? '')) ?>" alt="product"></td>
                        <td>#<?= $pid ?></td>
                        <td><strong><?= h($p['name'] ?? '') ?></strong>
                            <div class="muted">
                                <?= h(strlen($p['descriptions'] ?? '') > 45 ? substr($p['descriptions'], 0, 45) . '...' : ($p['descriptions'] ?? '')) ?>
                            </div>
                        </td>
                        <td><?= h($p['category'] ?? '') ?></td>
                        <td><?= number_format((float) ($p['price'] ?? 0), 2) ?> EGP</td>
                        <td><span
                                class="badge <?= $stock === 0 ? 'badge-out' : ($stock < 10 ? 'badge-low' : 'badge-active') ?>"><?= $stock ?>
                                units</span></td>
                        <td><?= product_details($p) ?></td>
                        <td>
                            <a class="btn btn-outline"
                                href="<?= h(app_url('MVC/View/GUI/component/admin_product_view.php?PID=' . $pid)) ?>">View</a>
                            <a class="btn btn-warning"
                                href="<?= h(app_url('MVC/View/GUI/component/admin_product_edit.php?PID=' . $pid)) ?>">Edit</a>
                            <a class="btn btn-danger"
                                href="<?= h(app_url('MVC/View/GUI/component/admin_product_delete.php?PID=' . $pid)) ?>">Delete</a>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$products): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--muted)">No products found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section" id="sec-add-product">
    <div class="form-box">
        <h2 style="margin-bottom:6px">Add New Product</h2>
        <div class="muted" style="margin-bottom:18px">Required: product name, category, price and stock. Product will be
            inserted in product and product_variants automatically.</div>
        <div class="alert err" id="form-error"></div>
        <div class="alert ok" id="form-ok"></div>
        <form id="addProductForm" enctype="multipart/form-data">
            <div class="grid">
                <div class="group"><label>Product Name *</label><input id="ap-name" name="name"
                        placeholder="e.g. Rose Pink Clogs"></div>
                <div class="group"><label>Category *</label><select id="ap-cat" name="category"
                        onchange="showCategoryFields()">
                        <option value="">Select category</option>
                        <option value="Bag">Bag</option>
                        <option value="Clog">Clog</option>
                        <option value="Slipper">Slipper</option>
                    </select></div>
                <div class="group"><label>Price *</label><input id="ap-price" name="price" type="number" step="0.01"
                        min="0" placeholder="0.00"></div>
                <div class="group"><label>Stock *</label><input id="ap-stock" name="stock" type="number" min="0"
                        placeholder="0"></div>
                <div class="group"><label>Color</label><input name="color" placeholder="Pink / Black / Default"></div>
                <div class="group"><label>Size</label><input name="size" type="number" placeholder="38"></div>
                <div class="group full"><label>Description</label><textarea name="description" rows="3"
                        placeholder="Product description..."></textarea></div>
                <div class="group full"><label>Product Image</label><input type="file" name="image_file"
                        accept="image/*">
                    <div class="muted">Saved in MVC/View/assets/images and name stored in product_images.</div>
                </div>
            </div>
            <div id="fields-Bag" class="type">
                <div class="grid">
                    <div class="group"><label>Capacity Liters</label><input name="capacityLiters" type="number"
                            placeholder="5"></div>
                    <div class="group"><label>Number of Pockets</label><input name="numpackets" type="number"
                            placeholder="3"></div>
                </div>
            </div>
            <div id="fields-Clog" class="type">
                <div class="grid">
                    <div class="group"><label>Heel Height</label><input name="heelHeight" type="number" step="0.1"
                            placeholder="5.5"></div>
                    <div class="group"><label>Strap Type</label><input name="strapType" placeholder="Ankle Strap"></div>
                </div>
            </div>
            <div id="fields-Slipper" class="type">
                <div class="grid">
                    <div class="group"><label>Material Softness</label><input name="materialsoftness"
                            placeholder="Ultra Soft"></div>
                </div>
            </div>
            <button type="button" class="btn btn-rose" onclick="submitAddProduct()">Add Product</button>
        </form>
    </div>
</section>

<section class="section" id="sec-orders">
    <div class="card">
        <div class="card-head">
            <div class="card-title">All Orders</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o):
                    $status = $o['status'] ?? 'Pending'; ?>
                    <tr>
                        <td>#<?= (int) ($o['orderID'] ?? 0) ?></td>
                        <td><?= h($o['userName'] ?? 'N/A') ?></td>
                        <td><?= number_format((float) ($o['totalAmount'] ?? 0), 2) ?> EGP</td>
                        <td><select
                                onchange="updateOrderStatus(<?= (int) ($o['orderID'] ?? 0) ?>, this.value)"><?php foreach (['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'] as $s): ?>
                                    <option value="<?= h($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= h($s) ?></option>
                                <?php endforeach; ?>
                            </select></td>
                        <td><?= h($o['orderDate'] ?? '') ?></td>
                        <td><button class="btn btn-danger"
                                onclick="deleteOrder(<?= (int) ($o['orderID'] ?? 0) ?>)">Delete</button></td>
                    </tr><?php endforeach; ?>
                <?php if (!$orders): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--muted)">No orders found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section" id="sec-refunds">
    <div class="card">
        <div class="card-head">
            <div class="card-title">Refund Requests</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Refund</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($refunds as $r):
                    $status = $r['status'] ?? 'Pending'; ?>
                    <tr>
                        <td>#<?= (int) ($r['refundID'] ?? 0) ?></td>
                        <td>#<?= (int) ($r['orderID'] ?? 0) ?></td>
                        <td><?= h($r['userName'] ?? 'N/A') ?></td>
                        <td><?= number_format((float) ($r['refundAmount'] ?? 0), 2) ?> EGP</td>
                        <td><span class="badge badge-<?= h(strtolower($status)) ?>"><?= h($status) ?></span></td>
                        <td><?= h($r['reason'] ?? '') ?></td>
                        <td><button class="btn btn-rose"
                                onclick="applyRefund(<?= (int) ($r['refundID'] ?? 0) ?>)">Approve</button> <button
                                class="btn btn-danger" onclick="denyRefund(<?= (int) ($r['refundID'] ?? 0) ?>)">Deny</button>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$refunds): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--muted)">No refunds found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section" id="sec-reports">
    <div class="stats">
        <div class="stat">
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value"><?= number_format($monthlyRevenue, 2) ?> EGP</div>
        </div>
        <div class="stat">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-value" style="color:var(--danger)"><?= count($lowStock) ?></div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <div class="card-title">Top Selling Products</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sells</th>
                </tr>
            </thead>
            <tbody><?php foreach ($topSelling as $p): ?>
                    <tr>
                        <td><?= h($p['name'] ?? '') ?></td>
                        <td><?= h($p['category'] ?? '') ?></td>
                        <td><?= number_format((float) ($p['price'] ?? 0), 2) ?> EGP</td>
                        <td><?= (int) ($p['Number_Of_Sells'] ?? 0) ?></td>
                    </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    function goTo(sec) { document.querySelectorAll('.section').forEach(s => s.classList.remove('active')); const el = document.getElementById('sec-' + sec); if (el) el.classList.add('active'); }
    function openFromHash() { goTo((location.hash || '#dashboard').replace('#', '')) } window.addEventListener('load', openFromHash); window.addEventListener('hashchange', openFromHash);
    function showMsg(id, msg) { const el = document.getElementById(id); el.textContent = msg; el.classList.add('show'); setTimeout(() => el.classList.remove('show'), 3500) }
    function clearMsgs() { ['form-error', 'form-ok'].forEach(id => { const e = document.getElementById(id); e.textContent = ''; e.classList.remove('show') }) }
    function showCategoryFields() {
        const c = document.getElementById('ap-cat').value; document.querySelectorAll('.type').forEach(x => x.style.display = 'none');
        const el = document.getElementById('fields-' + c); if (el) el.style.display = 'block'
    }

    function submitAddProduct() {
        clearMsgs(); const name = document.getElementById('ap-name').value.trim(), cat = document.getElementById('ap-cat').value, price = parseFloat(document.getElementById('ap-price').value), stock = document.getElementById('ap-stock').value;
        if (!name || !cat || !price || stock === '' || parseInt(stock) < 0) {
            showMsg('form-error', 'Please fill in the required product information: name, category, price and stock.');
            return
        } fetch(APP_BASE + '/MVC/Controller/AdminController.php?action=addProduct', { method: 'POST', body: new FormData(document.getElementById('addProductForm')) }).then(r => r.json()).then(res => { if (res.success) { showMsg('form-ok', res.message || 'Product added successfully.'); toast('Product added.'); setTimeout(() => location.href = APP_BASE + '/MVC/View/GUI/component/admin_dashboard.php#products', 700); setTimeout(() => location.reload(), 950) } else showMsg('form-error', res.message || 'Failed to add product.') }).catch(() => showMsg('form-error', 'Connection error. Please try again.'))
    }
    function updateOrderStatus(id, status) { post('modifyOrder', { orderID: id, status }).then(r => toast(r.success ? 'Order updated' : 'Failed to update order')) }
    function deleteOrder(id) { if (!confirm('Delete this order?')) return; post('deleteOrder', { orderID: id }).then(r => { toast(r.success ? 'Order deleted' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
    function applyRefund(id) { post('applyRefund', { refundID: id }).then(r => { toast(r.success ? 'Refund approved' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
    function denyRefund(id) { post('denyRefund', { refundID: id }).then(r => { toast(r.success ? 'Refund denied' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }
</script>
<?php admin_footer(); ?>