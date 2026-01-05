/**
 * Frontend JavaScript for FAQ block
 * Handles accordion toggle functionality - only one item open at a time
 */

/**
 * Open FAQ item
 *
 * @param {Element} item FAQ item element
 */
function openItem( item ) {
	const content = item.querySelector( '.sfb-faq-item__content' );
	const header = item.querySelector( '.sfb-faq-item__header' );

	if ( ! content || ! header ) {
		return;
	}

	item.classList.add( 'is-open' );
	header.setAttribute( 'aria-expanded', 'true' );
	content.setAttribute( 'aria-hidden', 'false' );
	content.style.maxHeight = content.scrollHeight + 'px';
}

/**
 * Close FAQ item
 *
 * @param {Element} item FAQ item element
 */
function closeItem( item ) {
	const content = item.querySelector( '.sfb-faq-item__content' );
	const header = item.querySelector( '.sfb-faq-item__header' );

	if ( ! content || ! header ) {
		return;
	}

	item.classList.remove( 'is-open' );
	header.setAttribute( 'aria-expanded', 'false' );
	content.setAttribute( 'aria-hidden', 'true' );
	content.style.maxHeight = '0';
}

/**
 * Toggle FAQ item
 *
 * @param {Element} item FAQ item element
 */
function toggleItem( item ) {
	if ( item.classList.contains( 'is-open' ) ) {
		closeItem( item );
	} else {
		openItem( item );
	}
}

/**
 * Initialize FAQ accordion
 */
function initFAQ() {
	const containers = document.querySelectorAll( '.wp-block-sfb-faq' );

	containers.forEach( ( container ) => {
		const items = container.querySelectorAll( '.sfb-faq-item' );

		// Open first item by default
		if ( items.length > 0 ) {
			openItem( items[ 0 ] );
		}

		items.forEach( ( item, index ) => {
			const header = item.querySelector( '.sfb-faq-item__header' );

			if ( ! header ) {
				return;
			}

			// Setup accessibility attributes
			const itemId = `faq-item-${ container.id || 'default' }-${ index }`;
			const contentId = `${ itemId }-content`;

			header.setAttribute( 'id', itemId );
			header.setAttribute( 'aria-controls', contentId );
			header.setAttribute( 'role', 'button' );
			header.setAttribute( 'tabindex', '0' );

			const content = item.querySelector( '.sfb-faq-item__content' );
			if ( content ) {
				content.setAttribute( 'id', contentId );
				content.setAttribute( 'role', 'region' );
				content.setAttribute( 'aria-labelledby', itemId );
			}

			// Click handler
			header.addEventListener( 'click', () => {
				// Close all other items
				items.forEach( ( otherItem ) => {
					if ( otherItem !== item && otherItem.classList.contains( 'is-open' ) ) {
						closeItem( otherItem );
					}
				} );

				// Toggle current item
				toggleItem( item );
			} );

			// Keyboard handler (Enter and Space)
			header.addEventListener( 'keydown', ( e ) => {
				if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					header.click();
				}
			} );
		} );
	} );
}

// Initialize on DOM ready
if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initFAQ );
} else {
	initFAQ();
}
