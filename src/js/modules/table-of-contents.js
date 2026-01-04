/**
 * Table of Contents Module
 * Generates TOC from article headings (H2, H3)
 * Features: smooth scroll, active section highlighting, collapsible, sticky bar
 */

export function initTableOfContents() {
    const articleContent = document.querySelector('.prose');
    const tocWrapper = document.getElementById('toc-wrapper');
    const tocContainer = document.getElementById('toc-container');
    const tocNav = document.getElementById('toc');
    const tocToggle = document.getElementById('toc-toggle');
    const tocToggleIcon = document.getElementById('toc-toggle-icon');

    // Sticky ToC elements
    const stickyToc = document.getElementById('toc-sticky');
    const stickyToggle = document.getElementById('toc-sticky-toggle');
    const stickyIcon = document.getElementById('toc-sticky-icon');
    const stickyContent = document.getElementById('toc-sticky-content');

    if (!articleContent || !tocContainer || !tocNav) return;

    // Get all H2 and H3 headings
    const headings = articleContent.querySelectorAll('h2, h3');

    if (headings.length < 2) {
        // Don't show TOC if less than 2 headings
        return;
    }

    // Generate IDs for headings if they don't have one
    const tocItems = [];

    headings.forEach((heading, index) => {
        if (!heading.id) {
            heading.id = `section-${index + 1}`;
        }

        tocItems.push({
            id: heading.id,
            text: heading.textContent,
            level: heading.tagName.toLowerCase(),
        });
    });

    // Build TOC HTML
    const tocHTML = buildTocHTML(tocItems);
    tocNav.innerHTML = tocHTML;

    // Show TOC container
    tocContainer.classList.remove('hidden');

    // Initialize toggle collapse/expand for main ToC
    if (tocToggle && tocToggleIcon) {
        initToggle(tocToggle, tocToggleIcon, tocNav);
    }

    // Initialize smooth scroll for main ToC
    initSmoothScroll(tocNav);

    // Initialize active section highlighting
    initActiveHighlighting(headings, tocNav, stickyContent);

    // Initialize sticky ToC
    if (stickyToc && stickyToggle && stickyContent && tocWrapper) {
        initStickyToc(tocWrapper, stickyToc, stickyToggle, stickyIcon, stickyContent, tocHTML);
    }
}

/**
 * Build TOC HTML structure
 */
function buildTocHTML(items) {
    let html = '<ul class="space-y-1">';

    items.forEach(item => {
        const indent = item.level === 'h3' ? 'pl-4' : '';

        html += `
            <li class="${indent}">
                <a href="#${item.id}"
                   class="toc-link"
                   data-target="${item.id}">
                    ${item.text}
                </a>
            </li>
        `;
    });

    html += '</ul>';
    return html;
}

/**
 * Initialize toggle collapse/expand
 */
function initToggle(toggleBtn, toggleIcon, content) {
    toggleBtn.addEventListener('click', () => {
        content.classList.toggle('hidden');
        toggleIcon.classList.toggle('rotate-180');
    });
}

/**
 * Initialize smooth scroll for TOC links
 */
function initSmoothScroll(container) {
    container.addEventListener('click', (e) => {
        const link = e.target.closest('.toc-link');
        if (!link) return;

        e.preventDefault();

        const targetId = link.getAttribute('data-target');
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            const headerOffset = 140; // Account for sticky header + sticky ToC
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

            // Update URL hash without jumping
            history.pushState(null, null, `#${targetId}`);
        }
    });
}

/**
 * Initialize active section highlighting on scroll
 */
function initActiveHighlighting(headings, tocNav, stickyContent) {
    const updateActiveLinks = (targetId) => {
        // Update main ToC
        const tocLinks = tocNav.querySelectorAll('.toc-link');
        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-target') === targetId) {
                link.classList.add('active');
            }
        });

        // Update sticky ToC if exists
        if (stickyContent) {
            const stickyLinks = stickyContent.querySelectorAll('.toc-link');
            stickyLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-target') === targetId) {
                    link.classList.add('active');
                }
            });
        }
    };

    // Create Intersection Observer
    const observerOptions = {
        root: null,
        rootMargin: '-100px 0px -66% 0px', // Trigger when heading is in top third
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                updateActiveLinks(entry.target.id);
            }
        });
    }, observerOptions);

    // Observe all headings
    headings.forEach(heading => {
        observer.observe(heading);
    });

    // Set first item as active initially
    const tocLinks = tocNav.querySelectorAll('.toc-link');
    if (tocLinks.length > 0) {
        tocLinks[0].classList.add('active');
    }
}

/**
 * Initialize sticky ToC bar
 */
function initStickyToc(tocWrapper, stickyToc, stickyToggle, stickyIcon, stickyContent, tocHTML) {
    // Copy ToC content to sticky
    stickyContent.innerHTML = tocHTML;

    // Initialize smooth scroll for sticky ToC
    initSmoothScroll(stickyContent);

    // Toggle expand/collapse for sticky ToC
    stickyToggle.addEventListener('click', () => {
        stickyContent.classList.toggle('hidden');
        if (stickyIcon) {
            stickyIcon.classList.toggle('rotate-180');
        }
    });

    // Close sticky ToC when clicking a link
    stickyContent.addEventListener('click', (e) => {
        if (e.target.classList.contains('toc-link')) {
            stickyContent.classList.add('hidden');
            if (stickyIcon) {
                stickyIcon.classList.remove('rotate-180');
            }
        }
    });

    // Show/hide sticky ToC based on scroll position
    // Sticky ToC should only appear when main ToC has scrolled ABOVE the viewport (behind header)
    const headerHeight = 80;

    const checkStickyVisibility = () => {
        const rect = tocWrapper.getBoundingClientRect();

        // Show sticky only when main ToC bottom edge is above the header
        // (meaning user has scrolled down past the main ToC)
        if (rect.bottom < headerHeight) {
            stickyToc.classList.remove('hidden');
            stickyToc.classList.add('is-visible');
        } else {
            stickyToc.classList.add('hidden');
            stickyToc.classList.remove('is-visible');
            stickyContent.classList.add('hidden');
            if (stickyIcon) {
                stickyIcon.classList.remove('rotate-180');
            }
        }
    };

    window.addEventListener('scroll', checkStickyVisibility, { passive: true });
    checkStickyVisibility(); // Initial check

    // Close sticky ToC on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !stickyContent.classList.contains('hidden')) {
            stickyContent.classList.add('hidden');
            if (stickyIcon) {
                stickyIcon.classList.remove('rotate-180');
            }
        }
    });
}
