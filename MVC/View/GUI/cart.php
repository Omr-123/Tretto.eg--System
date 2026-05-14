<?php
require_once __DIR__ . '/../../Controller/cart_Controller.php';
require_once __DIR__ . '/../../Model/cart.php';
require_once __DIR__ . '/../../Model/product.php';
require_once __DIR__ . '/../../../db.php';
$cartController = new Cart_Controller();
$user_id = 1; // This should be dynamically set based on the logged-in user
$cart_items = $cartController->get_cart_items($user_id);
$subtotal=$cartController->Subtotal($user_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/cart.css">
    <script src="../javascript/product.js" defer></script>
    <title>Shopping Cart - Tretto.eg</title>
</head>
<body>
    <?php include 'component/navbar.php'; ?>
    
   <div class="page" id="page-cart">
        <div class="page-header">
            <div class="sec-tag">🛍 Shopping</div>
            <h1 class="sec-title">Your <em>Cart</em></h1>
        </div>
        
        <div class="page-wrap">
            <div class="cart-layout">
                <!-- CART ITEMS -->
                <div>
                    <?php foreach ($cart_items as $item): ?>
                    <!-- CART ITEM 1 -->
                    <div class="cart-item-row">
                        <div class="ci-img">
                            <img src="<?= $item->image; ?>" alt="<?= $item->name; ?>">
                        </div>
                        <div class="ci-inf">
                            <div class="ci-name">1</div>
                            <div class="ci-var">Size: 38 · Denim Blue</div>
                            <div class="ci-qty">
                                <button class="qty-b" >-</button>
                                <span class="qty-n">3</span>
                                <button class="qty-b" >+</button>
                            </div>
                        </div>
                        <div class="ci-pr"><?= $item->price; ?> EGP</div>
                    </div>
                    <?php endforeach; ?>
                    
                </div>

                <!-- CART SUMMARY -->
                <div class="cart-summary">
                    <div class="cs-title">Order Summary</div>
                    
                    <div class="cs-row">
                        <span>Subtotal</span>
                        <span><?= $subtotal;?> EGP</span>
                    </div>
                    
                    <div class="cs-row">
                        <span>Shipping</span>
                        <span>70 EGP</span>
                    </div>
                    
                    <div class="cs-row">
                        <span>Tax (14%)</span>
                        <span>646 EGP</span>
                    </div>
                    
                    <div class="cs-row total">
                        <span>Total</span>
                        <span>5,046 EGP</span>
                    </div>
                    
                    <p class="cs-note">✨ Free shipping on orders over 2,000 EGP</p>
                    
                    <button class="btn-primary" style="width: 100%; margin-bottom: 10px;">Proceed to Checkout 💕</button>
                    <button class="btn-secondary" style="width: 100%;">Continue Shopping</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include animation JavaScript only -->
    <?php include 'component/footer.php'; ?>

    <script src="../javascript/all.js" defer></script>
</body>
</html>