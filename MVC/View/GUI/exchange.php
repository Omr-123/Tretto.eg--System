<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Controller/exchange_Controller.php';

$controller   = new ExchangeController();
$submitResult = null;
$old          = [];

// Post/Redirect/Get — يمنع إعادة إرسال الفورم عند refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->handleSubmit();
    $_SESSION['exchange_flash'] = $result;
    if (empty($result['success'])) {
        $_SESSION['exchange_old'] = $_POST;
    }
    header('Location: exchange.php');
    exit;
}

$submitResult = $_SESSION['exchange_flash'] ?? null;
$old          = $_SESSION['exchange_old'] ?? [];
unset($_SESSION['exchange_flash'], $_SESSION['exchange_old']);

// ── Load page data (orders + products) ───────────────────────────────────────
$pageData = $controller->loadPage();
$orders             = $pageData['orders'];
$products           = $pageData['products'];
$sizes              = $pageData['sizes'];
$colors             = $pageData['colors'];
$variantsByProduct  = $pageData['variants_by_product'];

$exchangeConfig = [
    'orderId' => (string) ($old['order_id'] ?? ''),
    'productId' => (string) ($old['old_product_id'] ?? ''),
    'requestType' => (string) ($old['request_type'] ?? ''),
    'reason' => (string) ($old['reason'] ?? ''),
    'contactMethod' => (string) ($old['contact_method'] ?? 'whatsapp'),
    'preferredSize' => (string) ($old['preferred_size'] ?? ''),
    'preferredColor' => (string) ($old['preferred_color'] ?? ''),
    'newProductId' => (string) ($old['new_product_id'] ?? ''),
    'variantsByProduct' => $variantsByProduct,
];
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Exchange — Tretto.eg</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <?php
    $exchangeCssVer = @filemtime(__DIR__ . '/../css/exchange.css') ?: time();
    $exchangeJsVer = @filemtime(__DIR__ . '/../javascript/exchange.js') ?: time();
    ?>
    <link rel="stylesheet" href="../css/exchange.css?v=<?= (int) $exchangeCssVer ?>">
    <script id="exchange-config" type="application/json"><?= json_encode($exchangeConfig, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>
    <script src="../javascript/exchange.js?v=<?= (int) $exchangeJsVer ?>" defer></script>
</head>
<body class="<?= ($submitResult && !empty($submitResult['success'])) ? 'exchange-submitted' : '' ?>">

<div id="cur"></div>
<div id="cur-r"></div>

<div id="toast"><span class="t-ico">✨</span><span id="tmsg"></span></div>

<nav>
    <a class="logo">Tretto<span>.</span>eg <span class="logo-heart">♥</span></a>
    <a class="nav-back" onclick="window.location.href='index.php'">Back to My Orders</a>
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

<div class="success-screen show">
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
            <form method="POST" action="exchange.php" id="exchange-form" novalidate>

                <!-- Hidden fields filled by JS when user clicks cards/chips -->
                <input type="hidden" name="order_id"       id="h-order-id"
                       value="<?= $old['order_id']       ?? '' ?>">
                <input type="hidden" name="old_product_id" id="h-product-id"
                       value="<?= $old['old_product_id'] ?? '' ?>">
                <input type="hidden" name="request_type"   id="h-request-type"
                       value="<?= $old['request_type']   ?? '' ?>">
                <input type="hidden" name="reason"         id="h-reason"
                       value="<?= $old['reason']         ?? '' ?>">
                <input type="hidden" id="h-contact-method"
                       value="<?= htmlspecialchars($old['contact_method'] ?? 'whatsapp', ENT_QUOTES, 'UTF-8') ?>">

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
                            $cardId = (int) $order['order_id'] . '-' . (int) $order['product_id'];
                            $isSelected = (string)($old['order_id'] ?? '') === (string)$order['order_id']
                                && (string)($old['old_product_id'] ?? '') === (string)$order['product_id'];
                        ?>
                        <div class="order-sel-card <?= $isSelected ? 'selected' : '' ?>"
                             id="osc-<?= $cardId ?>"
                             data-order-id="<?= (int) $order['order_id'] ?>"
                             data-product-id="<?= (int) $order['product_id'] ?>"
                             role="button"
                             tabindex="0">

                            <div class="osc-radio">
                                <div class="osc-radio-dot"></div>
                            </div>

                            <img class="osc-img"
                                 src="<?= htmlspecialchars($order['product_image'], ENT_QUOTES, 'UTF-8') ?>"
                                 alt=""
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2254%22 height=%2266%22%3E%3Crect fill=%22%23f5e6eb%22 width=%22100%25%22 height=%22100%25%22/%3E%3C/svg%3E'">

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
                        <div class="type-btn <?= ($old['request_type'] ?? '') === 'refund' ? 'active' : '' ?>"
                             id="type-refund" role="button" tabindex="0">
                            <div class="type-check" id="check-refund"></div>
                            <div class="type-btn-ico">💸</div>
                            <div class="type-btn-name">Full Refund</div>
                            <div class="type-btn-desc">Get your money back to your original payment method</div>
                        </div>
                        <div class="type-btn <?= ($old['request_type'] ?? '') === 'exchange' ? 'active' : '' ?>"
                             id="type-exchange" role="button" tabindex="0">
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
                            <div class="reason-chip <?= $isActive ? 'selected' : '' ?>"
                                 data-reason="<?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?>"
                                 role="button"
                                 tabindex="0"><?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="error-msg" id="err-reason">Please select a reason.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Details <span class="req">*</span></label>
                        <textarea class="form-input" name="details" id="reason-detail" maxlength="1000"
                            placeholder="Please describe the issue in more detail… (e.g. I ordered size 38 but received size 40)"
                             ><?= $old['details'] ?? '' ?></textarea>

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
                                    <?php foreach ($sizes as $size): ?>
                                    <option value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ($old['preferred_size'] ?? '') === $size ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Preferred New Colour</label>
                                <select class="form-input" name="preferred_color" id="ex-color">
                                    <option value="">Same colour</option>
                                    <?php foreach ($colors as $color): ?>
                                    <option value="<?= htmlspecialchars($color['value'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ($old['preferred_color'] ?? '') === $color['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($color['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Exchange for a Different Item?</label>
                            <select class="form-input" name="new_product_id" id="ex-item">
                                <option value="">No — same item, different variant</option>
                                <?php if (empty($products)): ?>
                                <option value="" disabled>No products available in stock</option>
                                <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                <option value="<?= (int) $product['product_id'] ?>"
                                    data-product-id="<?= (int) $product['product_id'] ?>"
                                    <?= (string) ($old['new_product_id'] ?? '') === (string) $product['product_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                    <?php if (!empty($product['category'])): ?>
                                        (<?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>)
                                    <?php endif; ?>
                                    — <?= number_format((float) $product['price'], 0) ?> EGP
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
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
                            <label class="contact-pref <?= $selectedContact === $val ? 'selected' : '' ?>">
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
                                <?= empty($orders) ? 'disabled' : '' ?>>
                            <span>Submit Request</span> <span>💕</span>
                        </button>
                        <button type="button" class="btn-secondary" id="reset-btn">Clear & Start Over</button>
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

</body>
</html>