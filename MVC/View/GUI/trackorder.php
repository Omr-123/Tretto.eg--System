<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../css/navbar.css">
     <link rel="stylesheet" href="../css/main.css">
     <link rel="stylesheet" href="../css/tracking.css">
    
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
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
                <label class="form-label">Enter Order ID</label>
                <div class="track-input-row">
                    <input class="track-inp" id="track-input" placeholder="e.g. TRT-20251116-0042" type="text">
                    <button class="btn-primary" onclick="doTrack()">Track 🔍</button>
                </div>
                <div class="track-result" id="track-result">
                    <div class="track-order-info">
                        <div>
                            <div class="toi-id" id="toi-id">TRT-20251116-0042</div>
                            <div class="toi-date">Placed: 16 Nov 2025</div>
                        </div>
                        <div class="toi-total">3,480 EGP</div>
                    </div>
                    <div class="status-timeline">
                        <div class="st-step done">
                            <div class="st-dot">✓</div>
                            <div class="st-lbl">Placed</div>
                        </div>
                        <div class="st-step done">
                            <div class="st-dot">✓</div>
                            <div class="st-lbl">Confirmed</div>
                        </div>
                        <div class="st-step active">
                            <div class="st-dot">→</div>
                            <div class="st-lbl">Shipped</div>
                        </div>
                        <div class="st-step">
                            <div class="st-dot">○</div>
                            <div class="st-lbl">Delivered</div>
                        </div>
                    </div>
                    <div
                        style="background:rgba(232,103,138,.07);border:1.5px solid var(--border);padding:16px;margin-bottom:20px;font-size:13px;color:var(--mid);border-radius:12px">
                        📦 Your order is on its way! Estimated delivery: <strong style="color:var(--rose-d)">18–20 Nov
                            2025</strong>
                    </div>
                    <div style="display:flex;gap:12px;margin-top:16px">
                        <button class="btn-secondary" onclick="goTo('reviews')">Write a Review ⭐</button>
                        <button class="btn-ghost" onclick="goTo('support')">Need Help? →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
