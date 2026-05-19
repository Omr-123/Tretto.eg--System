<?php
require_once __DIR__ . '/../../Controller/PaymentController.php';
require_once __DIR__ . '/../../Controller/cart_Controller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['userID'])) {
    header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
    exit;
}

$userId = (int) $_SESSION['userID'];
$controller = new PaymentController();
$formAction = '/Tretto.eg--System/MVC/View/GUI/payment.php';

$data = $controller->loadPageData($userId);
$items = $data['items'];
$totals = $data['totals'];
$paymentMethod = $data['paymentMethod'];
$error = $data['error'];
$isEmpty = $data['isEmpty'];
$checkout = $data['checkout'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $result = $controller->processPlaceOrder($userId, $_POST);

    if ($result['success']) {
        unset($_SESSION['checkoutID']);
        header('Location: /Tretto.eg--System/MVC/View/GUI/placeorder.php?id=' . (int) $result['orderID']);
        exit;
    }

    $error = $result['error'] ?? 'Could not place order.';
    $paymentMethod = $result['paymentMethod'] ?? 'visa';
    $data = $controller->loadPageData($userId);
    $items = $data['items'];
    $totals = $data['totals'];
    $isEmpty = $data['isEmpty'];
    $checkout = $data['checkout'] ?? null;
}

$formattedTotal = number_format($totals['total'], 2);
$formattedSubtotal = number_format($totals['subtotal'], 2);
$formattedShipping = number_format($totals['shipping'], 2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment – Tretto</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/payment.css">
    <script src="../javascript/navbar.js" defer></script>
</head>

<body>
    <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-payment">
        <div class="page-header">
            <div class="sec-tag">Step 3 of 3</div>
            <h1 class="sec-title">Complete <em>Payment</em> 💳</h1>
        </div>

        <div class="page-wrap">
            <?php if ($isEmpty): ?>
                <div class="payment-empty">
                    <p>Your cart is empty.</p>
                    <a href="products.php" class="btn-primary">Browse Products</a>
                </div>
            <?php else: ?>
                <div class="payment-layout">
                    <div>
                        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" id="payment-form"
                            class="payment-form-box">
                            <input type="hidden" name="place_order" value="1">
                            <input type="hidden" name="payment_method" id="payment_method"
                                value="<?= htmlspecialchars($paymentMethod) ?>">

                            <?php if ($error !== ''): ?>
                                <div class="payment-alert" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                    <?php if (!$checkout): ?>
                                        <a href="/Tretto.eg--System/MVC/Controller/checkout_Controller.php"
                                            class="payment-alert-link">Go to Checkout</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="co-section-title">Payment Method 💳</div>
                            <div class="pay-method-grid">
                                <button type="button" class="pay-method<?= $paymentMethod === 'visa' ? ' sel' : '' ?>"
                                    id="pm-visa" data-method="visa">
                                    <div class="pay-method-ico">💳</div>
                                    <div class="pay-method-name">Visa Card</div>
                                    <div class="pay-method-desc">Secure online payment</div>
                                    <div class="pay-method-check"><?= $paymentMethod === 'visa' ? '✓' : '' ?></div>
                                </button>
                                <button type="button" class="pay-method<?= $paymentMethod === 'cod' ? ' sel' : '' ?>"
                                    id="pm-cod" data-method="cod">
                                    <div class="pay-method-ico">💵</div>
                                    <div class="pay-method-name">Cash on Delivery</div>
                                    <div class="pay-method-desc">Pay when you receive</div>
                                    <div class="pay-method-check"><?= $paymentMethod === 'cod' ? '✓' : '' ?></div>
                                </button>
                            </div>

                            <div class="visa-form<?= $paymentMethod === 'visa' ? '' : ' hidden' ?>" id="visa-form">
                                <div class="form-group">
                                    <label class="form-label" for="card_name">Cardholder Name</label>
                                    <input class="form-input" id="card_name" name="card_name" placeholder="Sara Ahmed"
                                        value="<?= htmlspecialchars($_POST['card_name'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="card_number">Card Number</label>
                                    <input class="form-input" id="card_number" name="card_number"
                                        placeholder="1234 5678 9012 3456" maxlength="19"
                                        value="<?= htmlspecialchars($_POST['card_number'] ?? '') ?>">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="card_expiry">Expiry Date</label>
                                        <input class="form-input" id="card_expiry" name="card_expiry" placeholder="MM / YY"
                                            value="<?= htmlspecialchars($_POST['card_expiry'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="card_cvv">CVV</label>
                                        <input class="form-input" id="card_cvv" name="card_cvv" placeholder="123"
                                            maxlength="4" value="<?= htmlspecialchars($_POST['card_cvv'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div id="cod-form" class="cod-form<?= $paymentMethod === 'cod' ? '' : ' hidden' ?>">
                                <div class="cod-form-title">💵 Cash on Delivery Selected</div>
                                <p class="cod-form-message">
                                    You will pay <strong id="cod-total"><?= $formattedTotal ?> EGP</strong>
                                    in cash when your order is delivered.
                                </p>
                            </div>

                            <div class="payment-submit-wrap">
                                <button class="btn-primary" type="submit" id="place-order-btn"
                                    data-total="<?= htmlspecialchars($formattedTotal) ?>">
                                    Place Order — <?= $formattedTotal ?> EGP 🎀
                                </button>
                            </div>
                        </form>
                    </div>

                    <aside class="order-mini">
                        <div class="order-mini-title">Order Review 🛍</div>

                        <?php foreach ($items as $item):
                            $color = $item['color'] ?? '';
                            $isHexColor = preg_match('/^#[0-9A-Fa-f]{3,8}$/', $color);
                            ?>
                            <div class="order-mini-item">
                                <div class="omi-img">
                                    <img src="<?= htmlspecialchars($item['image'] ?? '') ?>"
                                        alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>"
                                        style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                </div>
                                <div class="omi-body">
                                    <div class="omi-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="omi-var">
                                        <span>Size <?= htmlspecialchars($item['size']) ?></span>
                                        <span>·</span>
                                        <?php if ($isHexColor): ?>
                                            <span class="omi-color-dot" style="background:<?= htmlspecialchars($color) ?>"></span>
                                        <?php endif; ?>
                                        <span>Color <?= htmlspecialchars($color) ?></span>
                                        <span>· ×<?= (int) $item['quantity'] ?></span>
                                    </div>
                                    <div class="omi-pr">
                                        <span class="omi-pr-unit"><?= number_format($item['unit_price'], 2) ?> EGP each</span>
                                        · <?= number_format($item['subtotal'], 2) ?> EGP
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="order-summary-rows">
                            <div class="cs-row"><span class="cs-lbl">Subtotal</span><span class="cs-val"
                                    id="sum-subtotal"><?= $formattedSubtotal ?> EGP</span></div>
                            <div class="cs-row"><span class="cs-lbl">Shipping</span><span class="cs-val"
                                    id="sum-shipping"><?= $formattedShipping ?> EGP</span></div>
                            <div class="cs-row total"><span class="cs-lbl">Total</span><span class="cs-val"
                                    id="sum-total"><?= $formattedTotal ?> EGP</span></div>
                        </div>

                        <div class="payment-secure-note">🔒 Secured by 256-bit SSL encryption</div>
                    </aside>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'component/footer.php'; ?>

    <?php if (!$isEmpty): ?>
        <script src="../javascript/payment.js" defer></script>
    <?php endif; ?>
</body>

</html>