<?php
require_once __DIR__ . '/admin_common.php';
$PID = (int) ($_GET['PID'] ?? $_GET['pid'] ?? 0);
$product = $controller->getProductByID($PID);
if (!$product) {
    admin_header('Product Not Found', 'products');
    echo '<div class="card" style="padding:20px">Product not found. PID = ' . h($PID) . '</div>';
    admin_footer();
    exit;
}
$variants = $controller->getProductVariants($PID);
admin_header('View Product #' . $PID, 'products');
?>
<div class="card">
    <div class="card-head">
        <div class="card-title"><?= h($product['name'] ?? '') ?></div>
        <div><a class="btn btn-warning"
                href="<?= h(app_url('MVC/View/GUI/component/admin_product_edit.php?PID=' . $PID)) ?>">Edit</a><a
                class="btn btn-danger"
                href="<?= h(app_url('MVC/View/GUI/component/admin_product_delete.php?PID=' . $PID)) ?>">Delete</a></div>
    </div>
    <table>
        <tr>
            <th>Product ID</th>
            <td>#<?= (int) $PID ?></td>
        </tr>
        <tr>
            <th>Category</th>
            <td><?= h($product['category'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Base Price</th>
            <td><?= number_format((float) ($product['price'] ?? 0), 2) ?> EGP</td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= h($product['descriptions'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Type Details</th>
            <td><?php if (($product['category'] ?? '') === 'Bag'): ?>Capacity:
                    <?= (int) ($product['capacityLiters'] ?? 0) ?>L / Pockets:
                    <?= (int) ($product['numpackets'] ?? 0) ?><?php elseif (($product['category'] ?? '') === 'Clog'): ?>Heel:
                    <?= h($product['heelHeight'] ?? 0) ?> / Strap:
                    <?= h($product['strapType'] ?? '') ?><?php elseif (($product['category'] ?? '') === 'Slipper'): ?>Softness:
                    <?= h($product['materialsoftness'] ?? '') ?><?php else: ?>N/A<?php endif; ?></td>
        </tr>
    </table>
</div>
<div class="card">
    <div class="card-head">
        <div class="card-title">Variants, Sizes, Colors, Stock and Images</div>
    </div>
    <?php if ($variants):
        foreach ($variants as $v):
            $pvid = (int) ($v['pvid'] ?? 0);
            $imgs = $controller->getVariantImages($pvid); ?>
            <div style="padding:18px;border-bottom:1px solid var(--border)"><strong>Variant ID:</strong> #<?= $pvid ?> &nbsp;
                <strong>Color:</strong> <?= h($v['color'] ?? '') ?> &nbsp; <strong>Size:</strong> <?= (int) ($v['sizes'] ?? 0) ?>
                &nbsp; <strong>Stock:</strong> <?= (int) ($v['stock'] ?? 0) ?> &nbsp; <strong>Extra Price:</strong>
                <?= number_format((float) ($v['add_price'] ?? 0), 2) ?> EGP
                <div class="image-grid" style="margin-top:12px"><?php if ($imgs):
                    foreach ($imgs as $im): ?>
                            <div class="image-box"><img src="<?="../".$im['images']?> " class="thumb"
                                    alt="Product image">
                                <div class="muted">#<?= (int) ($im['piid'] ?? $im['id'] ?? 0) ?></div>
                            </div><?php endforeach; else: ?><span class="muted">No images for this variant.</span><?php endif; ?>
                </div>
            </div>
        <?php endforeach; else: ?>
        <div style="padding:18px;color:var(--muted)">No variants yet. Go to Edit page to add color, size, stock and images.
        </div><?php endif; ?>
</div>
<?php admin_footer(); ?>