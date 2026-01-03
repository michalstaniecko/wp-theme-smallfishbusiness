/**
 * Mobile Menu Module
 *
 * Handles the mobile navigation toggle functionality.
 */

export function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (!toggle || !menu) {
        return;
    }

    const hamburgerIcon = toggle.querySelector('.hamburger-icon');
    const closeIcon = toggle.querySelector('.close-icon');

    toggle.addEventListener('click', () => {
        const isHidden = menu.classList.contains('hidden');

        // Toggle menu visibility
        menu.classList.toggle('hidden');

        // Update aria-expanded
        toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

        // Toggle icons
        if (hamburgerIcon && closeIcon) {
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    });

    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
        if (!menu.contains(event.target) && !toggle.contains(event.target)) {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');

            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }
    });

    // Close menu on escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();

            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }
    });
}
