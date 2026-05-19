<?php
require_once __DIR__ . '/../../Controller/products_controller.php';
session_start();
$prod = new ProductsController();
$products = $prod->getAllProducts();
function productImageUrl($product)
{

    if (empty($product->variants)) {

        return '';
    }
    return $product->variants[0]->img_url[0] ?? '';
}
function heroDisplayPrice($product)
{
    $price = (float) $product->price;
    if (!empty($product->variants)) {

        $price += (float) ($product->variants[0]->add_price ?? 0);

    }
    return number_format($price, 0, '.', ',') . ' EGP';
}
$heroProducts = [];
foreach ($products as $product) {

    if (empty($product->variants) || productImageUrl($product) === '') {

        continue;
    }
    $heroProducts[] = $product;
    if (count($heroProducts) >= 3) {

        break;
    }
}
$featuredProducts = [];
foreach ($products as $product) {
    if (empty($product->variants)) {
        continue;
    }
    $featuredProducts[] = $product;
    if (count($featuredProducts) >= 4) {
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/product.css">
    <title>Tretto</title>
</head>

<body>

    <?php include 'component/navbar.php'; ?>
    <div class="page" id="page-home">
        <div class="hero">
            <div class="hero-l">
                <div class="hero-badge"><span class="hb-dot"></span><span class="hb-txt">Autumn Collection 2025 ✨</span>
                </div>
                <h1 class="hero-h1">Egyptian <em>Craft</em>,<br>Feminine Style 🌸</h1>
                <p class="hero-sub">Handcrafted clogs, slippers & bags — made with love, delivering to all of Egypt.</p>
                <div class="hero-cta">
                    <button class="btn-primary" onclick="window.location.href='products.php'">Shop Now 💕</button>
                </div>
                <div class="hero-trust">
                    <div class="trust-item"><span class="trust-num">500+</span><span class="trust-lbl">Products</span>
                    </div>
                    <div class="trust-item"><span class="trust-num">98%</span><span class="trust-lbl">Happy Girls</span>
                    </div>
                    <div class="trust-item"><span class="trust-num">3</span><span class="trust-lbl">Stores</span></div>
                </div>
            </div>

            <div class="hero-r">

                <div class="hero-pill"><span class="hero-pill-txt">✨ Free delivery over 500 EGP</span></div>

                <div class="hero-showcase">

                    <?php if (!empty($heroProducts)):
                        $heroMain = $heroProducts[0];
                        $heroMainImg = productImageUrl($heroMain);
                        $heroMainName = htmlspecialchars($heroMain->name, ENT_QUOTES, 'UTF-8');
                        ?>
                        <a class="hero-main-card" href="product_detail.php?id=<?= (int) $heroMain->pid ?>">
                            <img class="hero-main-img" src="<?= htmlspecialchars($heroMainImg, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= $heroMainName ?>">
                            <div class="hero-card-info">
                                <div class="hero-card-name"><?= $heroMainName ?></div>
                                <div class="hero-card-price"><?= heroDisplayPrice($heroMain) ?></div>
                            </div>
                        </a>
                        <?php if (count($heroProducts) > 1): ?>
                            <div class="hero-mini-row">
                                <?php for ($hi = 1; $hi < count($heroProducts); $hi++):
                                    $heroMini = $heroProducts[$hi];
                                    $heroMiniImg = productImageUrl($heroMini);
                                    $heroMiniName = htmlspecialchars($heroMini->name, ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <a class="hero-mini-card" href="product_detail.php?id=<?= (int) $heroMini->pid ?>">
                                        <img class="hero-mini-img" src="<?= htmlspecialchars($heroMiniImg, ENT_QUOTES, 'UTF-8') ?>"
                                            alt="<?= $heroMiniName ?>">
                                        <div class="hero-mini-info">
                                            <div class="hero-mini-name"><?= $heroMiniName ?></div>
                                            <div class="hero-mini-price"><?= heroDisplayPrice($heroMini) ?></div>
                                        </div>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="marquee">
            <div class="mq-track">
                <span class="mq-item"><span class="mq-dot">💕</span> New arrivals every week</span>
                <span class="mq-item"><span class="mq-dot">✨</span> Easy returns within 14 days</span>
                <span class="mq-item"><span class="mq-dot">🛍</span> Cash on delivery available</span>
                <span class="mq-item"><span class="mq-dot">💕</span> New arrivals every week</span>
                <span class="mq-item"><span class="mq-dot">✨</span> Easy returns within 14 days</span>
                <span class="mq-item"><span class="mq-dot">🛍</span> Cash on delivery available</span>
            </div>
        </div>
        <div class="home-sec">
            <div class="home-sec-hd">
                <div>
                    <div class="sec-tag">✨ Recommended For You</div>
                    <h2 class="sec-title">Trending & <em>Loved</em></h2>
                </div>
                <a class="view-all" href="products.php">View all</a>
            </div>
            <div class="prod-grid feat">
                <?php foreach ($featuredProducts as $product):
                    $variant = $product->variants[0];
                    $img = $variant->img_url[0] ?? '';
                    if ($img === '') {
                        continue;
                    }
                    $inStock = isset($variant->stock) && (int) $variant->stock > 0;
                    ?>
                    <a href="product_detail.php?id=<?= (int) $product->pid ?>">
                        <div class="prod-card">
                            <div class="prod-img-wrap">
                                <img class="prod-photo" src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') ?>">
                                <div class="prod-ov">
                                    <form method="GET" action="products.php" class="prod-action-form"
                                        onclick="event.stopPropagation();">
                                        <input type="hidden" name="PID" value="<?= (int) $product->pid ?>">
                                        <input type="hidden" name="pvid" value="<?= (int) $variant->pvid ?>">
                                        <input type="hidden" name="price"
                                            value="<?= htmlspecialchars((string) $product->price, ENT_QUOTES, 'UTF-8') ?>">
                                        <button class="btn-atc" type="submit">Add to Cart</button>
                                    </form>
                                    <form method="GET" action="products.php" class="prod-action-form"
                                        onclick="event.stopPropagation();">
                                        <input type="hidden" name="PID" value="<?= (int) $product->pid ?>">
                                        <input type="hidden" name="pvid" value="<?= (int) $variant->pvid ?>">
                                        <input type="hidden" name="add_to_fav" value="<?= (int) $product->pid ?>">
                                        <button class="btn-fav-sm" type="submit">♡</button>
                                    </form>
                                </div>
                            </div>
                            <div class="prod-info">
                                <div class="prod-cat"><?= htmlspecialchars($product->category, ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="prod-name"><?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="prod-pr-row">
                                    <span
                                        class="prod-price"><?= htmlspecialchars((string) $product->price, ENT_QUOTES, 'UTF-8') ?>
                                        <span class="prod-egp">EGP</span></span>
                                    <span class="prod-stars">★★★★☆</span>
                                </div>
                                <?php if ($inStock): ?>
                                    <div class="stock-in">● In Stock</div>
                                <?php else: ?>
                                    <div class="stock-out">● Out of Stock</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script src="../javascript/all.js" defer></script>
    <?php include 'component/footer.php'; ?>
</body>

</html>