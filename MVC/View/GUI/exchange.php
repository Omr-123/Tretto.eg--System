<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/exchange.css">  
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<body>
    <div id="cur"></div>
    <div id="cur-r"></div>
    <div id="toast"><span class="t-ico">✨</span><span id="tmsg"></span></div>

    <!-- NAV -->
    <nav>
        <a class="logo">Tretto<span>.</span>eg <span class="logo-heart">♥</span></a>
        <a class="nav-back" onclick="history.back()">Back to My Orders</a>
    </nav>

    <!-- HERO HEADER -->
    <div class="page-hero">
        <div class="hero-inner">
            <div class="hero-tag"><span class="hero-tag-dot"></span> Return Policy — 14 Days</div>
            <h1 class="hero-title">Request a <em>Refund</em><br>or Exchange 🔄</h1>
            <p class="hero-sub">Not happy with your order? We're here to help. Submit your request below and our team
                will get back to you within 24 hours. 💕</p>
        </div>
    </div>

    <!-- POLICY STRIP -->
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

    <!-- FORM or SUCCESS -->
    <div id="form-view">
        <div class="main-wrap">

            <!-- LEFT: FORM -->
            <div>
                <!-- Steps -->
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

                <!-- STEP 1: SELECT ITEM -->
                <div class="form-card" id="card-item">
                    <div class="form-card-title">🛍 Select the Item</div>
                    <div class="form-card-sub">Choose the delivered order you'd like to return or exchange</div>
                    <div class="order-cards" >
                        
                        <div class="order-sel-card" id="osc-${o.id}" onclick="selectOrder('${o.id}')">
                            <div class="osc-radio"><div class="osc-radio-dot"></div></div>
                            <img class="osc-img" src="${o.img}" alt="${o.product}">
                            <div class="osc-info">
                                <div class="osc-name">${o.product}</div>
                                <div class="osc-details">${o.variant}</div>
                                <div class="osc-order-id">Order #${o.id} · Delivered ${o.date}</div>
                            </div>
                            <div class="osc-price">${o.price}</div>
                            <span class="osc-status delivered">Delivered</span>
                            </div>
                    <!-- Rendered by JS -->
                </div>
                    <div class="error-msg" id="err-item">Please select an order item to continue.</div>
                </div>

                <!-- STEP 2: REQUEST TYPE -->
                <div class="form-card" id="card-type">
                    <div class="form-card-title">🔄 Request Type</div>
                    <div class="form-card-sub">What would you like to do with this item?</div>
                    <div class="type-toggle">
                        <div class="type-btn" id="type-refund" onclick="selectType('refund')">
                            <div class="type-check" id="check-refund"></div>
                            <div class="type-btn-ico">💸</div>
                            <div class="type-btn-name">Full Refund</div>
                            <div class="type-btn-desc">Get your money back to your original payment method</div>
                        </div>
                        <div class="type-btn" id="type-exchange" onclick="selectType('exchange')">
                            <div class="type-check" id="check-exchange"></div>
                            <div class="type-btn-ico">🔁</div>
                            <div class="type-btn-name">Exchange</div>
                            <div class="type-btn-desc">Swap for a different size, colour, or another item</div>
                        </div>
                    </div>
                    <div class="error-msg" id="err-type">Please select a request type.</div>
                </div>

                <!-- STEP 3: REASON -->
                <div class="form-card" id="card-reason">
                    <div class="form-card-title">📋 Reason for Request</div>
                    <div class="form-card-sub">Help us understand what went wrong so we can improve 💕</div>
                    <div class="form-group">
                        <label class="form-label">Quick Select <span class="req">*</span></label>
                        <div class="reason-chips" id="reason-chips">
                            <div class="reason-chip" onclick="selectReason(this,'Wrong size received')">Wrong size
                                received</div>
                            <div class="reason-chip" onclick="selectReason(this,'Wrong item delivered')">Wrong item
                                delivered</div>
                            <div class="reason-chip" onclick="selectReason(this,'Item arrived damaged')">Item arrived
                                damaged</div>
                            <div class="reason-chip" onclick="selectReason(this,'Item differs from photos')">Differs
                                from photos</div>
                            <div class="reason-chip" onclick="selectReason(this,'Quality not as expected')">Quality not
                                as expected</div>
                            <div class="reason-chip" onclick="selectReason(this,'Changed my mind')">Changed my mind
                            </div>
                            <div class="reason-chip" onclick="selectReason(this,'Received duplicate item')">Duplicate
                                item</div>
                            <div class="reason-chip" onclick="selectReason(this,'Other')">Other</div>
                        </div>
                        <div class="error-msg" id="err-reason">Please select a reason.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Additional Details <span class="req">*</span></label>
                        <textarea class="form-input" id="reason-detail"
                            placeholder="Please describe the issue in more detail… (e.g. I ordered size 38 but received size 40, the item is still in its original packaging)"
                            oninput="countChars(this,'char-count')"></textarea>
                        <div class="char-count" id="char-count">0 / 500 characters</div>
                        <div class="error-msg" id="err-detail">Please add more details (min. 20 characters).</div>
                    </div>

                    <!-- EXCHANGE-ONLY options -->
                    <div class="exchange-options" id="exchange-options">
                        <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
                        <div class="form-card-title" style="font-size:15px;margin-bottom:5px">🔁 Exchange Preferences
                        </div>
                        <div class="form-card-sub">What would you like instead?</div>
                        <div class="ex-row">
                            <div class="form-group">
                                <label class="form-label">Preferred New Size</label>
                                <select class="form-input" id="ex-size" style="cursor:none">
                                    <option value="">Same size</option>
                                    <option>36</option>
                                    <option>37</option>
                                    <option>38</option>
                                    <option>39</option>
                                    <option>40</option>
                                    <option>41</option>
                                    <option>42</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Preferred New Colour</label>
                                <select class="form-input" id="ex-color" style="cursor:none">
                                    <option value="">Same colour</option>
                                    <option>Denim Blue</option>
                                    <option>Black</option>
                                    <option>Taupe</option>
                                    <option>Beige</option>
                                    <option>Terracotta</option>
                                    <option>Sand</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Exchange for a Different Item?</label>
                            <select class="form-input" id="ex-item" style="cursor:none">
                                <option value="">No — same item, different variant</option>
                                <option>Denim Slide Sandal (850 EGP)</option>
                                <option>Classic Leather Tote (1,530 EGP)</option>
                                <option>Suede Mule Clog (1,100 EGP)</option>
                                <option>Denim Buckle Slide (920 EGP)</option>
                                <option>Mule Clog Taupe (980 EGP)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Photo upload -->
                   

                </div>

                <!-- STEP 4: CONTACT & CONFIRM -->
                <div class="form-card" id="card-confirm">
                    <div class="form-card-title">📬 Contact & Confirmation</div>
                    <div class="form-card-sub">How should we reach you about this request?</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Your Name <span class="req">*</span></label>
                            <input class="form-input" id="c-name" placeholder="Sara Ahmed">
                            <div class="error-msg" id="err-name">Name is required.</div>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Email Address <span class="req">*</span></label>
                            <input class="form-input" id="c-email" type="email" placeholder="your@email.com">
                            <div class="error-msg" id="err-email">Valid email is required.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="req">*</span></label>
                        <input class="form-input" id="c-phone" type="tel" placeholder="01xxxxxxxxx">
                        <div class="error-msg" id="err-phone">Valid Egyptian phone number required.</div>
                    </div>
                   
                    <div class="form-group">
    <label class="form-label">Preferred Contact Method</label>

    <div class="contact-prefs">

        <!-- WhatsApp -->
        <label class="contact-pref">
            <input type="radio" name="contact_method" value="whatsapp" checked hidden>

            <div class="cp-ico">📱</div>
            <div class="cp-name">WhatsApp</div>
        </label>

        <!-- Email -->
        <label class="contact-pref">
            <input type="radio" name="contact_method" value="email" hidden>

            <div class="cp-ico">✉️</div>
            <div class="cp-name">Email</div>
        </label>

        <!-- Phone -->
        <label class="contact-pref">
            <input type="radio" name="contact_method" value="phone" hidden>

            <div class="cp-ico">📞</div>
            <div class="cp-name">Phone Call</div>
        </label>

    </div>
