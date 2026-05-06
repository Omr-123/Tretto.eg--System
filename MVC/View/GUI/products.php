<?php

require_once __DIR__ . '/../../Controller/products_controller.php';
require_once __DIR__ . '/../../Model/product.php';
require_once __DIR__ . '/../../../db.php';
$list=new ProductsController();
$products=$list->getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">

    <link rel="stylesheet" href="../css/product.css">
    
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
    <title>Products - Tretto.eg</title>
</head>
<body>
    
    <?php include 'component/navbar.php'; ?>
    <!-- PAGE 5: PRODUCT LISTING -->
    <div class="page" id="page-products">
        <div class="page-header">
            <div class="sec-tag">🛍 All Products</div>
            <h1 class="sec-title">Clogs, Slippers & <em>Bags</em></h1>
        </div>
        <div class="page-wrap">
            <div class="search-bar">
                <span class="s-ico">🔍</span>
                <input class="s-inp" id="search-input" type="text" placeholder="Search by name, style, colour…">
                <button class="s-btn">Search ✨</button>
            </div>
            <div class="filter-row">
                <span class="filter-lbl">Filter:</span>
                <button class="chip on" data-filter="all">All 💕</button>
                <button class="chip" data-filter="clogs">Clogs</button>
                <button class="chip" data-filter="slippers">Slippers</button>
                <button class="chip" data-filter="bags">Bags</button>
                <button class="chip" data-filter="new">New Arrivals</button>
                <button class="chip" data-filter="sale">On Sale 🏷</button>
                <select class="sort-sel">
                    <option value="newest">Sort: Newest</option>
                    <option value="price-asc">Price: Low → High</option>
                    <option value="price-desc">Price: High → Low</option>
                    <option value="popular">Best Selling</option>
                </select>
            </div>
            
            <!-- PRODUCT GRID - HARDCODED HTML -->
            <div class="prod-grid" id="prod-listing">
                 <?php foreach($products as $product):?>
                    <a href="product_detail.php?id=<?= $product->pid ?>">
                        <div class="prod-card" data-category="bags" data-product-id="5">
                            <div class="prod-img-wrap">
                                <img class="prod-photo" src="<?= $product->image; ?>" alt="Pebble Leather Tote">
                                <div class="prod-ov">
                                    <button class="btn-atc">Add to Cart</button>
                                    <button class="btn-fav-sm">♡</button>
                                </div>
                            </div>
                            <div class="prod-info">
                                <div class="prod-cat">Bags</div>
                                <div class="prod-name">Pebble Leather Tote</div>
                                <div class="prod-pr-row">
                                    <span class="prod-price"><?= $product->price; ?> <span class="prod-egp">EGP</span></span>
                                    <span class="prod-stars">★★★★☆</span>
                                </div>
                                <div class="stock-out">● Out of Stock</div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                

                
            </div>
        </div>
    </div>

    <!-- Include footer if needed -->
        <?php include 'component/footer.php'; ?>

    <script src="../javascript/all.js" defer></script>
</body>
</html>