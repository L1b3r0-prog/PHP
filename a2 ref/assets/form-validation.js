// Replaces the browser's native constraint-validation popups (the little
// tooltip bubbles for required / min / max / pattern) with an inline
// .form-error box, so every warning in the app looks and behaves the same
// way instead of some being native browser UI and others being styled.
//
// Opt-in per form: add data-validate to the <form> tag. Field labels in
// messages come from data-label="..." on the field, falling back to its
// name attribute. Progressive enhancement: if this script fails to load,
// the form's original min/max/pattern/required attributes still work
// natively, so nothing is left unvalidated.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.setAttribute('novalidate', 'novalidate');

        let errorEl = form.querySelector('.form-error[data-global-error]');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'form-error';
            errorEl.setAttribute('data-global-error', '1');
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.parentNode.insertBefore(errorEl, submitBtn);
            } else {
                form.appendChild(errorEl);
            }
        }

        function showError(msg) {
            errorEl.textContent = msg;
            errorEl.classList.add('visible');
        }
        function clearError() {
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }

        function labelFor(field) {
            return field.dataset.label || field.name || 'This field';
        }

        function firstInvalidMessage() {
            const fields = form.querySelectorAll('[required], [min], [max], [pattern]');
            for (const field of fields) {
                if (field.disabled || field.type === 'hidden') continue;

                if (field.hasAttribute('required') && !field.value.trim()) {
                    return labelFor(field) + ' is required.';
                }
                if (!field.value) continue; // optional and empty -- nothing more to check

                if (field.hasAttribute('pattern')) {
                    const re = new RegExp('^(?:' + field.getAttribute('pattern') + ')$');
                    if (!re.test(field.value)) {
                        return labelFor(field) + ' is not in a valid format.';
                    }
                }
                if (field.hasAttribute('min')) {
                    const isDate = field.type === 'date';
                    const invalid = isDate ? field.value < field.min : Number(field.value) < Number(field.min);
                    if (invalid) {
                        return isDate
                            ? labelFor(field) + ' cannot be before ' + field.min + '.'
                            : labelFor(field) + ' must be at least ' + field.min + '.';
                    }
                }
                if (field.hasAttribute('max')) {
                    const isDate = field.type === 'date';
                    const invalid = isDate ? field.value > field.max : Number(field.value) > Number(field.max);
                    if (invalid) {
                        return isDate
                            ? labelFor(field) + ' cannot be after ' + field.max + '.'
                            : labelFor(field) + ' must be at most ' + field.max + '.';
                    }
                }
            }
            return '';
        }

        form.addEventListener('submit', function (e) {
            const msg = firstInvalidMessage();
            if (msg) {
                e.preventDefault();
                showError(msg);
            } else {
                clearError();
            }
        });
        form.addEventListener('input', clearError);
        form.addEventListener('change', clearError);
    });
});
