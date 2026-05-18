<?php
session_start();
require_once __DIR__ . '/../../Controller/products_controller.php'; 
require_once __DIR__ . '/../../Model/product.php';
require_once __DIR__ . '/../../Model/cart.php';
require_once __DIR__ . '/../../Model/Collection.php';
require_once __DIR__ . '/../../../db.php';
$A=$_GET['id'] ?? 1;
$id=-1;
if($id==-1){
    $id=$A;
}
$user_id = 1;
$i=isset($_GET['i']) ? (int)$_GET['i'] : 0;
$Img_Active = isset($_GET['Img_Active']) ? (int)$_GET['Img_Active'] : 0;
$ct=-1;
$Size_Active = isset($_GET['Size_Active']) ? (int)$_GET['Size_Active'] : 0;
$Color_Active=isset($_GET['Color_Active']) ? (int)$_GET['Color_Active'] : 0;
$ct1=-1;
$ct2=-1;
$prod=new ProductsController();
$product=$prod->getProductbyID($id);
if (isset($_GET['add_to_cart'])) {
    $prod->addToCart($product->pid,$i+1,$product->price, $user_id);
}


?>
<!-- PAGE 6: PRODUCT DETAIL -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/main.css">
            <link rel="stylesheet" href="../css/navbar.css">
            <link rel="stylesheet" href="../css/product-detail.css">
    <title><?= $product->name?></title>
</head>
<body>
    <?php include 'component/navbar.php'; ?>


    <div class="page" id="page-product-detail">
        <div class="page-wrap">
            <div class="product-detail-grid">
                <div class="pd-images">
                    <div class="pd-main-img"><img class="pd-main-photo" id="pd-main-photo" src="<?= $product->variants[$i]->img_url[$Img_Active] ?>"alt="Product"></div>
                    <div class="pd-thumbs">
                            <?php foreach($product->variants[$i]->img_url as $variant): $ct=$ct+1;?>
                                <a href="product_detail.php?id=<?= $product->pid ?>&Img_Active=<?= $ct ?>$id=<?= $id ?>">
                                    <div class="pd-thumb <?= $ct==$Img_Active ? 'active' : '' ?>"><img id="pd-thumb-0" src="<?= $variant?>"alt="thumb"></div>
                                </a>
                            <?php endforeach; ?>
                            
                    </div>
                </div>
                <div class="pd-info">
                    <div class="pd-breadcrumb"><a onclick="goTo('products')"
                            style="cursor:none;color:var(--muted);text-decoration:none">Products</a> › <span
                            id="pd-cat-crumb">Slippers</span></div>
                    <h1 class="pd-title" id="pd-title"><?= $product->name ?></h1>
                    <div class="pd-stars" id="pd-stars">★★★★★ <span style="color:var(--muted);font-size:11px">(248
                            reviews)</span></div>
                    <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:4px">
                        <div class="pd-price" id="pd-price"><?= $product->price?></div>
                        <div class="pd-old-price" id="pd-old-price" style="display:none"></div>
                    </div>
                    <div class="pd-price-egp">Egyptian Pounds (EGP)</div>
                    <p class="pd-desc" id="pd-desc" style="margin-top:16px"><?= $product->descriptions?></p>
                    <div class="pd-attr-title">Select Size</div>
                    <div class="size-grid" id="size-grid">
                        <?php foreach($product->variants as $variant): $ct1=$ct1+1;
                            if($variant->color== $product->variants[$Color_Active]->color): ?>
                            <a href="product_detail.php?id=<?= $product->pid ?>&Img_Active=<?= $Img_Active ?>&Size_Active=<?= $ct1 ?> &Color_Active=<?= $Color_Active ?>">
                                <button class="size-btn     <?= $ct1==$Size_Active ? 'sel' : '' ?>" onclick="selSize(this)"><?= $variant->size ?></button>
                            </a>
                        <?php endif; endforeach; ?>
                        
                        
                    </div>
                    <div class="pd-attr-title">Colour</div>
                    <div class="color-grid" id="color-grid">
                        <?php foreach($product->variants as $variant): $ct2=$ct2+1;
                            if($variant->size== $product->variants[$Size_Active]->size): ?>
                            <a href="product_detail.php?id=<?= $product->pid ?>&Img_Active=<?= $Img_Active ?>&Size_Active=<?= $Size_Active ?> &Color_Active=<?= $ct2 ?>">
                                <div class="color-dot<?=$ct2==$Color_Active ? 'sel' : '' ?>" style="background:<?= $variant->color ?>" title="<?= $variant->color ?>"
                                    onclick="selColor(this)"></div>
                                </a>
                        <?php endif; endforeach; ?>
                    </div>
                    <?php if($product->variants[$Size_Active]->stock <= 0): ?>
                        <div class="pd-stock stock-out" id="pd-stock">● Out of Stock</div>
                    <?php else: ?>
                    <div class="pd-stock stock-in" id="pd-stock">● In Stock — Ships within 2 business days</div>
                    <form method="GET" action="product_detail.php" class="prod-action-form">
                        <input type="hidden" name="add_to_cart" value="<?= $id ?>">
                        <input type="hidden" name="img_url" value="<?= $product->variants[$i]->img_url[$Img_Active] ?>">
                        <div class="pd-actions" style="margin-top:20px">
                            <button class="btn-primary" id="pd-add-cart" onclick="addCartFromDetail()">Add to Cart
                                            🛍</button>
                            <button class="btn-fav-lg" id="pd-fav-btn" onclick="toggleFavDetail()">♡</button>
                        </div>
                    </form>
                    <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);display:flex;gap:20px">
                    <div style="font-size:12px;color:var(--muted)">🚚 Free delivery over 500 EGP</div>
                    <div style="font-size:12px;color:var(--muted)">🔄 14-day easy returns</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</div>
        <?php include 'component/footer.php'; ?>


</body>
</html>