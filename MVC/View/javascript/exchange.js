/**
 * Exchange / Refund page — v2 (PHP-rendered orders, no renderOrders mock data)
 */
(function () {
    'use strict';

    const cfgEl = document.getElementById('exchange-config');
    const cfg = cfgEl ? JSON.parse(cfgEl.textContent || '{}') : {};

    let selectedOrderId = cfg.orderId || '';
    let selectedProductId = cfg.productId || '';
    let selectedType = cfg.requestType || '';
    let selectedReason = cfg.reason || '';
    const variantsByProduct = cfg.variantsByProduct || {};

    function getVariantsForProduct(productId) {
        const key = String(productId);
        return variantsByProduct[key] || { sizes: [], colors: [] };
    }

    function updateVariantOptions(productId, keepValues) {
        const productKey = String(productId);
        const data = getVariantsForProduct(productKey);
        const sizeSelect = document.getElementById('ex-size');
        const colorSelect = document.getElementById('ex-color');
        const itemSelect = document.getElementById('ex-item');

        const savedSize = keepValues
            ? (sizeSelect?.value || cfg.preferredSize || '')
            : '';
        const savedColor = keepValues
            ? (colorSelect?.value || cfg.preferredColor || '')
            : '';
        const savedItem = keepValues
            ? (itemSelect?.value || cfg.newProductId || '')
            : '';

        if (sizeSelect) {
            sizeSelect.innerHTML = '<option value="">Same size</option>';
            (data.sizes || []).forEach((size) => {
                const opt = document.createElement('option');
                opt.value = size;
                opt.textContent = size;
                if (savedSize === size) opt.selected = true;
                sizeSelect.appendChild(opt);
            });
        }

        if (colorSelect) {
            colorSelect.innerHTML = '<option value="">Same colour</option>';
            (data.colors || []).forEach((c) => {
                const opt = document.createElement('option');
                opt.value = c.value;
                opt.textContent = c.label || c.value;
                if (savedColor === c.value) opt.selected = true;
                colorSelect.appendChild(opt);
            });
        }

        if (itemSelect) {
            Array.from(itemSelect.options).forEach((opt) => {
                if (!opt.value) {
                    opt.hidden = false;
                    return;
                }
                opt.hidden = opt.value === productKey;
            });
            if (savedItem && savedItem !== productKey) {
                itemSelect.value = savedItem;
            } else if (itemSelect.value === productKey) {
                itemSelect.value = '';
            }
        }
    }

    function showError(id, visible) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('show', visible);
    }

    function selectOrder(orderId, productId) {
        selectedOrderId = String(orderId);
        selectedProductId = String(productId);

        document.querySelectorAll('.order-sel-card').forEach((c) => c.classList.remove('selected'));

        const card = document.getElementById('osc-' + orderId + '-' + productId);
        if (card) {
            card.classList.add('selected');
        }

        const hOrder = document.getElementById('h-order-id');
        const hProduct = document.getElementById('h-product-id');
        if (hOrder) hOrder.value = selectedOrderId;
        if (hProduct) hProduct.value = selectedProductId;

        showError('err-item', false);
        unlockFormCards();
        updateVariantOptions(selectedProductId, true);
    }

    function unlockFormCards() {
        document.querySelectorAll('.form-card.card-disabled').forEach((c) => {
            c.classList.remove('card-disabled');
        });
        const btn = document.getElementById('submit-btn');
        if (btn) btn.disabled = false;
    }

    function selectType(type) {
        selectedType = type;

        const hType = document.getElementById('h-request-type');
        if (hType) hType.value = type;

        const refundBtn = document.getElementById('type-refund');
        const exchangeBtn = document.getElementById('type-exchange');
        if (refundBtn) refundBtn.classList.toggle('active', type === 'refund');
        if (exchangeBtn) exchangeBtn.classList.toggle('active', type === 'exchange');

        const checkRefund = document.getElementById('check-refund');
        const checkExchange = document.getElementById('check-exchange');
        if (checkRefund) checkRefund.textContent = type === 'refund' ? '✓' : '';
        if (checkExchange) checkExchange.textContent = type === 'exchange' ? '✓' : '';

        const exOpts = document.getElementById('exchange-options');
        if (exOpts) exOpts.style.display = type === 'exchange' ? 'block' : 'none';

        showError('err-type', false);
    }

    function selectReason(el, reason) {
        selectedReason = reason;

        const hReason = document.getElementById('h-reason');
        if (hReason) hReason.value = reason;

        document.querySelectorAll('.reason-chip').forEach((c) => c.classList.remove('selected'));
        if (el) el.classList.add('selected');

        showError('err-reason', false);
    }

    function selectContact(val) {
        const hContact = document.getElementById('h-contact-method');
        if (hContact) hContact.value = val;

        document.querySelectorAll('.contact-pref').forEach((l) => {
            l.classList.remove('selected');
            const radio = l.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = radio.value === val;
                if (radio.checked) l.classList.add('selected');
            }
        });
    }

    function countChars(el, counterId) {
        const counter = document.getElementById(counterId);
        if (counter && el) {
            counter.textContent = el.value.length + ' / 1000 characters';
        }
    }

    function validateAll() {
        let ok = true;

        if (!selectedOrderId) {
            showError('err-item', true);
            ok = false;
        } else {
            showError('err-item', false);
        }

        if (!selectedType) {
            showError('err-type', true);
            ok = false;
        } else {
            showError('err-type', false);
        }

        if (!selectedReason) {
            showError('err-reason', true);
            ok = false;
        } else {
            showError('err-reason', false);
        }

        const detailsEl = document.getElementById('reason-detail');
        const details = detailsEl ? detailsEl.value.trim() : '';
        showError('err-detail', details.length < 20);

        const nameEl = document.getElementById('c-name');
        const name = nameEl ? nameEl.value.trim() : '';
        showError('err-name', !name);

        const emailEl = document.getElementById('c-email');
        const email = emailEl ? emailEl.value.trim() : '';
        showError('err-email', !email || !email.includes('@'));

        const phoneEl = document.getElementById('c-phone');
        const phone = phoneEl ? phoneEl.value.trim() : '';
        showError('err-phone', !phone || !/^01[0-9]{9}$/.test(phone));

        const terms = document.getElementById('agree-terms');
        showError('err-terms', !(terms && terms.checked));

        return ok;
    }

    function submitRequest() {
        if (!validateAll()) {
            const firstErr = document.querySelector('.error-msg.show');
            if (firstErr) {
                firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        const form = document.getElementById('exchange-form');
        if (form) form.submit();
    }

    function resetForm() {
        selectedOrderId = '';
        selectedProductId = '';
        selectedType = '';
        selectedReason = '';

        document.querySelectorAll('.order-sel-card').forEach((c) => c.classList.remove('selected'));
        document.querySelectorAll('.type-btn').forEach((b) => b.classList.remove('active'));
        document.querySelectorAll('.reason-chip').forEach((c) => c.classList.remove('selected'));

        const checkRefund = document.getElementById('check-refund');
        const checkExchange = document.getElementById('check-exchange');
        if (checkRefund) checkRefund.textContent = '';
        if (checkExchange) checkExchange.textContent = '';

        const fields = {
            'h-order-id': '',
            'h-product-id': '',
            'h-request-type': '',
            'h-reason': '',
            'h-contact-method': 'whatsapp',
        };
        Object.keys(fields).forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.value = fields[id];
        });

        const detail = document.getElementById('reason-detail');
        if (detail) detail.value = '';
        const charCount = document.getElementById('char-count');
        if (charCount) charCount.textContent = '0 / 1000 characters';

        const exOpts = document.getElementById('exchange-options');
        if (exOpts) exOpts.style.display = 'none';

        ['c-name', 'c-email', 'c-phone'].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const terms = document.getElementById('agree-terms');
        if (terms) terms.checked = false;

        document.querySelectorAll('.error-msg').forEach((e) => e.classList.remove('show'));

        selectContact('whatsapp');
        updateVariantOptions('', false);
    }

    function bindFormGuards() {
        const form = document.getElementById('exchange-form');
        if (!form) return;

        // Enter داخل input يرسل الفورم ويعمل refresh — نمنعه (ما عدا textarea)
        form.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            if (e.target.tagName === 'TEXTAREA') return;
            e.preventDefault();
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            submitRequest();
        });
    }

    function bindEvents() {
        const ordersContainer = document.getElementById('order-cards-container');
        if (ordersContainer) {
            ordersContainer.addEventListener('click', (e) => {
                const card = e.target.closest('.order-sel-card');
                if (!card || !card.dataset.orderId) return;
                selectOrder(card.dataset.orderId, card.dataset.productId);
            });
        }

        const typeRefund = document.getElementById('type-refund');
        const typeExchange = document.getElementById('type-exchange');
        if (typeRefund) typeRefund.addEventListener('click', () => selectType('refund'));
        if (typeExchange) typeExchange.addEventListener('click', () => selectType('exchange'));

        document.querySelectorAll('.reason-chip').forEach((chip) => {
            chip.addEventListener('click', () => {
                const reason = chip.dataset.reason || chip.textContent.trim();
                selectReason(chip, reason);
            });
        });

        document.querySelectorAll('.contact-pref').forEach((pref) => {
            pref.addEventListener('click', () => {
                const radio = pref.querySelector('input[type="radio"]');
                if (radio) selectContact(radio.value);
            });
        });

        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                submitRequest();
            });
        }

        const resetBtn = document.getElementById('reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', resetForm);
        }

        const detail = document.getElementById('reason-detail');
        if (detail) {
            detail.addEventListener('input', () => countChars(detail, 'char-count'));
        }
    }

    function restoreFromConfig() {
        if (selectedOrderId && selectedProductId) {
            selectOrder(selectedOrderId, selectedProductId);
            updateVariantOptions(selectedProductId, true);
        }
        if (selectedType) {
            selectType(selectedType);
        }
        if (selectedReason) {
            const chip = Array.from(document.querySelectorAll('.reason-chip')).find(
                (c) => (c.dataset.reason || c.textContent.trim()) === selectedReason
            );
            if (chip) selectReason(chip, selectedReason);
        }

        const detail = document.getElementById('reason-detail');
        if (detail) countChars(detail, 'char-count');

        selectContact(cfg.contactMethod || 'whatsapp');
    }

    window.selectOrder = selectOrder;
    window.selectType = selectType;
    window.selectReason = selectReason;
    window.selectContact = selectContact;
    window.countChars = countChars;
    window.validateAll = validateAll;
    window.submitRequest = submitRequest;
    window.resetForm = resetForm;

    document.addEventListener('DOMContentLoaded', () => {
        bindFormGuards();
        bindEvents();
        restoreFromConfig();
    });
})();
