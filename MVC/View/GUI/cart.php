<?php
session_start();
require_once __DIR__ . '/../../Controller/cart_Controller.php';
require_once __DIR__ . '/../../Model/cart.php';
require_once __DIR__ . '/../../Model/product.php';
require_once __DIR__ . '/../../../db.php';
$cartController = new Cart_Controller();
if(!isset($_SESSION['userID'])){
     header("Location:/Tretto.eg--System/MVC/View/GUI/login.php");
}
$user_id = $_SESSION['userID']; // This should be dynamically set based on the logged-in user
if (isset($_GET['update_cart']) && isset($_GET['action'])) {
    $action = $_GET['action'];
    $prod_ID = (int)$_GET['PID'];
    $quantity = (int)$_GET['quantity'];
    $pvid = isset($_GET['pvid']) ? (int)$_GET['pvid'] : null;

    $cartController->handleCartAction($user_id, $action, $prod_ID, $quantity, $pvid);
}

if(isset($_GET['update_cart']) && isset($_GET['-'])){
    $cartController->update_cart_item($user_id, (int)$_GET['PID'], (int)$_GET['quantity']-1);
    
}
if(isset($_GET['update_cart']) && isset($_GET['+'])){
    $cartController->update_cart_item($user_id, (int)$_GET['PID'], (int)$_GET['quantity']+1);
    var_dump($_GET['quantity']);
}
if(isset($_GET['update_cart']) && isset($_GET['remove']) && isset($_GET['pvid'])){
    $cartController->remove_from_cart($user_id, (int)$_GET['PID'],(int)$_GET['pvid']);
}
$cart_items = $cartController->get_cart_items($user_id);
$cart_info=$cartController->get_cart_info($user_id);
$subtotal=1;
$ct=-1;
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
                    <?php foreach ($cart_items as $item ): $ct=$ct+1 ;?>
                    <!-- CART ITEM 1 -->
                    <div class="cart-item-row">
                        <div class="ci-img">
                            <img src="<?= $item->variants[0]->img_url[0]; ?>" alt="<?= $item->name; ?>">
                        </div>
                        <div class="ci-inf">
                            <div class="ci-name"><?= $item->name; ?></div>
                            <div class="ci-var"><?= $item->variants[$cart_info[$ct]['pvid']-1]->size ?? 'N/A'; ?></div>
                            <form method="GET" action="cart.php">
                                <input type="hidden" name="update_cart" value="1">
                                <input type="hidden" name="PID" value="<?= $item->pid; ?>">
                                <input type="hidden" name="quantity" value="<?= $cart_info[$ct]['quantity']; ?>">
                                <input type="hidden" name="pvid" value="<?= $cart_info[$ct]['pvid']; ?>">
                                <div class="ci-qty">
                                    <button class="qty-b" type="submit" name="action" value="decrease">-</button>
                                    <span class="qty-n"><?= $cart_info[$ct]['quantity']; ?></span>
                                    <button class="qty-b" type="submit" name="action" value="increase">+</button>
                                    <button class="btn-ghost" type="submit" name="action" value="remove" style="margin-left:8px;font-size:11px;color:var(--rose)">Remove</button>                                </div>
                            </form>
                        </div>

                        <div class="ci-pr"><?= $cartController->getPrice($item->price, ($item->variants[$cart_info[$ct]['pvid']-1]->add_price ?? 0),$cart_info[$ct]['quantity']); ?> EGP</div>
                    </div>
                    <?php endforeach; ?>
                    
                </div>

                <!-- CART SUMMARY -->
                <div class="cart-summary">
                    <div class="cs-title">Order Summary</div>
                    
                    <div class="cs-row">
                        <span>Subtotal</span>
                        <span><?= $cartController->Subtotal($user_id);?> EGP</span>
                    </div>
                    
                    <div class="cs-row">
                        <span>Shipping</span>

                        <span>70 EGP</span>
                    </div>
                    
                    <div class="cs-row">
                        <span>Tax (14%)</span>
                        <span><?= $cartController->Subtotal($user_id) * 0.4;?> EGP</span>
                    </div>
                    
                    <div class="cs-row total">
                        <span>Total</span>
                        <span><?= $cartController->Subtotal($user_id)*1.4?>EGP</span>
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