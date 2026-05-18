<?php
require_once __DIR__ . '/../../Controller/exchange_controller.php';

$controller   = new ExchangeController();
$submitResult = null;

// ── Handle POST submit first ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitResult = $controller->handleSubmit();
}

// ── Load page data (orders + products) ───────────────────────────────────────
$pageData = $controller->loadPage();
$orders   = $pageData['orders'];
echo "<pre>";
print_r($orders);
echo "</pre>";
$products = $pageData['products'];

// ── Keep old POST values to re-fill form on error ─────────────────────────────
$old = $_POST ?? [];
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Exchange — Tretto.eg</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/exchange.css">
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/exchange.js" defer></script>
</head>
<body>

<div id="cur"></div>
<div id="cur-r"></div>

<div id="toast"><span class="t-ico">✨</span><span id="tmsg"></span></div>

<nav>
    <a class="logo">Tretto<span>.</span>eg <span class="logo-heart">♥</span></a>
    <a class="nav-back" onclick="history.back()">Back to My Orders</a>
</nav>

<div class="page-hero">
    <div class="hero-inner">
        <div class="hero-tag"><span class="hero-tag-dot"></span> Return Policy — 14 Days</div>
        <h1 class="hero-title">Request a <em>Refund</em><br>or Exchange 🔄</h1>
        <p class="hero-sub">Not happy with your order? We're here to help. Submit your request below and our
            team will get back to you within 24 hours. 💕</p>
    </div>
</div>

<div class="policy-strip">
    <div class="policy-item"><span class="policy-ico">📅</span>
        <div class="policy-txt"><strong>14-Day Window</strong>Submit within 14 days of delivery</div>
    </div>
    <div class="policy-item"><span class="policy-ico">📦</span>
        <div class="policy-txt"><strong>Unworn Items Only</strong>Items must be in original condition</div>
    </div>
    <div class="policy-item"><span class="policy-ico">✅</span>
        <div class="policy-txt"><strong>Fast Processing</strong>Reviewed within 2–3 business days</div>
    </div>
    <div class="policy-item"><span class="policy-ico">🔔</span>
        <div class="policy-txt"><strong>You'll Be Notified</strong>Email & SMS updates throughout</div>
    </div>
</div>


<!-- ═══════════════════ SUCCESS SCREEN (shown after successful submit) ════════ -->
<?php if ($submitResult && $submitResult['success']): ?>

<div class="success-screen" style="display:flex">
    <div class="success-confetti">🎀</div>
    <div class="success-anim">✓</div>
    <div class="success-badge">Request Submitted!</div>
    <h1 class="success-title">We've got your<br><em>request</em>! 🌸</h1>
    <p class="success-sub">Your refund/exchange request has been submitted successfully. Our team will review
        it and get back to you within 24 hours. 💕</p>

    <div class="success-ref"><?= $submitResult['reference'] ?></div>

    <div class="success-details">
        <div class="sd-row">
            <span class="sd-lbl">Request Type</span>
            <span class="sd-val"><?= ucfirst($submitResult['request_type']) ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Item</span>
            <span class="sd-val"><?= $submitResult['product_name'] ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Reason</span>
            <span class="sd-val"><?= $submitResult['reason'] ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Order ID</span>
            <span class="sd-val">#<?= $submitResult['order_id'] ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Submitted</span>
            <span class="sd-val"><?= $submitResult['submitted_at'] ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Contact Method</span>
            <span class="sd-val"><?= ucfirst($submitResult['contact']) ?></span>
        </div>
        <div class="sd-row">
            <span class="sd-lbl">Expected Response</span>
            <span class="sd-val" style="color:var(--rose-d)">Within 24 hours</span>
        </div>
    </div>

    <div class="success-note">
        📧 A confirmation has been sent to your email.<br>
        📱 Keep your reference number handy — you'll need it when our team contacts you.
    </div>

    <div class="success-acts">
        <a href="exchange.php" class="btn-rose">Submit Another Request</a>
        <a href="orders.php"   class="btn-outline-rose">View My Orders</a>
    </div>
</div>

<?php else: ?>
<!-- ═══════════════════════════ FORM VIEW ════════════════════════════════════ -->

