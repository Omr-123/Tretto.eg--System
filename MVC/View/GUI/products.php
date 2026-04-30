<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../javascript/products.js" defer></script>
    <title>Document</title>
</head>
<body>
        <?php include 'navbar.php'; ?>
        <div class="page" id="page-products">
            <div class="products-container">
                <div class="products-header">
                    <h1>Our Collections</h1>
                    <p>Discover our handcrafted collections</p>
                </div>

                <div class="filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="bags">Bags</button>
                    <button class="filter-btn" data-filter="clogs">Clogs</button>
                    <button class="filter-btn" data-filter="slippers">Slippers</button>
                </div>

                <div class="products-grid" id="products-grid">
                    <!-- Products will be dynamically loaded here via PHP -->
                    <!-- Sample product card structure -->
                    <div class="product-card" data-product-id="1">
                        <div class="product-image">
                            <img src="/images/product-1.jpg" alt="Product">
                            <button class="btn-favorite">♡</button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <p class="product-category">Category</p>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="review-count">(0)</span>
                            </div>
                            <div class="product-price">
                                <span class="price">EGP 0.00</span>
                            </div>
                            <button class="btn-add-cart">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../javascript/all.js" defer></script>
    </body>
</html>