/**
 * Accordion interaction logic.
 *
 * Features:
 * - Only one item open at a time
 * - Smooth height transitions
 * - Keyboard navigation (Enter, Space)
 * - ARIA attributes for accessibility
 */

function openItem( item ) {
	const content = item.querySelector( '.sfb-accordion-item__content' );
	const header = item.querySelector( '.sfb-accordion-item__header' );

	if ( ! content || ! header ) {
		return;
	}

	item.classList.add( 'is-open' );
	header.setAttribute( 'aria-expanded', 'true' );
	content.style.maxHeight = content.scrollHeight + 'px';
}

function closeItem( item ) {
	const content = item.querySelector( '.sfb-accordion-item__content' );
	const header = item.querySelector( '.sfb-accordion-item__header' );

	if ( ! content || ! header ) {
		return;
	}

	item.classList.remove( 'is-open' );
	header.setAttribute( 'aria-expanded', 'false' );
	content.style.maxHeight = null;
}

function toggleItem( item, allItems ) {
	const isOpen = item.classList.contains( 'is-open' );

	// Close all other items
	allItems.forEach( ( otherItem ) => {
		if ( otherItem !== item && otherItem.classList.contains( 'is-open' ) ) {
			closeItem( otherItem );
		}
	} );

	// Toggle current item
	if ( isOpen ) {
		closeItem( item );
	} else {
		openItem( item );
	}
}

function initAccordion( container ) {
	const items = Array.from( container.querySelectorAll( '.sfb-accordion-item' ) );

	if ( items.length === 0 ) {
		return;
	}

	items.forEach( ( item, index ) => {
		const header = item.querySelector( '.sfb-accordion-item__header' );
		const content = item.querySelector( '.sfb-accordion-item__content' );

		if ( ! header || ! content ) {
			return;
		}

		// Set up ARIA attributes
		const itemId = `accordion-item-${ index }`;
		const contentId = `accordion-content-${ index }`;

		header.setAttribute( 'id', itemId );
		header.setAttribute( 'aria-controls', contentId );
		header.setAttribute( 'aria-expanded', 'false' );
		header.setAttribute( 'role', 'button' );
		header.setAttribute( 'tabindex', '0' );

		content.setAttribute( 'id', contentId );
		content.setAttribute( 'aria-labelledby', itemId );
		content.setAttribute( 'role', 'region' );

		// Click handler
		header.addEventListener( 'click', () => {
			toggleItem( item, items );
		} );

		// Keyboard handler
		header.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'Enter' || event.key === ' ' ) {
				event.preventDefault();
				toggleItem( item, items );
			}
		} );
	} );

	// Open first item by default
	if ( items.length > 0 ) {
		openItem( items[ 0 ] );
	}
}

// Initialize all accordions on page load
document.addEventListener( 'DOMContentLoaded', () => {
	const accordions = document.querySelectorAll( '.wp-block-sfb-accordion' );
	accordions.forEach( ( accordion ) => {
		initAccordion( accordion );
	} );
} );
