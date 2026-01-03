/**
 * Mobile Menu Module
 *
 * Handles the mobile navigation toggle and submenu functionality.
 */

export function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (!toggle || !menu) {
        return;
    }

    const hamburgerIcon = toggle.querySelector('.hamburger-icon');
    const closeIcon = toggle.querySelector('.close-icon');

    // Main menu toggle
    toggle.addEventListener('click', () => {
        const isHidden = menu.classList.contains('hidden');

        menu.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

        if (hamburgerIcon && closeIcon) {
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    });

    // Setup submenu toggles
    initMobileSubmenus(menu);

    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
        if (!menu.contains(event.target) && !toggle.contains(event.target)) {
            closeMenu();
        }
    });

    // Close menu on escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
            closeMenu();
            toggle.focus();
        }
    });

    function closeMenu() {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');

        if (hamburgerIcon && closeIcon) {
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }

        // Close all submenus
        menu.querySelectorAll('.mobile-submenu-toggle[aria-expanded="true"]').forEach(btn => {
            btn.setAttribute('aria-expanded', 'false');
            const wrapper = btn.parentElement;
            const submenu = wrapper?.nextElementSibling;
            if (submenu?.classList.contains('mobile-submenu')) {
                submenu.hidden = true;
            }
        });
    }
}

/**
 * Initialize mobile submenu toggle buttons
 */
function initMobileSubmenus(menu) {
    const submenuToggles = menu.querySelectorAll('.mobile-submenu-toggle');

    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            const wrapper = toggle.parentElement;
            const submenu = wrapper?.nextElementSibling;

            if (!submenu?.classList.contains('mobile-submenu')) return;

            // Toggle this submenu
            toggle.setAttribute('aria-expanded', !isExpanded ? 'true' : 'false');

            if (isExpanded) {
                // Close submenu with animation
                animateClose(submenu);
            } else {
                // Open submenu with animation
                animateOpen(submenu);
            }
        });

        // Keyboard support for submenu toggles
        toggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle.click();
            }
        });
    });
}

/**
 * Animate submenu open
 */
function animateOpen(submenu) {
    submenu.hidden = false;
    submenu.classList.add('is-animating');

    // Get the full height
    const height = submenu.scrollHeight;
    submenu.style.maxHeight = '0px';

    // Force reflow
    submenu.offsetHeight;

    // Animate to full height
    submenu.style.maxHeight = height + 'px';

    // Clean up after animation
    submenu.addEventListener('transitionend', function handler() {
        submenu.style.maxHeight = '';
        submenu.classList.remove('is-animating');
        submenu.removeEventListener('transitionend', handler);
    });
}

/**
 * Animate submenu close
 */
function animateClose(submenu) {
    submenu.classList.add('is-animating');
    submenu.style.maxHeight = submenu.scrollHeight + 'px';

    // Force reflow
    submenu.offsetHeight;

    // Animate to zero
    submenu.style.maxHeight = '0px';

    // Hide after animation
    submenu.addEventListener('transitionend', function handler() {
        submenu.hidden = true;
        submenu.style.maxHeight = '';
        submenu.classList.remove('is-animating');
        submenu.removeEventListener('transitionend', handler);
    });
}
