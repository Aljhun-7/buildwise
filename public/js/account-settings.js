document.addEventListener('DOMContentLoaded', function () {
    initAccountModals();
    initChangePasswordValidation();
});

function initAccountModals() {
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
        }
    };

    window.printModal = function (contentId, title) {
        const content = document.getElementById(contentId);
        if (!content) return;

        const printWindow = window.open('', '_blank');
        if (!printWindow) return;

        printWindow.document.write(`
            <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body { font-family: 'DM Sans', sans-serif; padding: 24px; color: #0f172a; }
                        h1 { font-size: 20px; margin-bottom: 16px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 13px; }
                        th { text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-size: 11px; }
                    </style>
                </head>
                <body>
                    <h1>${title}</h1>
                    ${content.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
}

function initChangePasswordValidation() {
    const form = document.getElementById('changePasswordForm');
    if (!form) return;

    const currentInput = document.getElementById('current_password');
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('new_password_confirmation');
    const submitBtn = document.getElementById('updatePasswordBtn');
    const strengthWrap = document.getElementById('cpwStrength');
    const strengthFill = document.getElementById('cpwStrengthFill');
    const strengthLabel = document.getElementById('cpwStrengthLabel');
    const reqsWrap = document.getElementById('cpwReqs');
    const matchMsg = document.getElementById('cpwMatchMsg');

    const reqLength = document.getElementById('cpwReqLength');
    const reqCase = document.getElementById('cpwReqCase');
    const reqNumber = document.getElementById('cpwReqNumber');
    const reqSpecial = document.getElementById('cpwReqSpecial');

    function setReqMet(el, met) {
        el.classList.toggle('met', met);
        el.querySelector('svg').innerHTML = met
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
    }

    function evaluate() {
        const current = currentInput.value || '';
        const pw = passwordInput.value || '';
        const confirm = confirmInput.value || '';

        if (pw.length > 0) {
            strengthWrap.style.display = 'block';
            reqsWrap.style.display = 'block';
        } else {
            strengthWrap.style.display = 'none';
            reqsWrap.style.display = 'none';
            matchMsg.style.display = 'none';
        }

        const hasLen = pw.length >= 8;
        const hasCase = /[a-z]/.test(pw) && /[A-Z]/.test(pw);
        const hasNumber = /[0-9]/.test(pw);
        const hasSpecial = /[^a-zA-Z0-9]/.test(pw);

        setReqMet(reqLength, hasLen);
        setReqMet(reqCase, hasCase);
        setReqMet(reqNumber, hasNumber);
        setReqMet(reqSpecial, hasSpecial);

        const score = [hasLen, hasCase, hasNumber, hasSpecial].filter(Boolean).length;
        strengthFill.className = 'cpw-strength-fill';

        if (score >= 4) {
            strengthFill.classList.add('strong');
            strengthLabel.textContent = 'Strong Password';
        } else if (score >= 2) {
            strengthFill.classList.add('medium');
            strengthLabel.textContent = 'Medium Strength';
        } else {
            strengthFill.classList.add('weak');
            strengthLabel.textContent = 'Weak Password';
        }

        let matchOk = false;
        if (confirm.length > 0) {
            matchMsg.style.display = 'block';
            matchOk = pw === confirm;
            matchMsg.className = 'cpw-match-msg ' + (matchOk ? 'ok' : 'err');
            matchMsg.textContent = matchOk ? 'Password match!' : 'Password do not match';
        } else {
            matchMsg.style.display = 'none';
        }

        const rulesOk = hasLen && hasCase && hasNumber && hasSpecial;
        submitBtn.disabled = !(current.length > 0 && rulesOk && matchOk);
    }

    currentInput.addEventListener('input', evaluate);
    passwordInput.addEventListener('input', evaluate);
    confirmInput.addEventListener('input', evaluate);
    evaluate();
}