</div>
                    <!-- Terms -->
                    <div
                        style="padding:14px;background:var(--blush);border-radius:12px;border:1px solid var(--border);font-size:12px;color:var(--mid);line-height:1.7;margin-bottom:18px">
                        By submitting this request you confirm that:
                        <ul style="margin-top:7px;margin-left:16px;display:flex;flex-direction:column;gap:4px">
                            <li>The item is in its original condition and has not been worn or washed</li>
                            <li>You are within the 14-day return window from your delivery date</li>
                            <li>You agree to Tretto.eg's <a style="color:var(--rose);cursor:none;font-weight:600">Return
                                    & Exchange Policy</a></li>
                        </ul>
                    </div>
                    <label style="display:flex;align-items:start;gap:10px;cursor:none;margin-bottom:18px">
                        <input type="checkbox" id="agree-terms" style="margin-top:3px;accent-color:var(--rose)">
                        <span style="font-size:12px;color:var(--mid);font-weight:500">I confirm that all the information
                            provided is accurate and I agree to the return policy. 🌸</span>
                    </label>
                    <div class="error-msg" id="err-terms">You must agree to the terms to continue.</div>
                    <div class="submit-area">
                        <button class="btn-submit" onclick="submitRequest()" id="submit-btn">
                            <span>Submit Request</span> <span>💕</span>
                        </button>
                        <button class="btn-secondary" onclick="resetForm()">Clear & Start Over</button>
                    </div>
                    <div class="submit-note">🔒 Your request is secure. Reference number sent via email & SMS.<br>Our
                        team responds within <strong>24 hours</strong> on business days.</div>
                </div>
            </div>

            <!-- RIGHT: SIDEBAR -->
            <div class="sidebar">
                <div class="sidebar-card">
                    <div class="sc-title">📋 Return Policy</div>
                    <div class="policy-list">
                        <div class="policy-list-item"><span class="pli-ico">📅</span>
                            <div class="pli-txt"><strong>14-Day Window</strong>You have 14 days from your delivery date
                                to submit a request.</div>
                        </div>
                        <div class="policy-list-item"><span class="pli-ico">📦</span>
                            <div class="pli-txt"><strong>Original Condition</strong>Items must be unworn, unwashed, and
                                in original packaging with tags.</div>
                        </div>
                        <div class="policy-list-item"><span class="pli-ico">💸</span>
                            <div class="pli-txt"><strong>Refund Method</strong>Refunds are processed to your original
                                payment method within 5–7 days.</div>
                        </div>
                        <div class="policy-list-item"><span class="pli-ico">🔁</span>
                            <div class="pli-txt"><strong>Exchanges</strong>Free exchange for different size or colour.
                                Price difference applies for different items.</div>
                        </div>
                        <div class="policy-list-item"><span class="pli-ico">🚫</span>
                            <div class="pli-txt"><strong>Non-returnable</strong>Items marked as final sale or
                                worn/damaged by the customer cannot be returned.</div>
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
                                <div class="stl-desc">You're notified via your preferred contact method with the
                                    decision</div>
                            </div>
                        </div>
                        <div class="stl-item">
                            <div class="stl-dot pending">4</div>
                            <div class="stl-info">
                                <div class="stl-name">Pickup Arranged</div>
                                <div class="stl-desc">If approved, we arrange free pickup of the item from your address
                                </div>
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
                    <div class="csc-sub">Can't find what you're looking for? Our support team is ready to help you.
                    </div>
                    <div class="csc-links">
                        <a class="csc-link">📱 WhatsApp: 010 1234 5678</a>
                        <a class="csc-link">📞 Hotline: 19123</a>
                        <a class="csc-link">✉️ support@tretto.eg</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SUCCESS SCREEN -->
    <div id="success-view" class="success-screen">
        <div class="success-confetti">🎀</div>
        <div class="success-anim">✓</div>
        <div
            style="font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:var(--rose);font-weight:700;margin-bottom:10px">
            Request Submitted!</div>
        <h1 class="success-title">We've got your<br><em>request</em>! 🌸</h1>
        <p class="success-sub">Your refund/exchange request has been submitted successfully. Our team will review it and
            get back to you within 24 hours. 💕</p>
        <div class="success-ref" id="success-ref">REF-20251116-XXXX</div>
        <div class="success-details">
            <div class="sd-row"><span class="sd-lbl">Request Type</span><span class="sd-val" id="sd-type">—</span></div>
            <div class="sd-row"><span class="sd-lbl">Item</span><span class="sd-val" id="sd-item">—</span></div>
            <div class="sd-row"><span class="sd-lbl">Reason</span><span class="sd-val" id="sd-reason">—</span></div>
            <div class="sd-row"><span class="sd-lbl">Order ID</span><span class="sd-val" id="sd-order">—</span></div>
            <div class="sd-row"><span class="sd-lbl">Submitted</span><span class="sd-val" id="sd-date">—</span></div>
            <div class="sd-row"><span class="sd-lbl">Contact Method</span><span class="sd-val" id="sd-contact">—</span>
            </div>
            <div class="sd-row"><span class="sd-lbl">Expected Response</span><span class="sd-val"
                    style="color:var(--rose-d)">Within 24 hours</span></div>
        </div>
        <div
            style="background:rgba(232,103,138,.07);border:1.5px solid rgba(232,103,138,.2);border-radius:14px;padding:16px;max-width:460px;margin:0 auto 28px;font-size:12px;color:var(--mid);line-height:1.65">
            📧 A confirmation has been sent to your email.<br>
            📱 Keep your reference number handy — you'll need it when our team contacts you.
        </div>
        <div class="success-acts">
            <button class="btn-rose" onclick="location.reload()">Submit Another Request</button>
            <button class="btn-outline-rose" onclick="showToast('Redirecting to your orders... 📦')">View My
                Orders</button>
        </div>
    </div>

    <script src="../javascript/exchange.js"></script>
</body>
</html>