<?php if ($submitResult && !$submitResult['success']): ?>
<!-- Error banner — shown when submit failed -->
<div class="error-banner">
    ⚠️ <?= $submitResult['message'] ?>
    <?php if (!empty($submitResult['errors'])): ?>
    <ul>
        <?php foreach ($submitResult['errors'] as $msg): ?>
            <li><?= $msg ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
<?php endif; ?>

<div id="form-view">
    <div class="main-wrap">

        <!-- ══════════════════════════ MAIN FORM COLUMN ══════════════════════ -->
        <div>

            <!-- Steps indicator -->
            <div class="steps-indicator">
                <div class="step-ind done" id="step1">
                    <div class="si-dot">✓</div>
                    <div class="si-lbl">Select Item</div>
                </div>
                <div class="step-ind current" id="step2">
                    <div class="si-dot">2</div>
                    <div class="si-lbl">Request Type</div>
                </div>
                <div class="step-ind" id="step3">
                    <div class="si-dot">3</div>
                    <div class="si-lbl">Details</div>
                </div>
                <div class="step-ind" id="step4">
                    <div class="si-dot">4</div>
                    <div class="si-lbl">Submit</div>
                </div>
            </div>

            <!-- ══════════════════════ THE FORM ══════════════════════════════ -->
            <form method="POST" action="" id="exchange-form">

                <!-- Hidden fields filled by JS when user clicks cards/chips -->
                <input type="hidden" name="order_id"       id="h-order-id"
                       value="<?= $old['order_id']       ?? '' ?>">
                <input type="hidden" name="old_product_id" id="h-product-id"
                       value="<?= $old['old_product_id'] ?? '' ?>">
                <input type="hidden" name="request_type"   id="h-request-type"
                       value="<?= $old['request_type']   ?? '' ?>">
                <input type="hidden" name="reason"         id="h-reason"
                       value="<?= $old['reason']         ?? '' ?>">
                <input type="hidden" name="contact_method" id="h-contact-method"
                       value="<?= $old['contact_method'] ?? 'whatsapp' ?>">

                <!-- ══════════ CARD 1 — SELECT ITEM ══════════════════════════ -->
                <div class="form-card" id="card-item">
                    <div class="form-card-title">🛍 Select the Item</div>
                    <div class="form-card-sub">Choose the delivered order you'd like to return or exchange</div>

                    <div class="order-cards" id="order-cards-container">

                        <?php if (empty($orders)): ?>
                        <div class="empty-orders">
                            <div class="empty-ico">📦</div>
                            <div class="empty-title">No delivered orders found</div>
                            <div class="empty-sub">No delivered orders available for exchange or refund.</div>
                        </div>

                        <?php else: ?>
                        <?php foreach ($orders as $order):
                            $isSelected = (string)($old['order_id'] ?? '') === (string)$order['order_id'];
                        ?>
                        <div class="order-sel-card <?= $isSelected ? 'selected' : '' ?>"
                             id="osc-<?= $order['order_id'] ?>"
                             onclick="selectOrder(
                                 '<?= $order['order_id'] ?>',
                                 '<?= $order['product_id'] ?>',
                                 '<?= addslashes($order['product_name']) ?>'
                             )">

                            <div class="osc-radio">
                                <div class="osc-radio-dot <?= $isSelected ? 'active' : '' ?>"></div>
                            </div>

                            <img class="osc-img"
                                 src="<?= $order['product_image'] ?? 'img/placeholder.jpg' ?>"
                                 alt="<?= $order['product_name'] ?>"
                                 onerror="this.src='img/placeholder.jpg'">

                            <div class="osc-info">
                                <div class="osc-name"><?= $order['product_name'] ?></div>
                                <div class="osc-details"><?= $order['variant'] ?? 'No variant info' ?></div>
                                <div class="osc-order-id">
                                    Order #<?= $order['order_id'] ?>
                                    &middot; Delivered
                                    <?= !empty($order['delivery_date'])
                                        ? date('d M Y', strtotime($order['delivery_date']))
                                        : 'N/A' ?>
                                </div>
                            </div>

                            <div class="osc-price">
                                <?= number_format((float) $order['price'], 0) ?> EGP
                            </div>

                            <span class="osc-status delivered">Delivered</span>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <div class="error-msg" id="err-item">Please select an order item to continue.</div>
                </div>

                <!-- ══════════ CARD 2 — REQUEST TYPE ══════════════════════════ -->
                <div class="form-card <?= empty($orders) ? 'card-disabled' : '' ?>" id="card-type">
                    <div class="form-card-title">🔄 Request Type</div>
                    <div class="form-card-sub">What would you like to do with this item?</div>

                    <div class="type-toggle">
                        <div class="type-btn <?= ($old['request_type'] ?? '') === 'refund' ? 'selected' : '' ?>"
                             id="type-refund" onclick="selectType('refund')">
                            <div class="type-check" id="check-refund"></div>
                            <div class="type-btn-ico">💸</div>
                            <div class="type-btn-name">Full Refund</div>
                            <div class="type-btn-desc">Get your money back to your original payment method</div>
                        </div>
                        <div class="type-btn <?= ($old['request_type'] ?? '') === 'exchange' ? 'selected' : '' ?>"
                             id="type-exchange" onclick="selectType('exchange')">
                            <div class="type-check" id="check-exchange"></div>
                            <div class="type-btn-ico">🔁</div>
                            <div class="type-btn-name">Exchange</div>
                            <div class="type-btn-desc">Swap for a different size, colour, or another item</div>
                        </div>
                    </div>
                    <div class="error-msg" id="err-type">Please select a request type.</div>
                </div>

                <!-- ══════════ CARD 3 — REASON ════════════════════════════════ -->
                <div class="form-card <?= empty($orders) ? 'card-disabled' : '' ?>" id="card-reason">
                    <div class="form-card-title">📋 Reason for Request</div>
                    <div class="form-card-sub">Help us understand what went wrong so we can improve 💕</div>

                    <div class="form-group">
                        <label class="form-label">Quick Select <span class="req">*</span></label>
                        <div class="reason-chips" id="reason-chips">
                            <?php
                            $reasons = [
                                'Wrong size received',
                                'Wrong item delivered',
                                'Item arrived damaged',
                                'Item differs from photos',
                                'Quality not as expected',
                                'Changed my mind',
                                'Received duplicate item',
                                'Other',
                            ];
                            foreach ($reasons as $r):
                                $isActive = ($old['reason'] ?? '') === $r;
                            ?>
                            <div class="reason-chip <?= $isActive ? 'active' : '' ?>"
                                 onclick="selectReason(this, '<?= $r ?>')">
                                <?= $r ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="error-msg" id="err-reason">Please select a reason.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Details <span class="req">*</span></label>
                        <textarea class="form-input" name="details" id="reason-detail" maxlength="1000"
                            placeholder="Please describe the issue in more detail… (e.g. I ordered size 38 but received size 40)"
                            oninput="countChars(this,'char-count')"><?= $old['details'] ?? '' ?></textarea>
                        <div class="char-count" id="char-count">0 / 1000 characters</div>
                        <div class="error-msg" id="err-detail">Please add more details (min. 20 characters).</div>
                    </div>

                    <!-- Exchange-only preferences -->
                    <div class="exchange-options" id="exchange-options"
                         style="display:<?= ($old['request_type'] ?? '') === 'exchange' ? 'block' : 'none' ?>">
                        <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
                        <div class="form-card-title" style="font-size:15px;margin-bottom:5px">🔁 Exchange Preferences</div>
                        <div class="form-card-sub">What would you like instead?</div>
                        <div class="ex-row">
                            <div class="form-group">
                                <label class="form-label">Preferred New Size</label>
                                <select class="form-input" name="preferred_size" id="ex-size">
                                    <option value="">Same size</option>
                                    <?php foreach (['36','37','38','39','40','41','42'] as $size): ?>
                                    <option value="<?= $size ?>"
                                        <?= ($old['preferred_size'] ?? '') === $size ? 'selected' : '' ?>>
                                        <?= $size ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Preferred New Colour</label>
                                <select class="form-input" name="preferred_color" id="ex-color">
                                    <option value="">Same colour</option>
                                    <?php foreach (['Denim Blue','Black','Taupe','Beige','Terracotta','Sand'] as $color): ?>
                                    <option value="<?= $color ?>"
                                        <?= ($old['preferred_color'] ?? '') === $color ? 'selected' : '' ?>>
                                        <?= $color ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Exchange for a Different Item?</label>
                            <select class="form-input" name="new_product_id" id="ex-item">
                                <option value="">No — same item, different variant</option>
                                <?php foreach ($products as $product): ?>
                                <option value="<?= $product['product_id'] ?>"
                                    <?= ($old['new_product_id'] ?? '') == $product['product_id'] ? 'selected' : '' ?>>
                                    <?= $product['name'] ?>
                                    (<?= number_format((float) $product['price'], 0) ?> EGP)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ══════════ CARD 4 — CONTACT & CONFIRM ═════════════════════ -->
                <div class="form-card <?= empty($orders) ? 'card-disabled' : '' ?>" id="card-confirm">
                    <div class="form-card-title">📬 Contact & Confirmation</div>
                    <div class="form-card-sub">How should we reach you about this request?</div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Your Name <span class="req">*</span></label>
                            <input class="form-input" name="c_name" id="c-name"
                                   placeholder="Sara Ahmed"
                                   value="<?= $old['c_name'] ?? ($_SESSION['name'] ?? '') ?>">
                            <div class="error-msg" id="err-name">Name is required.</div>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Email Address <span class="req">*</span></label>
                            <input class="form-input" name="c_email" id="c-email" type="email"
                                   placeholder="your@email.com"
                                   value="<?= $old['c_email'] ?? ($_SESSION['email'] ?? '') ?>">
                            <div class="error-msg" id="err-email">Valid email is required.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="req">*</span></label>
                        <input class="form-input" name="c_phone" id="c-phone" type="tel"
                               placeholder="01xxxxxxxxx"
                               value="<?= $old['c_phone'] ?? ($_SESSION['phone'] ?? '') ?>">
                        <div class="error-msg" id="err-phone">Valid Egyptian phone number required.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferred Contact Method</label>
                        <div class="contact-prefs">
                            <?php
                            $contactMethods = [
                                'whatsapp' => ['📱', 'WhatsApp'],
                                'email'    => ['✉️',  'Email'],
                                'phone'    => ['📞', 'Phone Call'],
                            ];
                            $selectedContact = $old['contact_method'] ?? 'whatsapp';
                            foreach ($contactMethods as $val => [$ico, $label]):
                            ?>
                            <label class="contact-pref <?= $selectedContact === $val ? 'active' : '' ?>"
                                   onclick="selectContact('<?= $val ?>')">
                                <input type="radio" name="contact_method" value="<?= $val ?>" hidden
                                       <?= $selectedContact === $val ? 'checked' : '' ?>>
                                <div class="cp-ico"><?= $ico ?></div>
                                <div class="cp-name"><?= $label ?></div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="terms-box">
                        By submitting this request you confirm that:
                        <ul>
                            <li>The item is in its original condition and has not been worn or washed</li>
                            <li>You are within the 14-day return window from your delivery date</li>
                            <li>You agree to Tretto.eg's <a class="terms-link">Return & Exchange Policy</a></li>
                        </ul>
                    </div>

                    <label class="agree-label">
                        <input type="checkbox" id="agree-terms" name="agree_terms" style="accent-color:var(--rose)"
                               <?= isset($old['agree_terms']) ? 'checked' : '' ?>>
                        <span>I confirm that all the information provided is accurate and I agree to the return policy. 🌸</span>
                    </label>
                    <div class="error-msg" id="err-terms">You must agree to the terms to continue.</div>

                    <div class="submit-area">
                        <button type="button" class="btn-submit" id="submit-btn"
                                onclick="submitRequest()"
                                <?= empty($orders) ? 'disabled' : '' ?>>
                            <span>Submit Request</span> <span>💕</span>
                        </button>
                        <button type="button" class="btn-secondary" onclick="resetForm()">Clear & Start Over</button>
                    </div>

                    <div class="submit-note">
                        🔒 Your request is secure. Reference number sent via email & SMS.<br>
                        Our team responds within <strong>24 hours</strong> on business days.
                    </div>
                </div>

            </form>
        </div>

        <!-- ══════════════════════════════ SIDEBAR ════════════════════════════ -->
        <div class="sidebar">
            <div class="sidebar-card">
                <div class="sc-title">📋 Return Policy</div>
                <div class="policy-list">
                    <div class="policy-list-item"><span class="pli-ico">📅</span>
                        <div class="pli-txt"><strong>14-Day Window</strong>You have 14 days from your delivery date to submit a request.</div>
                    </div>
                    <div class="policy-list-item"><span class="pli-ico">📦</span>
                        <div class="pli-txt"><strong>Original Condition</strong>Items must be unworn, unwashed, and in original packaging with tags.</div>
                    </div>
                    <div class="policy-list-item"><span class="pli-ico">💸</span>
                        <div class="pli-txt"><strong>Refund Method</strong>Refunds are processed to your original payment method within 5–7 days.</div>
                    </div>
                    <div class="policy-list-item"><span class="pli-ico">🔁</span>
                        <div class="pli-txt"><strong>Exchanges</strong>Free exchange for different size or colour. Price difference applies for different items.</div>
                    </div>
                    <div class="policy-list-item"><span class="pli-ico">🚫</span>
                        <div class="pli-txt"><strong>Non-returnable</strong>Items marked as final sale or worn/damaged by the customer cannot be returned.</div>
                    </div>
                </div>
            </div>

            <div class="sidebar-card">
                <div class="sc-title">⏱ What Happens Next?</div>
                <div class="status-timeline">
                    <div class="stl-item">
                        <div class="stl-dot active">1</div>
                        <div class="stl-info">
                            <div class="stl-name">Request Submitted</div>
                            <div class="stl-desc">Your request is received and logged with a reference number</div>
                        </div>
                    </div>
                    <div class="stl-item">
                        <div class="stl-dot pending">2</div>
                        <div class="stl-info">
                            <div class="stl-name">Admin Review</div>
                            <div class="stl-desc">Our team reviews your request within 2–3 business days</div>
                        </div>
                    </div>
                    <div class="stl-item">
                        <div class="stl-dot pending">3</div>
                        <div class="stl-info">
                            <div class="stl-name">Decision Notified</div>
                            <div class="stl-desc">You're notified via your preferred contact method with the decision</div>
                        </div>
                    </div>
                    <div class="stl-item">
                        <div class="stl-dot pending">4</div>
                        <div class="stl-info">
                            <div class="stl-name">Pickup Arranged</div>
                            <div class="stl-desc">If approved, we arrange free pickup of the item from your address</div>
                        </div>
                    </div>
                    <div class="stl-item">
                        <div class="stl-dot pending">5</div>
                        <div class="stl-info">
                            <div class="stl-name">Refund or Exchange</div>
                            <div class="stl-desc">Refund processed or replacement shipped to you 💕</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-support-card">
                <div class="csc-title">Need Help? 💕</div>
                <div class="csc-sub">Can't find what you're looking for? Our support team is ready to help you.</div>
                <div class="csc-links">
                    <a class="csc-link">📱 WhatsApp: 010 1234 5678</a>
                    <a class="csc-link">📞 Hotline: 19123</a>
                    <a class="csc-link">✉️ support@tretto.eg</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php endif; ?>

