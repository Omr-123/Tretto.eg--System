<?php
require_once __DIR__ . '/../../../config.php';
ensure_session();

$order_ID = (int)($_GET['order_id'] ?? $_GET['order_ID'] ?? 0);
$amount   = (float)($_GET['amount'] ?? 0);

if ($order_ID <= 0 || $amount <= 0) {
    http_response_code(422);
    echo 'Invalid order or amount. Open this page with ?order_id=1&amount=850';
    exit;
}
$APP_BASE = app_base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tretto.eg — Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --blush:#FFF0F3;--rose:#E8678A;--rose-d:#C44A6D;
            --dark:#2D1B25;--mid:#6B3D52;--muted:#A07088;
            --white:#FFFAFC;--border:rgba(232,103,138,.18);
            --success:#27ae60;--danger:#e74c3c;
        }
        body{font-family:'DM Sans',sans-serif;background:#F9F0F4;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px 16px}
        .checkout-wrap{width:100%;max-width:520px}
        .checkout-logo{font-family:'Playfair Display',serif;font-size:26px;color:var(--dark);text-align:center;margin-bottom:4px}
        .checkout-logo span{color:var(--rose)}
        .checkout-sub{text-align:center;font-size:12px;color:var(--muted);margin-bottom:28px}

        /* Order Summary */
        .order-summary{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:20px 24px;margin-bottom:20px}
        .os-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;margin-bottom:14px}
        .os-row{display:flex;justify-content:space-between;font-size:13px;color:var(--mid);margin-bottom:8px}
        .os-total{display:flex;justify-content:space-between;font-size:16px;font-weight:700;color:var(--dark);padding-top:12px;border-top:1px solid var(--border);margin-top:8px}
        .os-total span:last-child{color:var(--rose-d)}

        /* Payment Method Tabs */
        .pay-tabs{display:flex;gap:12px;margin-bottom:20px}
        .pay-tab{flex:1;padding:14px;border:2px solid var(--border);border-radius:12px;text-align:center;cursor:pointer;transition:all .2s;background:var(--white)}
        .pay-tab.active{border-color:var(--rose);background:rgba(232,103,138,.06)}
        .pay-tab-ico{font-size:24px;margin-bottom:4px}
        .pay-tab-label{font-size:12px;font-weight:700;color:var(--mid)}
        .pay-tab.active .pay-tab-label{color:var(--rose-d)}

        /* Form */
        .pay-form{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:24px}
        .form-group{margin-bottom:16px}
        .form-label{display:block;font-size:10px;letter-spacing:.13em;text-transform:uppercase;color:var(--mid);margin-bottom:7px;font-weight:700}
        .form-input{width:100%;padding:12px 14px;border:1.5px solid var(--border);background:var(--blush);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--dark);outline:none;border-radius:10px;transition:border-color .2s}
        .form-input:focus{border-color:var(--rose)}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .btn-pay{width:100%;padding:14px;background:var(--rose);color:white;border:none;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;cursor:pointer;transition:background .2s;margin-top:8px}
        .btn-pay:hover{background:var(--rose-d)}
        .btn-pay:disabled{background:var(--muted);cursor:not-allowed}

        /* Result */
        .result{padding:20px;border-radius:12px;text-align:center;margin-top:16px;display:none}
        .result.success{background:#D1FAE5;color:#065F46}
        .result.error{background:#FEE2E2;color:#991B1B}
        .result-ico{font-size:32px;margin-bottom:8px}
        .result-msg{font-size:14px;font-weight:600}
        .result-change{font-size:20px;font-weight:700;margin-top:8px}

        /* Loading */
        .loading{display:none;text-align:center;padding:16px;color:var(--muted);font-size:13px}
    </style>
</head>
<body>
<div class="checkout-wrap">
    <div class="checkout-logo">Tretto<span>.</span>eg</div>
    <div class="checkout-sub">Secure Checkout</div>

    <!-- Order Summary -->
    <div class="order-summary">
        <div class="os-title">Order Summary</div>
        <div class="os-row">
            <span>Order ID</span>
            <span>#<?= $order_ID ?></span>
        </div>
        <div class="os-total">
            <span>Total Amount</span>
            <span><?= number_format($amount, 2) ?> EGP</span>
        </div>
    </div>

    <!-- Payment Method Tabs -->
    <div class="pay-tabs">
        <div class="pay-tab active" id="tab-cash" onclick="switchTab('cash')">
            <div class="pay-tab-ico">💵</div>
            <div class="pay-tab-label">Cash</div>
        </div>
        <div class="pay-tab" id="tab-visa" onclick="switchTab('visa')">
            <div class="pay-tab-ico">💳</div>
            <div class="pay-tab-label">Visa / Card</div>
        </div>
    </div>

    <!-- CASH FORM -->
    <div class="pay-form" id="cash-form">
        <div class="form-group">
            <label class="form-label">Amount You're Paying (EGP)</label>
            <input class="form-input" type="number" id="cash-received"
                placeholder="Enter amount" min="<?= $amount ?>" step="0.01">
        </div>
        <div class="form-group">
            <label class="form-label">Payment Location (Optional)</label>
            <input class="form-input" type="text" id="cash-location"
                placeholder="e.g. Cairo Branch">
        </div>

        <!-- بيحسب الباقي في real time -->
        <div style="background:var(--blush);border-radius:10px;padding:12px 16px;margin-bottom:16px">
            <div style="font-size:11px;color:var(--muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.1em">Change Due</div>
            <div id="change-display" style="font-size:22px;font-weight:700;color:var(--dark)">0.00 EGP</div>
        </div>

        <button class="btn-pay" id="cash-pay-btn" onclick="processCashPayment()">Pay Now 💵</button>
    </div>

    <!-- VISA FORM -->
    <div class="pay-form" id="visa-form" style="display:none">
        <div class="form-group">
            <label class="form-label">Card Number</label>
            <input class="form-input" type="text" id="visa-card-number"
                placeholder="1234 5678 9012 3456" maxlength="16">
        </div>
        <div class="form-group">
            <label class="form-label">Cardholder Name</label>
            <input class="form-input" type="text" id="visa-holder-name"
                placeholder="Name as on card">
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input class="form-input" type="date" id="visa-expiry">
            </div>
            <div class="form-group">
                <label class="form-label">CVV</label>
                <input class="form-input" type="password" id="visa-cvv"
                    placeholder="•••" maxlength="4">
            </div>
        </div>

        <button class="btn-pay" id="visa-pay-btn" onclick="processVisaPayment()">Pay <?= number_format($amount, 2) ?> EGP 💳</button>
    </div>

    <!-- Loading -->
    <div class="loading" id="loading">⏳ Processing payment...</div>

    <!-- Result -->
    <div class="result" id="result">
        <div class="result-ico" id="result-ico"></div>
        <div class="result-msg" id="result-msg"></div>
        <div class="result-change" id="result-change"></div>
    </div>
</div>

<script>
const APP_BASE = <?= json_encode($APP_BASE) ?>;
const ORDER_ID = <?= $order_ID ?>;
const AMOUNT   = <?= $amount ?>;

// ── Switch between Cash and Visa tabs
function switchTab(type) {
    document.getElementById('cash-form').style.display = type === 'cash' ? 'block' : 'none';
    document.getElementById('visa-form').style.display = type === 'visa' ? 'block' : 'none';
    document.getElementById('tab-cash').classList.toggle('active', type === 'cash');
    document.getElementById('tab-visa').classList.toggle('active', type === 'visa');
    document.getElementById('result').style.display = 'none';
}

// ── Calculate change in real time
document.getElementById('cash-received').addEventListener('input', function() {
    const received = parseFloat(this.value) || 0;
    const change   = received - AMOUNT;
    const display  = document.getElementById('change-display');
    display.textContent = change.toFixed(2) + ' EGP';
    display.style.color = change >= 0 ? 'var(--success)' : 'var(--danger)';
});

// ── Process Cash Payment — بيبعت لـ PaymentController
function processCashPayment() {
    const received = parseFloat(document.getElementById('cash-received').value);
    const location = document.getElementById('cash-location').value;

    if (!received || received < AMOUNT) {
        showResult(false, 'Insufficient amount. Minimum: ' + AMOUNT.toFixed(2) + ' EGP');
        return;
    }

    setLoading(true);
    fetch(APP_BASE + '/MVC/Controller/PaymentController.php?action=processPayment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            order_ID:        ORDER_ID,
            amount:          AMOUNT,
            payment_type:    'Cash',
            receivedAmount:  received,
            paymentLocation: location
        })
    })
    .then(r => r.json())
    .then(res => {
        setLoading(false);
        if (res.success) {
            showResult(true, 'Payment successful!', 'Change: ' + parseFloat(res.change).toFixed(2) + ' EGP');
            // بعد 3 ثواني بنروح للـ homepage
            setTimeout(() => window.location.href = APP_BASE + '/user.html', 3000);
        } else {
            showResult(false, res.message || 'Payment failed.');
        }
    })
    .catch(() => { setLoading(false); showResult(false, 'Connection error. Please try again.'); });
}

