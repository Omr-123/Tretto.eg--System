(function () {
    'use strict';

    let paymentMethod = document.getElementById('payment_method')?.value || 'visa';

    const pmVisa = document.getElementById('pm-visa');
    const pmCod = document.getElementById('pm-cod');
    const visaForm = document.getElementById('visa-form');
    const codForm = document.getElementById('cod-form');
    const methodInput = document.getElementById('payment_method');
    const placeBtn = document.getElementById('place-order-btn');

    function updatePlaceOrderButton() {
        if (!placeBtn) return;
        const total = placeBtn.getAttribute('data-total') || '0.00';
        placeBtn.textContent = 'Place Order — ' + total + ' EGP 🎀';
    }

    function selPayment(method) {
        paymentMethod = method;

        if (methodInput) {
            methodInput.value = method;
        }

        if (pmVisa) {
            pmVisa.classList.toggle('sel', method === 'visa');
            const check = pmVisa.querySelector('.pay-method-check');
            if (check) check.textContent = method === 'visa' ? '✓' : '';
        }

        if (pmCod) {
            pmCod.classList.toggle('sel', method === 'cod');
            const check = pmCod.querySelector('.pay-method-check');
            if (check) check.textContent = method === 'cod' ? '✓' : '';
        }

        if (visaForm) {
            visaForm.classList.toggle('hidden', method !== 'visa');
        }

        if (codForm) {
            codForm.classList.toggle('hidden', method !== 'cod');
        }
    }

    pmVisa?.addEventListener('click', function () {
        selPayment('visa');
    });

    pmCod?.addEventListener('click', function () {
        selPayment('cod');
    });

    selPayment(paymentMethod);
    updatePlaceOrderButton();
})();