<script>
// ─── State ───────────────────────────────────────────────────────────────────
let selectedOrderId   = '<?= $old['order_id']       ?? '' ?>';
let selectedProductId = '<?= $old['old_product_id'] ?? '' ?>';
let selectedType      = '<?= $old['request_type']   ?? '' ?>';
let selectedReason    = '<?= $old['reason']         ?? '' ?>';

// ─── Order card selection ─────────────────────────────────────────────────────
function selectOrder(orderId, productId, productName) {
    selectedOrderId   = orderId;
    selectedProductId = productId;

    document.querySelectorAll('.order-sel-card').forEach(c => c.classList.remove('selected'));
    document.querySelectorAll('.osc-radio-dot').forEach(d => d.classList.remove('active'));

    const card = document.getElementById('osc-' + orderId);
    if (card) {
        card.classList.add('selected');
        card.querySelector('.osc-radio-dot').classList.add('active');
    }

    document.getElementById('h-order-id').value   = orderId;
    document.getElementById('h-product-id').value = productId;
    document.getElementById('err-item').style.display = 'none';
}

// ─── Request type selection ───────────────────────────────────────────────────
function selectType(type) {
    selectedType = type;
    document.getElementById('h-request-type').value = type;

    document.getElementById('type-refund').classList.toggle('selected',   type === 'refund');
    document.getElementById('type-exchange').classList.toggle('selected', type === 'exchange');

    document.getElementById('exchange-options').style.display =
        type === 'exchange' ? 'block' : 'none';

    document.getElementById('err-type').style.display = 'none';
}

