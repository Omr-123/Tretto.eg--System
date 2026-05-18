<?php
require_once __DIR__ . '/admin_common.php';
$PID = (int)($_GET['PID'] ?? $_GET['pid'] ?? 0);
$product = $controller->getProductByID($PID);
if (!$product) { admin_header('Delete Product', 'products'); echo '<div class="card" style="padding:20px">Product not found. PID = '.h($PID).'</div>'; admin_footer(); exit; }
$variants = $controller->getProductVariants($PID);
admin_header('Delete Product / Variants #' . $PID, 'products');
?>
<div class="card" style="padding:22px"><h2><?= h($product['name'] ?? '') ?></h2><p class="muted" style="margin:10px 0 18px">Delete a specific color/size variant with its images, or delete the whole product with all variants and images.</p><button class="btn btn-danger" onclick="deleteWhole()">Delete Whole Product</button><a class="btn btn-outline" href="<?= h(app_url('MVC/View/GUI/component/admin_product_edit.php?PID=' . $PID)) ?>">Back to Edit</a></div>
<div class="card"><div class="card-head"><div class="card-title">Delete Variants</div></div><table><thead><tr><th>Variant</th><th>Images</th><th>Action</th></tr></thead><tbody><?php if($variants): foreach($variants as $v): $pvid=(int)($v['pvid'] ?? 0); $imgs=$controller->getVariantImages($pvid); ?><tr><td><strong><?= h($v['color'] ?? '') ?></strong><br>Size: <?= (int)($v['sizes'] ?? 0) ?> / Stock: <?= (int)($v['stock'] ?? 0) ?> / Extra: <?= number_format((float)($v['add_price'] ?? 0), 2) ?></td><td><div class="image-grid"><?php if($imgs): foreach($imgs as $im): ?><img class="thumb" src="<?= h(admin_img_url($im['images'] ?? '')) ?>" alt="Product image"><?php endforeach; else: ?><span class="muted">No images</span><?php endif; ?></div></td><td><button class="btn btn-danger" onclick="deleteVariant(<?= $pvid ?>)">Delete this color/size</button></td></tr><?php endforeach; else: ?><tr><td colspan="3" style="text-align:center;color:var(--muted)">No variants.</td></tr><?php endif; ?></tbody></table></div>
<script>
function deleteVariant(pvid){if(!confirm('Delete this variant? Its images will be deleted too.'))return;post('deleteProductVariant',{pvid}).then(r=>{toast(r.message || (r.success?'Variant deleted.':'Failed to delete variant.'));if(r.success)setTimeout(()=>location.reload(),700)})}
function deleteWhole(){if(!confirm('Delete the whole product with all variants/images?'))return;post('deleteProduct',{PID:<?= (int)$PID ?>}).then(r=>{toast(r.message || (r.success?'Product deleted.':'Failed to delete product.'));if(r.success)setTimeout(()=>{location.href=APP_BASE+'/MVC/View/GUI/component/admin_dashboard.php#products'},900)})}
</script>
<?php admin_footer(); ?>
