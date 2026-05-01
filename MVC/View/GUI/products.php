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
                <!-- Row 1 -->
                <div class="prod-card" data-category="slippers" data-product-id="1">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Slipper1.png" alt="Denim Slide Sandal">
                        <span class="prod-bdg b-new">New 🌸</span>
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Slippers</div>
                        <div class="prod-name">Denim Slide Sandal</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">850 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★★</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="bags" data-product-id="2">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/bag1.jpeg" alt="Classic Leather Tote">
                        <span class="prod-bdg b-sale">Sale 🏷</span>
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Bags</div>
                        <div class="prod-name">Classic Leather Tote</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">1,530 <span class="prod-egp">EGP</span></span>
                            <span class="prod-old">1,800</span>
                            <span class="prod-stars">★★★★★</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="clogs" data-product-id="3">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Clog1.png" alt="Suede Mule Clog">
                        <span class="prod-bdg b-sale">Sale 🏷</span>
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Clogs</div>
                        <div class="prod-name">Suede Mule Clog</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">1,100 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★☆</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="slippers" data-product-id="4">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Slipper2.png" alt="Denim Buckle Slide">
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Slippers</div>
                        <div class="prod-name">Denim Buckle Slide</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">920 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★★</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="prod-card" data-category="bags" data-product-id="5">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Bag2.png" alt="Pebble Leather Tote">
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Bags</div>
                        <div class="prod-name">Pebble Leather Tote</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">1,620 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★☆</span>
                        </div>
                        <div class="stock-out">● Out of Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="clogs" data-product-id="6">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Clog2.png" alt="Mule Clog Taupe">
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Clogs</div>
                        <div class="prod-name">Mule Clog Taupe</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">980 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★★</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="slippers" data-product-id="7">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Slipper3.png" alt="Canvas Clog">
                        <span class="prod-bdg b-new">New 🌸</span>
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Slippers</div>
                        <div class="prod-name">Canvas Clog</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">750 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★☆</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>

                <div class="prod-card" data-category="bags" data-product-id="8">
                    <div class="prod-img-wrap">
                        <img class="prod-photo" src="../assets/images/Bag3.png" alt="Woven Crossbody">
                        <div class="prod-ov">
                            <button class="btn-atc">Add to Cart</button>
                            <button class="btn-fav-sm">♡</button>
                        </div>
                    </div>
                    <div class="prod-info">
                        <div class="prod-cat">Bags</div>
                        <div class="prod-name">Woven Crossbody</div>
                        <div class="prod-pr-row">
                            <span class="prod-price">1,250 <span class="prod-egp">EGP</span></span>
                            <span class="prod-stars">★★★★★</span>
                        </div>
                        <div class="stock-in">● In Stock</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include footer if needed -->
    <script src="../javascript/all.js" defer></script>
</body>
</html>