// ─── Reason chip selection ────────────────────────────────────────────────────
function selectReason(el, reason) {
    selectedReason = reason;
    document.getElementById('h-reason').value = reason;

    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('err-reason').style.display = 'none';
}

// ─── Contact method selection ─────────────────────────────────────────────────
function selectContact(val) {
    document.getElementById('h-contact-method').value = val;
    document.querySelectorAll('.contact-pref').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('input[name="contact_method"]').forEach(r => {
        if (r.value === val) { r.checked = true; r.closest('.contact-pref').classList.add('active'); }
    });
}

// ─── Character counter ────────────────────────────────────────────────────────
function countChars(el, counterId) {
    document.getElementById(counterId).textContent = el.value.length + ' / 1000 characters';
}

// ─── Client-side validation before submit ─────────────────────────────────────
function validateAll() {
    let ok = true;

    if (!selectedOrderId) {
        document.getElementById('err-item').style.display = 'block';
        ok = false;
    }

    if (!selectedType) {
        document.getElementById('err-type').style.display = 'block';
        ok = false;
    }

    if (!selectedReason) {
        document.getElementById('err-reason').style.display = 'block';
        ok = false;
    }

    const details = document.getElementById('reason-detail').value.trim();
    if (details.length < 20) {
        document.getElementById('err-detail').style.display = 'block';
        ok = false;
    } else {
        document.getElementById('err-detail').style.display = 'none';
    }

    const name = document.getElementById('c-name').value.trim();
    if (!name) {
        document.getElementById('err-name').style.display = 'block';
        ok = false;
    } else {
        document.getElementById('err-name').style.display = 'none';
    }

    const email = document.getElementById('c-email').value.trim();
    if (!email || !email.includes('@')) {
        document.getElementById('err-email').style.display = 'block';
        ok = false;
    } else {
        document.getElementById('err-email').style.display = 'none';
    }

    const phone = document.getElementById('c-phone').value.trim();
    if (!phone || !/^01[0-9]{9}$/.test(phone)) {
        document.getElementById('err-phone').style.display = 'block';
        ok = false;
    } else {
        document.getElementById('err-phone').style.display = 'none';
    }

    if (!document.getElementById('agree-terms').checked) {
        document.getElementById('err-terms').style.display = 'block';
        ok = false;
    } else {
        document.getElementById('err-terms').style.display = 'none';
    }

    return ok;
}

