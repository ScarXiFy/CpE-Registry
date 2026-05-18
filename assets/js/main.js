/**
 * assets/js/main.js
 * Client-side JavaScript — to be implemented in Phase 9.
 *
 * Planned functionality:
 *  - ID-lookup form: live validation feedback
 *  - Registration form: field validation before submit
 *  - Admin search: filter toggle / UX helpers
 */

const gridHero = document.querySelector('[data-grid-hero]');

if (gridHero) {
    let offsetX = 0;
    let offsetY = 0;
    let animationFrameId = null;

    const animateGrid = () => {
        offsetX = (offsetX + 0.35) % 40;
        offsetY = (offsetY + 0.35) % 40;
        gridHero.style.setProperty('--grid-x', `${offsetX}px`);
        gridHero.style.setProperty('--grid-y', `${offsetY}px`);
        animationFrameId = window.requestAnimationFrame(animateGrid);
    };

    const updateCursor = (event) => {
        const bounds = gridHero.getBoundingClientRect();
        gridHero.style.setProperty('--cursor-x', `${event.clientX - bounds.left}px`);
        gridHero.style.setProperty('--cursor-y', `${event.clientY - bounds.top}px`);
    };

    gridHero.addEventListener('pointermove', updateCursor);
    animationFrameId = window.requestAnimationFrame(animateGrid);

    window.addEventListener('beforeunload', () => {
        if (animationFrameId) {
            window.cancelAnimationFrame(animationFrameId);
        }
    });
}

const revealItems = document.querySelectorAll('.reveal-on-scroll');

if (revealItems.length > 0) {
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.18
        });

        revealItems.forEach((item) => revealObserver.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }
}

document.querySelectorAll('[data-enhanced-form]').forEach((form) => {
    const inputs = form.querySelectorAll('input[required]');

    const messages = {
        id_number: 'Use 3-30 characters: letters, numbers, and dashes only.',
        first_name: 'First name must be at least 2 characters.',
        last_name: 'Last name must be at least 2 characters.',
        barangay: 'Barangay is required.',
        city: 'City is required.',
        province: 'Province is required.',
        contact_number: 'Use a valid contact number, 7-20 digits or symbols.',
        email: 'Use a valid email address.'
    };

    const showFieldState = (input, forceMessage = false) => {
        const field = input.closest('.field');

        if (!field || input.readOnly) {
            return true;
        }

        let message = field.querySelector('.field-message');

        if (!message) {
            message = document.createElement('small');
            message.className = 'field-message';
            field.appendChild(message);
        }

        const isEmpty = input.value.trim() === '';
        const isValid = input.checkValidity();
        const shouldShowState = forceMessage || !isEmpty;

        input.classList.toggle('is-valid', !isEmpty && isValid);
        input.classList.toggle('is-invalid', shouldShowState && !isValid);
        field.classList.toggle('has-error', shouldShowState && !isValid);
        message.textContent = shouldShowState && !isValid
            ? (isEmpty ? 'This field is required.' : (messages[input.name] || 'Please check this field.'))
            : '';

        return isValid;
    };

    inputs.forEach((input) => {
        input.addEventListener('input', () => showFieldState(input));
        input.addEventListener('blur', () => showFieldState(input));
        input.addEventListener('invalid', () => showFieldState(input, true));
    });

    form.addEventListener('submit', (event) => {
        let firstInvalidInput = null;

        inputs.forEach((input) => {
            if (!showFieldState(input, true) && !firstInvalidInput) {
                firstInvalidInput = input;
            }
        });

        if (firstInvalidInput) {
            event.preventDefault();
            firstInvalidInput.focus();
        }
    });
});
