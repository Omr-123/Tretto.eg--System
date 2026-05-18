<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../../db.php';
require_once __DIR__ . '/../../Model/Checkout.php';

if (!isset($cartItems) || !isset($total)) {
    $userID = $_SESSION['userID'] ?? null;
    if ($userID) {
        $cartData = Checkout::getCartItems($userID);
        $cartItems = $cartData['items'];
        $total = $cartData['total'];
    } else {
        $cartItems = [];
        $total = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
</head>

<body>
    <?php include 'component/navbar.php'; ?>
    <div class="page" id="page-checkout">
        <div class="page-header">
            <div class="sec-tag">Step 2 of 3</div>
            <h1 class="sec-title">Shipping <em>Details</em> 📦</h1>
        </div>
        <div class="page-wrap">
            <div class="checkout-layout">
                <div>
                    <!-- Contact Information -->
                    <div class="checkout-form-box">
                        <div class="co-section-title">Contact Information</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input class="form-input" name="firstName" placeholder="Enter your first name"
                                    value="<?= htmlspecialchars($user['firstName'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input class="form-input" name="lastName" placeholder="Enter your last name"
                                    value="<?= htmlspecialchars($user['lastName'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" name="email" placeholder="your@email.com"
                                value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input class="form-input" name="phone" placeholder="01xxxxxxxxx"
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="checkout-form-box" style="margin-top:20px">
                        <div class="co-section-title">Shipping Address 📍</div>
                        <form method="POST" action="/Tretto.eg--System/MVC/Controller/checkout_Controller.php">
                            <div class="form-group">
                                <label class="form-label">Governorate</label>
                                <select class="form-input" name="governorate">
                                    <option value="">Select Governorate</option>
                                    <option value="Cairo">Cairo</option>
                                    <option value="Giza">Giza</option>
                                    <option value="Alexandria">Alexandria</option>
                                    <option value="Luxor">Luxor</option>
                                    <option value="Aswan">Aswan</option>
                                    <option value="Mansoura">Mansoura</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City / District</label>
                                <input class="form-input" name="city" placeholder="Nasr City">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Street Address</label>
                                <input class="form-input" name="street" placeholder="12 Abbas Al-Aqqad St">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Building / Apartment</label>
                                <input class="form-input" name="building" placeholder="Building 5, Apt 3">
                            </div>
                            <?php if (!empty($_SESSION['checkout_error'])): ?>
                                <div class="error-msg" id="co-err" style="margin-bottom:12px">
                                    <?= htmlspecialchars($_SESSION['checkout_error']) ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn-primary">Continue to Payment 💳</button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-mini">
                    <div class="order-mini-title">Your Order 🛍</div>
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-mini-item">
                                <div class="omi-img">
                                    <img src="../images/products/<?= $item['PID'] ?>.jpg"
                                        alt="<?= htmlspecialchars($item['name']) ?>"
                                        style="width:100%;height:100%;object-fit:cover">
                                </div>
                                <div>
                                    <div class="omi-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="omi-var"><?= htmlspecialchars($item['category']) ?></div>
                                    <div class="omi-pr"><?= number_format($item['price'] * $item['quantity'], 2) ?> EGP</div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No items in your cart.</p>
                    <?php endif; ?>
                    <div style="border-top:1px solid var(--border);padding-top:14px;margin-top:4px">
                        <div class="cs-row total">
                            <span class="cs-lbl">Total</span>
                            <span class="cs-val"><?= number_format($total ?? 0, 2) ?> EGP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'component/footer.php'; ?>
    <script src="../javascript/all.js" defer></script>
</body>

</html>