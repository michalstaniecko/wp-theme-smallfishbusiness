/**
 * Dropdown Navigation Module
 *
 * Handles desktop hover dropdowns with delays, keyboard navigation,
 * and edge detection for Level 3 flyout menus.
 */

const HOVER_DELAY = 150; // ms delay before showing/hiding dropdown
const EDGE_THRESHOLD = 200; // px from right edge to trigger left flip

export function initDropdownNav() {
    const nav = document.getElementById('site-navigation');
    if (!nav) return;

    const dropdownItems = nav.querySelectorAll('.has-dropdown');

    dropdownItems.forEach(item => {
        setupDesktopHover(item);
        setupKeyboardNav(item);
    });

    // Handle edge detection for Level 3 menus
    setupEdgeDetection(nav);

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!nav.contains(e.target)) {
            closeAllDropdowns(nav);
        }
    });
}

/**
 * Setup hover behavior with delay for desktop
 */
function setupDesktopHover(item) {
    let hoverTimeout = null;

    const showDropdown = () => {
        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => {
            item.setAttribute('aria-expanded', 'true');
        }, HOVER_DELAY);
    };

    const hideDropdown = () => {
        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => {
            item.setAttribute('aria-expanded', 'false');
        }, HOVER_DELAY);
    };

    item.addEventListener('mouseenter', showDropdown);
    item.addEventListener('mouseleave', hideDropdown);

    // Also handle focus for accessibility
    item.addEventListener('focusin', () => {
        item.setAttribute('aria-expanded', 'true');
    });

    item.addEventListener('focusout', (e) => {
        // Only close if focus moved outside this dropdown
        if (!item.contains(e.relatedTarget)) {
            item.setAttribute('aria-expanded', 'false');
        }
    });
}

/**
 * Setup keyboard navigation
 */
function setupKeyboardNav(item) {
    const submenu = item.querySelector('.dropdown-menu');
    if (!submenu) return;

    const links = submenu.querySelectorAll('a[role="menuitem"]');

    item.addEventListener('keydown', (e) => {
        const isExpanded = item.getAttribute('aria-expanded') === 'true';

        switch (e.key) {
            case 'Enter':
            case ' ':
                if (e.target === item.querySelector(':scope > a')) {
                    e.preventDefault();
                    item.setAttribute('aria-expanded', !isExpanded ? 'true' : 'false');
                    if (!isExpanded && links.length) {
                        links[0].focus();
                    }
                }
                break;

            case 'Escape':
                item.setAttribute('aria-expanded', 'false');
                item.querySelector(':scope > a')?.focus();
                break;

            case 'ArrowDown':
                if (isExpanded && links.length) {
                    e.preventDefault();
                    const currentIndex = Array.from(links).indexOf(document.activeElement);
                    const nextIndex = currentIndex < links.length - 1 ? currentIndex + 1 : 0;
                    links[nextIndex]?.focus();
                }
                break;

            case 'ArrowUp':
                if (isExpanded && links.length) {
                    e.preventDefault();
                    const currentIndex = Array.from(links).indexOf(document.activeElement);
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : links.length - 1;
                    links[prevIndex]?.focus();
                }
                break;

            case 'ArrowRight':
                // Open Level 3 submenu if exists
                const parentLi = e.target.closest('li.has-dropdown');
                if (parentLi) {
                    const childMenu = parentLi.querySelector('.dropdown-menu--level-3');
                    if (childMenu) {
                        parentLi.setAttribute('aria-expanded', 'true');
                        childMenu.querySelector('a')?.focus();
                    }
                }
                break;

            case 'ArrowLeft':
                // Close Level 3 and return to parent
                const level3Parent = e.target.closest('.dropdown-menu--level-3')?.parentElement;
                if (level3Parent) {
                    level3Parent.setAttribute('aria-expanded', 'false');
                    level3Parent.querySelector(':scope > a')?.focus();
                }
                break;
        }
    });
}

/**
 * Detect screen edge and flip Level 3 menus to the left if needed
 */
function setupEdgeDetection(nav) {
    const level3Menus = nav.querySelectorAll('.dropdown-menu--level-3');

    const checkEdges = () => {
        const viewportWidth = window.innerWidth;

        level3Menus.forEach(menu => {
            const parentLi = menu.parentElement;
            if (!parentLi) return;

            const rect = parentLi.getBoundingClientRect();
            const spaceOnRight = viewportWidth - rect.right;

            if (spaceOnRight < EDGE_THRESHOLD) {
                menu.classList.add('flip-left');
            } else {
                menu.classList.remove('flip-left');
            }
        });
    };

    // Check on load and resize
    checkEdges();
    window.addEventListener('resize', checkEdges);

    // Also check when dropdowns open
    nav.querySelectorAll('.has-dropdown').forEach(item => {
        const observer = new MutationObserver(checkEdges);
        observer.observe(item, { attributes: true, attributeFilter: ['aria-expanded'] });
    });
}

/**
 * Close all open dropdowns
 */
function closeAllDropdowns(nav) {
    nav.querySelectorAll('.has-dropdown[aria-expanded="true"]').forEach(item => {
        item.setAttribute('aria-expanded', 'false');
    });
}
