<?php
require_once __DIR__ . '/../../Controller/trackorder_Controller.php';

$order = null;
$trackError = '';
$orderRef = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = (new TrackOrderController())->processTrack($_POST);
    $order = $result['order'];
    $trackError = $result['trackError'];
    $orderRef = $result['orderRef'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/tracking.css">
    <script src="../javascript/navbar.js" defer></script>
    <title>Track Order</title>
</head>

<body>
    <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-track">
        <div class="page-header">
            <div class="sec-tag">📦 My Orders</div>
            <h1 class="sec-title">Track Your <em>Order</em></h1>
        </div>

        <div class="page-wrap">
            <div class="track-box">
                <form method="POST" action="">
                    <label class="form-label" for="track-input">Enter Order ID</label>
                    <div class="track-input-row">
                        <input class="track-inp" id="track-input" type="text" name="order_id"
                            placeholder="e.g. 1 or TRK123456"
                            value="<?= htmlspecialchars($orderRef) ?>" required>
                        <button class="btn-primary" type="submit">Track 🔍</button>
                    </div>
                </form>

                <?php if ($trackError !== ''): ?>
                    <div class="track-error" role="alert" style="display:block;">
                        <?= htmlspecialchars($trackError) ?>
                    </div>
                <?php endif; ?>

                <?php if ($order): ?>
                    <div class="track-result show">
                        <div class="track-order-info">
                            <div>
                                <div class="toi-id"><?= htmlspecialchars($order['order_id']) ?></div>
                                <div class="toi-date">Placed: <?= htmlspecialchars($order['order_date']) ?></div>
                            </div>
                            <div class="toi-total"><?= htmlspecialchars($order['total_price']) ?></div>
                        </div>

                        <div class="status-timeline">
                            <?php foreach ($order['timeline'] as $step):
                                $class = $step['state'] === 'done' ? 'done' : ($step['state'] === 'active' ? 'active' : '');
                                $dot = $step['state'] === 'done' ? '✓' : ($step['state'] === 'active' ? '→' : '○');
                                ?>
                                <div class="st-step <?= $class ?>">
                                    <div class="st-dot"><?= $dot ?></div>
                                    <div class="st-lbl"><?= htmlspecialchars($step['label']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($order['status_key'] === 'delivered'): ?>
                            <div class="track-estimate">✅ Your order has been delivered.</div>
                        <?php elseif (!empty($order['estimated_delivery'])): ?>
                            <div class="track-estimate">
                                📦 Estimated delivery:
                                <strong style="color:var(--rose-d)"><?= htmlspecialchars($order['estimated_delivery']) ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="track-actions" style="display:flex;gap:12px;margin-top:16px;">
                            <button class="btn-secondary" type="button"
                                onclick="window.location.href='/Tretto.eg--System/MVC/View/GUI/reviews.php'">
                                Write a Review ⭐
                            </button>
                            <button class="btn-ghost" type="button"
                                onclick="window.location.href='/Tretto.eg--System/MVC/View/GUI/support.php'">
                                Need Help? →
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'component/footer.php'; ?>
</body>

</html>
