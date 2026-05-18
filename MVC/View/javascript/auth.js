(function authValidation() {
    'use strict';

    const $ = (id) => document.getElementById(id);

    function showFieldError(inputId, errId, msg) {
        const input = $(inputId);
        const err = $(errId);
        if (!input || !err) return;
        input.classList.add('input-error');
        input.classList.remove('input-ok');
        err.textContent = msg;
        err.style.display = 'block';
        err.style.color = 'red';
    }

    function showFieldOk(inputId, errId) {
        const input = $(inputId);
        const err = $(errId);
        if (!input) return;
        input.classList.remove('input-error');
        input.classList.add('input-ok');
        if (err) {
            err.textContent = '';
            err.style.display = 'none';
        }
    }

    function clearFieldState(inputId, errId) {
        const input = $(inputId);
        const err = $(errId);
        if (!input) return;
        input.classList.remove('input-error', 'input-ok');
        if (err) {
            err.textContent = '';
            err.style.display = 'none';
        }
    }

    function initLogin() {
        const form = $('login-form');
        if (!form) return;

        const INVALID_MSG = 'Invalid email or password';
        const emailEl = $('log-email');
        const passEl = $('log-pass');
        const errEl = $('log-err');

        function showLoginError(msg) {
            if (errEl) {
                errEl.textContent = msg || INVALID_MSG;
                errEl.style.display = 'block';
                errEl.style.color = 'red';
            }
            emailEl?.classList.add('input-error');
            passEl?.classList.add('input-error');
        }

        function clearLoginError() {
            if (errEl && !errEl.textContent) {
                errEl.style.display = 'none';
            }
            emailEl?.classList.remove('input-error');
            passEl?.classList.remove('input-error');
        }

        emailEl?.addEventListener('input', () => {
            if (errEl) {
                errEl.textContent = '';
                errEl.style.display = 'none';
            }
            clearLoginError();
        });
        passEl?.addEventListener('input', () => {
            if (errEl) {
                errEl.textContent = '';
                errEl.style.display = 'none';
            }
            clearLoginError();
        });

        form.addEventListener('submit', (e) => {
            const email = emailEl?.value.trim() ?? '';
            const password = passEl?.value ?? '';
            if (!email || !password.trim()) {
                e.preventDefault();
                showLoginError(INVALID_MSG);
                return;
            }
        });
    }

    const NAME_LETTERS_ONLY = /^[A-Za-z\u0600-\u06FF]+(?:\s+[A-Za-z\u0600-\u06FF]+)*$/;
    const HAS_NUMBER_OR_SYMBOL = /[0-9]|[^A-Za-z\u0600-\u06FF\s]/;
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const PHONE_REGEX = /^01[0125][0-9]{8}$/;

    const MSG = {
        nameLettersOnly: 'Name must contain letters only',
        nameNoSymbols: 'Numbers and symbols are not allowed',
        emailInvalid: 'Invalid email format',
        emailTaken: 'This email is already registered',
        phoneInvalid: 'Invalid Egyptian phone number',
        passwordShort: 'Password must be at least 8 characters',
    };

    let emailCheckTimer = null;

    const showError = showFieldError;
    const showOk = showFieldOk;
    const clearState = clearFieldState;

    function validateName(inputId, errId) {
        const val = $(inputId)?.value.trim() ?? '';
        if (!val) {
            showError(inputId, errId, MSG.nameLettersOnly);
            return false;
        }
        if (HAS_NUMBER_OR_SYMBOL.test(val)) {
            showError(inputId, errId, MSG.nameNoSymbols);
            return false;
        }
        if (!NAME_LETTERS_ONLY.test(val)) {
            showError(inputId, errId, MSG.nameLettersOnly);
            return false;
        }
        showOk(inputId, errId);
        return true;
    }

    async function checkEmailExists(email) {
        const res = await fetch(
            `/Tretto.eg--System/MVC/Controller/AuthController.php?action=checkEmail&email=${encodeURIComponent(email)}`
        );
        return res.json();
    }

    async function validateEmail(inputId, errId) {
        const val = $(inputId)?.value.trim() ?? '';
        if (!val || !EMAIL_REGEX.test(val)) {
            showError(inputId, errId, MSG.emailInvalid);
            return false;
        }

        try {
            const data = await checkEmailExists(val);
            if (!data.valid) {
                showError(inputId, errId, MSG.emailInvalid);
                return false;
            }
            if (data.exists) {
                showError(inputId, errId, MSG.emailTaken);
                return false;
            }
        } catch (e) {
            console.warn('Email check failed:', e);
        }

        showOk(inputId, errId);
        return true;
    }

    function validatePhone(inputId, errId) {
        const digits = ($(inputId)?.value ?? '').replace(/\D/g, '');
        if ($(inputId) && digits !== $(inputId).value) {
            $(inputId).value = digits;
        }
        if (!PHONE_REGEX.test(digits)) {
            showError(inputId, errId, MSG.phoneInvalid);
            return false;
        }
        showOk(inputId, errId);
        return true;
    }

    function validatePassword(inputId, errId) {
        const val = $(inputId)?.value ?? '';
        if (val.length < 8) {
            showError(inputId, errId, MSG.passwordShort);
            return false;
        }
        showOk(inputId, errId);
        return true;
    }

    function scheduleLiveValidation(inputId, errId, validateFn) {
        const el = $(inputId);
        if (!el) return;

        el.addEventListener('input', () => {
            const val = el.value.trim();
            if (!val && inputId !== 'reg-pass') {
                clearState(inputId, errId);
                return;
            }
            if (inputId === 'reg-email') {
                clearTimeout(emailCheckTimer);
                emailCheckTimer = setTimeout(() => validateFn(), 400);
                return;
            }
            validateFn();
        });

        el.addEventListener('blur', () => validateFn());
    }

    function attachSubmit() {
        const form = $('register-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const results = await Promise.all([
                Promise.resolve(validateName('reg-fname', 'reg-fname-err')),
                Promise.resolve(validateName('reg-lname', 'reg-lname-err')),
                validateEmail('reg-email', 'reg-email-err'),
                Promise.resolve(validatePhone('reg-phone', 'reg-phone-err')),
                Promise.resolve(validatePassword('reg-pass', 'reg-pass-err')),
            ]);

            if (results.includes(false)) {
                const first = form.querySelector('.input-error');
                if (first) {
                    first.focus();
                    first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }

            form.submit();
        });
    }

    function initRegister() {
        if (!$('register-form')) return;

        scheduleLiveValidation('reg-fname', 'reg-fname-err', () => validateName('reg-fname', 'reg-fname-err'));
        scheduleLiveValidation('reg-lname', 'reg-lname-err', () => validateName('reg-lname', 'reg-lname-err'));
        scheduleLiveValidation('reg-email', 'reg-email-err', () => validateEmail('reg-email', 'reg-email-err'));
        scheduleLiveValidation('reg-phone', 'reg-phone-err', () => validatePhone('reg-phone', 'reg-phone-err'));
        scheduleLiveValidation('reg-pass', 'reg-pass-err', () => validatePassword('reg-pass', 'reg-pass-err'));
        attachSubmit();
    }

    function init() {
        initLogin();
        initRegister();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
