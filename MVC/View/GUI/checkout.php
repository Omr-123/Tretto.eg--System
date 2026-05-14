<?php
require_once(__DIR__ . '/../../../db.php');

$userID = 1;

$cartItems = [];
$total = 0;

$sql = "SELECT c.*, p.name, p.image, p.category, p.price 
        FROM cart c 
        JOIN product p ON c.prod_ID = p.prod_ID 
        WHERE c.userID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $userID);
$stmt->execute();
$res = $stmt->get_result();


while ($row = $res->fetch_assoc()) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
    <title>Checkout</title>
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
                    <!-- Contact Info -->
                    <div class="checkout-form-box">
                        <div class="co-section-title">Contact Information</div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">First Name</label><input
                                    class="form-input" id="co-fname" placeholder="Sara"></div>
                            <div class="form-group"><label class="form-label">Last Name</label><input class="form-input"
                                    id="co-lname" placeholder="Ahmed"></div>
                        </div>
                        <div class="form-group"><label class="form-label">Email</label><input class="form-input"
                                type="email" placeholder="your@email.com"></div>
                        <div class="form-group"><label class="form-label">Phone</label><input class="form-input"
                                id="co-phone" placeholder="01xxxxxxxxx"></div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="checkout-form-box" style="margin-top:20px">
                        <div class="co-section-title">Shipping Address 📍</div>
                        <div class="form-group"><label class="form-label">Governorate</label>
                            <select class="form-input">
                                <option>Cairo</option>
                                <option>Giza</option>
                                <option>Alexandria</option>
                                <option>Luxor</option>
                                <option>Aswan</option>
                                <option>Mansoura</option>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">City / District</label><input
                                class="form-input" placeholder="Nasr City"></div>
                        <div class="form-group"><label class="form-label">Street Address</label><input
                                class="form-input" placeholder="12 Abbas Al-Aqqad St"></div>
                        <div class="form-group"><label class="form-label">Building / Apartment</label><input
                                class="form-input" placeholder="Building 5, Apt 3"></div>
                        <div class="error-msg" id="co-err" style="margin-bottom:12px">Please fill in all required
                            shipping fields.</div>
                        <button class="btn-primary" onclick="doCheckout()">Continue to Payment 💳</button> 
                        <!-- go to page payment  -->
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-mini">
                    <div class="order-mini-title">Your Order 🛍</div>
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-mini-item">
                                <div class="omi-img">
                                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>"
                                        style="width:100%;height:100%;object-fit:cover">
                                </div>
                                <div>
                                    <div class="omi-name"><?= $item['name'] ?></div>
                                    <div class="omi-var"><?= $item['category'] ?></div>
                                    <div class="omi-pr"><?= $item['price'] ?> EGP × <?= $item['quantity'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div style="border-top:1px solid var(--border);padding-top:14px;margin-top:4px">
                            <div class="cs-row total">
                                <span class="cs-lbl">Total</span>
                                <span class="cs-val"><?= $total ?> EGP</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="error-msg">❌ Cart is empty</div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
</body>

</html>