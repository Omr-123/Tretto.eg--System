<?php
require_once(__DIR__ . '/../../../db.php');

$orderID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$order = null;
$orderItems = [];

if ($orderID > 0) {
    $sql = "SELECT o.*, u.name AS userName 
            FROM orders o 
            JOIN User u ON o.userID = u.userID 
            WHERE o.order_ID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_object();

    $stmt = $conn->prepare("SELECT product_name, quantity, price 
                            FROM order_items 
                            WHERE order_ID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation #<?php echo $order->order_ID ?? 'N/A'; ?></title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/placeorder.css">
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
</head>
<body>
<div class="page" id="page-place-order">
    <div class="success-wrap">
        <div class="success-confetti">🎀</div>
        <div class="success-anim">✓</div>

        <div class="success-tag">Order Placed!</div>

        <h1 class="success-title">
            Thank you,<br>
            <em><?php echo $order->userName ?? 'Customer'; ?></em>! 🌸
        </h1>

        <div class="success-order-id">
            Order #<?php echo $order->order_ID ?? 'N/A'; ?>
        </div>

        <div class="success-info-grid">
            <div class="sinfo-card">
                <div class="sinfo-ico">📦</div>
                <div class="sinfo-lbl">Estimated Delivery</div>
                <div class="sinfo-val">
                    <?php echo $order->deliveryDate ?? 'TBD'; ?>
                </div>
            </div>

            <div class="sinfo-card">
                <div class="sinfo-ico">💳</div>
                <div class="sinfo-lbl">Payment Method</div>
                <div class="sinfo-val">
                    <?php echo $order->paymentMethod ?? 'N/A'; ?>
                </div>
            </div>

            <div class="sinfo-card">
                <div class="sinfo-ico">📍</div>
                <div class="sinfo-lbl">Shipping To</div>
                <div class="sinfo-val">
                    <?php echo $order->shippingAddress ?? 'N/A'; ?>
                </div>
            </div>

            <div class="sinfo-card">
                <div class="sinfo-ico">💰</div>
                <div class="sinfo-lbl">Total Paid</div>
                <div class="sinfo-val" style="color:#d4a373">
                    <?php echo isset($order->totalAmount) ? number_format($order->totalAmount, 2) : '0.00'; ?> EGP
                </div>
            </div>
        </div>

        <div class="success-details">
            <div class="sd-title">🛍 Your Items</div>

            <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                    <div class="sd-item" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                        <span>
                            <strong><?php echo $item['product_name']; ?></strong>
                            &times; <?php echo (int)$item['quantity']; ?>
                        </span>
                        <span><?php echo number_format($item['price'] * $item['quantity'], 2); ?> EGP</span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No items found for this order.</p>
            <?php endif; ?>
        </div>

        <div class="success-acts" style="margin-top: 30px; display: flex; flex-direction: column; gap: 12px; align-items: center;">
            <a href="trackorder.php?id=<?php echo $order->order_ID ?? 0; ?>" class="btn-primary" style="text-decoration: none; display: inline-block; width: 100%; text-align: center;">
                Track My Order 📦
            </a>
            <a href="index.php" class="btn-secondary" style="text-decoration: none; display: inline-block; width: 100%; text-align: center;">
                Continue Shopping 🛍
            </a>
            <a href="review.php?order_id=<?php echo $order->order_ID ?? 0; ?>" class="btn-ghost" style="text-decoration: none; display: inline-block; width: 100%; text-align: center;">
                Write a Review ⭐
            </a>
        </div>
    </div>
</div>
</body>
</html>
