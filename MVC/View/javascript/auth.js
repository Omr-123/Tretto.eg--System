(function doRegister() {
    'use strict';
    const $ = (id) => document.getElementById(id);
    const NAME_REGEX = /^[A-Za-zÀ-ÖØ-öø-ÿ\u0600-\u06FF][A-Za-zÀ-ÖØ-öø-ÿ\u0600-\u06FF\s\-.']{0,49}$/;
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i;
    const PHONE_REGEX = /^01[0125][0-9]{8}$/;
    const MSG = {
        fname: {
            empty: 'First name is required.',
            invalid: 'First name must contain letters only — no numbers or symbols.',
        },
        lname: {
            empty: 'Last name is required.',
            invalid: 'Last name must contain letters only — no numbers or symbols.',
        },
        email: {
            empty: 'Email address is required.',
            invalid: 'Invalid email format. Example: name@email.com',
            taken: 'This email is already registered.',
        },
        phone: {
            invalid: 'Invalid Egyptian phone number. Must start with 010, 011, 012, or 015 and be exactly 11 digits.',
        },
        pass: {
            empty: 'Password is required.',
            short: 'Password must be at least 8 characters.',
        },
    };
    function showError(inputId, errId, msg) {
        const input = $(inputId);
        const err = $(errId);
        if (!input || !err) return;
        input.classList.add('input-error');
        input.classList.remove('input-ok');
        err.textContent = msg;
        err.style.display = 'block';
    }

    function showOk(inputId, errId) {
        const input = $(inputId);
        const err = $(errId);
        if (!input) return;
        input.classList.remove('input-error');
        input.classList.add('input-ok');
        if (err) { err.textContent = ''; err.style.display = 'none'; }
    }

    function clearState(inputId, errId) {
        const input = $(inputId);
        const err = $(errId);
        if (!input) return;
        input.classList.remove('input-error', 'input-ok');
        if (err) { err.textContent = ''; err.style.display = 'none'; }
    }
    function ensureErrEl(inputId, errId) {
        if ($(errId)) return;
        const input = $(inputId);
        if (!input) return;
        const div = document.createElement('div');
        div.id = errId;
        div.className = 'error-msg';
        div.style.display = 'none';
        input.parentNode.insertBefore(div, input.nextSibling);
    }
    function validateName(inputId, errId, msgs) {
        const val = $(inputId)?.value.trim() ?? '';
        if (!val) { showError(inputId, errId, msgs.empty); return false; }
        if (!NAME_REGEX.test(val)) { showError(inputId, errId, msgs.invalid); return false; }
        showOk(inputId, errId);
        return true;
    }
    async function validateEmail(inputId, errId) {
        const val = $(inputId)?.value.trim() ?? '';
        if (!val) { showError(inputId, errId, MSG.email.empty); return false; }
        if (!EMAIL_REGEX.test(val)) { showError(inputId, errId, MSG.email.invalid); return false; }
        try {
            const res = await fetch(
                `/Tretto.eg--System/MVC/Controller/AuthController.php?action=checkEmail&email=${encodeURIComponent(val)}`
            );
            const data = await res.json();
            if (data.exists) { showError(inputId, errId, MSG.email.taken); return false; }
        } catch (e) {
            console.warn('Email uniqueness check failed:', e);
        }

        showOk(inputId, errId);
        return true;
    }
    function validatePhone(inputId, errId) {
        const raw = $(inputId)?.value.trim() ?? '';
        if (!raw) { clearState(inputId, errId); return true; }           // optional
        const digits = raw.replace(/\D/g, '');                           // strip spaces/dashes
        if (!PHONE_REGEX.test(digits)) { showError(inputId, errId, MSG.phone.invalid); return false; }
        showOk(inputId, errId);
        return true;
    }

    function validatePassword(inputId, errId) {
        const val = $(inputId)?.value ?? '';
        if (!val) { showError(inputId, errId, MSG.pass.empty); return false; }
        if (val.length < 8) { showError(inputId, errId, MSG.pass.short); return false; }
        showOk(inputId, errId);
        return true;
    }
    function attachStrengthMeter() {
        const passEl = $('reg-pass');
        const barWrap = $('reg-pass-strength');
        if (!passEl || !barWrap) return;

        const LABELS = ['', 'Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'];
        const COLORS = ['', '#e24b4a', '#ef9f27', '#ba7517', '#1d9e75', '#0f6e56'];

        passEl.addEventListener('input', () => {
            const v = passEl.value;
            barWrap.innerHTML = '';
            if (!v) return;

            let score = 0;
            if (v.length >= 8) score++;
            if (v.length >= 12) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const color = COLORS[score] || COLORS[1];

            barWrap.innerHTML = `
        <div style="height:3px;border-radius:2px;background:#e0e0e0;margin-top:6px;overflow:hidden">
          <div style="height:100%;border-radius:2px;background:${color};width:${Math.max(10, (score / 5) * 100)}%;transition:width .3s,background .3s"></div>
        </div>
        <span style="font-size:11px;color:${color};margin-top:3px;display:block">
          Password strength: ${LABELS[score] || LABELS[1]}
        </span>`;
        });
    }
    function attachBlur() {
        const map = [
            { id: 'reg-fname', err: 'reg-fname-err', fn: () => validateName('reg-fname', 'reg-fname-err', MSG.fname) },
            { id: 'reg-lname', err: 'reg-lname-err', fn: () => validateName('reg-lname', 'reg-lname-err', MSG.lname) },
            { id: 'reg-email', err: 'reg-email-err', fn: () => validateEmail('reg-email', 'reg-email-err') },
            { id: 'reg-phone', err: 'reg-phone-err', fn: () => validatePhone('reg-phone', 'reg-phone-err') },
            { id: 'reg-pass', err: 'reg-pass-err', fn: () => validatePassword('reg-pass', 'reg-pass-err') },
        ];

        map.forEach(({ id, err, fn }) => {
            ensureErrEl(id, err);
            const el = $(id);
            if (!el) return;
            el.addEventListener('blur', fn);
           
            el.addEventListener('input', () => {
                el.classList.remove('input-ok', 'input-error');
                const errEl = $(err);
                if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }
            });
        });
    }

    function attachSubmit() {
        const form = document.querySelector('form[action*="AuthController"]');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault(); 
            const results = await Promise.all([
                validateName('reg-fname', 'reg-fname-err', MSG.fname),
                validateName('reg-lname', 'reg-lname-err', MSG.lname),
                validateEmail('reg-email', 'reg-email-err'),
                validatePhone('reg-phone', 'reg-phone-err'),
                validatePassword('reg-pass', 'reg-pass-err'),
            ]);

            if (results.includes(false)) {
                const first = form.querySelector('.input-error');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            form.submit();
        });
    }

    function injectStyles() {
        if ($('tretto-val-styles')) return;
        const s = document.createElement('style');
        s.id = 'tretto-val-styles';
        s.textContent = `
      .form-input.input-error {
        border-color: #e24b4a !important;
        background:   #fcebeb !important;
        outline: none;
      }
      .form-input.input-ok {
        border-color: #1d9e75 !important;
        background:   #e1f5ee !important;
        outline: none;
      }
      .error-msg {
        font-size: 12px;
        color: #e24b4a;
        margin-top: 4px;
        display: none;
      }
    `;
        document.head.appendChild(s);
    }

    // ── Boot ──────────────────────────────────────────────────────────────────────
    function init() {
        injectStyles();
        attachBlur();
        attachStrengthMeter();
        attachSubmit();
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', init)
        : init();

})();