// ─── Submit ───────────────────────────────────────────────────────────────────
function submitRequest() {
    if (!validateAll()) return;
    document.getElementById('exchange-form').submit();
}

// ─── Reset ────────────────────────────────────────────────────────────────────
function resetForm() {
    selectedOrderId = selectedProductId = selectedType = selectedReason = '';

    document.querySelectorAll('.order-sel-card').forEach(c => c.classList.remove('selected'));
    document.querySelectorAll('.osc-radio-dot').forEach(d => d.classList.remove('active'));
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('selected'));
    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('active'));

    document.getElementById('h-order-id').value      = '';
    document.getElementById('h-product-id').value    = '';
    document.getElementById('h-request-type').value  = '';
    document.getElementById('h-reason').value        = '';
    document.getElementById('h-contact-method').value = 'whatsapp';

    document.getElementById('reason-detail').value = '';
    document.getElementById('char-count').textContent = '0 / 1000 characters';
    document.getElementById('exchange-options').style.display = 'none';

    document.getElementById('c-name').value  = '';
    document.getElementById('c-email').value = '';
    document.getElementById('c-phone').value = '';
    document.getElementById('agree-terms').checked = false;

    document.querySelectorAll('.error-msg').forEach(e => e.style.display = 'none');

    selectContact('whatsapp');
}

// ─── Re-apply selections from old POST on page reload after error ─────────────
document.addEventListener('DOMContentLoaded', () => {
    if (selectedOrderId)   selectOrder(selectedOrderId, selectedProductId, '');
    if (selectedType)      selectType(selectedType);
    if (selectedReason) {
        const chip = Array.from(document.querySelectorAll('.reason-chip'))
            .find(c => c.textContent.trim() === selectedReason);
        if (chip) selectReason(chip, selectedReason);
    }

    const details = document.getElementById('reason-detail');
    if (details) countChars(details, 'char-count');

    const savedContact = document.getElementById('h-contact-method').value || 'whatsapp';
    selectContact(savedContact);
});
</script>

</body>
</html>