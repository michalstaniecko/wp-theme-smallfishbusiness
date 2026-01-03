/**
 * Table of Contents Module
 * Generates TOC from article headings (H2, H3)
 * Features: smooth scroll, active section highlighting
 */

export function initTableOfContents() {
    const articleContent = document.querySelector('.prose');
    const tocContainer = document.getElementById('toc-container');
    const tocNav = document.getElementById('toc');

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

    // Initialize smooth scroll
    initSmoothScroll(tocNav);

    // Initialize active section highlighting
    initActiveHighlighting(headings, tocNav);
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
 * Initialize smooth scroll for TOC links
 */
function initSmoothScroll(tocNav) {
    tocNav.addEventListener('click', (e) => {
        const link = e.target.closest('.toc-link');
        if (!link) return;

        e.preventDefault();

        const targetId = link.getAttribute('data-target');
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            const headerOffset = 100; // Account for sticky header
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
function initActiveHighlighting(headings, tocNav) {
    const tocLinks = tocNav.querySelectorAll('.toc-link');

    // Create Intersection Observer
    const observerOptions = {
        root: null,
        rootMargin: '-100px 0px -66% 0px', // Trigger when heading is in top third
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Remove active class from all links
                tocLinks.forEach(link => {
                    link.classList.remove('active');
                });

                // Add active class to current link
                const activeLink = tocNav.querySelector(`[data-target="${entry.target.id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    }, observerOptions);

    // Observe all headings
    headings.forEach(heading => {
        observer.observe(heading);
    });

    // Set first item as active initially
    if (tocLinks.length > 0) {
        tocLinks[0].classList.add('active');
    }
}