// ── Process Visa Payment — بيبعت لـ PaymentController
function processVisaPayment() {
    const cardNumber     = document.getElementById('visa-card-number').value.replace(/\s/g, '');
    const cardHolderName = document.getElementById('visa-holder-name').value;
    const expiryDate     = document.getElementById('visa-expiry').value;
    const cvv            = document.getElementById('visa-cvv').value;

    // Client-side validation الأول
    if (cardNumber.length !== 16) { showResult(false, 'Card number must be 16 digits.'); return; }
    if (!cardHolderName)          { showResult(false, 'Please enter cardholder name.'); return; }
    if (!expiryDate)              { showResult(false, 'Please enter expiry date.'); return; }
    if (cvv.length < 3)           { showResult(false, 'CVV must be 3-4 digits.'); return; }

    setLoading(true);
    fetch(APP_BASE + '/MVC/Controller/PaymentController.php?action=processPayment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            order_ID:       ORDER_ID,
            amount:         AMOUNT,
            payment_type:   'Visa',
            cardNumber:     cardNumber,
            cardHolderName: cardHolderName,
            expiryDate:     expiryDate,
            cvv:            cvv
        })
    })
    .then(r => r.json())
    .then(res => {
        setLoading(false);
        if (res.success) {
            showResult(true, 'Payment successful! Redirecting...');
            setTimeout(() => window.location.href = APP_BASE + '/user.html', 3000);
        } else {
            showResult(false, res.message || 'Card authorization failed.');
        }
    })
    .catch(() => { setLoading(false); showResult(false, 'Connection error. Please try again.'); });
}

// ── Helper functions
function setLoading(show) {
    document.getElementById('loading').style.display = show ? 'block' : 'none';
    document.getElementById('cash-pay-btn').disabled = show;
    document.getElementById('visa-pay-btn').disabled = show;
}

function showResult(success, msg, extra = '') {
    const el = document.getElementById('result');
    el.className = 'result ' + (success ? 'success' : 'error');
    el.style.display = 'block';
    document.getElementById('result-ico').textContent  = success ? '✅' : '❌';
    document.getElementById('result-msg').textContent  = msg;
    document.getElementById('result-change').textContent = extra;
}
</script>
</body>
</html>
