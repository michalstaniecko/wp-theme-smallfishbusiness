/**
 * Frontend accordion functionality for comparison tables.
 *
 * @package SFB\ComparisonTables
 */

( function () {
	'use strict';

	/**
	 * Initialize all comparison tables on the page.
	 */
	function init() {
		const tables = document.querySelectorAll( '.sfb-ct' );
		tables.forEach( initTable );
	}

	/**
	 * Initialize a single table.
	 *
	 * @param {HTMLElement} table Table container element.
	 */
	function initTable( table ) {
		const sections = table.querySelectorAll( '.sfb-ct__section' );
		const products = table.querySelectorAll( '.sfb-ct__product' );

		// Set CSS variable for column count.
		table.style.setProperty( '--sfb-ct-columns', products.length );

		// Initialize sections.
		sections.forEach( ( section, index ) => {
			initSection( section, index );
		} );
	}

	/**
	 * Initialize a section with accordion functionality.
	 *
	 * @param {HTMLElement} section Section element.
	 * @param {number}      index   Section index.
	 */
	function initSection( section, index ) {
		const header = section.querySelector( '.sfb-ct__section-header' );
		const content = section.querySelector( '.sfb-ct__section-content' );

		if ( ! header || ! content ) {
			return;
		}

		// Generate unique IDs.
		const tableId = section.closest( '.sfb-ct' ).dataset.tableId || 'ct';
		const sectionId = `sfb-ct-${tableId}-section-${index}`;
		const contentId = `${sectionId}-content`;

		// Set ARIA attributes.
		header.setAttribute( 'id', sectionId );
		header.setAttribute( 'aria-controls', contentId );
		content.setAttribute( 'id', contentId );
		content.setAttribute( 'role', 'region' );
		content.setAttribute( 'aria-labelledby', sectionId );

		// Click handler.
		header.addEventListener( 'click', function () {
			toggleSection( section );
		} );

		// Keyboard handler.
		header.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Enter' || event.key === ' ' ) {
				event.preventDefault();
				toggleSection( section );
			}
		} );
	}

	/**
	 * Toggle section open/closed state.
	 *
	 * @param {HTMLElement} section Section element.
	 */
	function toggleSection( section ) {
		const header = section.querySelector( '.sfb-ct__section-header' );
		const isOpen = section.classList.contains( 'is-open' );

		section.classList.toggle( 'is-open' );
		header.setAttribute( 'aria-expanded', ! isOpen );
	}

	/**
	 * Open a specific section.
	 *
	 * @param {HTMLElement} section Section element.
	 */
	function openSection( section ) {
		const header = section.querySelector( '.sfb-ct__section-header' );

		section.classList.add( 'is-open' );
		header.setAttribute( 'aria-expanded', 'true' );
	}

	/**
	 * Close a specific section.
	 *
	 * @param {HTMLElement} section Section element.
	 */
	function closeSection( section ) {
		const header = section.querySelector( '.sfb-ct__section-header' );

		section.classList.remove( 'is-open' );
		header.setAttribute( 'aria-expanded', 'false' );
	}

	/**
	 * Open all sections in a table.
	 *
	 * @param {HTMLElement} table Table element.
	 */
	function openAllSections( table ) {
		const sections = table.querySelectorAll( '.sfb-ct__section' );
		sections.forEach( openSection );
	}

	/**
	 * Close all sections in a table.
	 *
	 * @param {HTMLElement} table Table element.
	 */
	function closeAllSections( table ) {
		const sections = table.querySelectorAll( '.sfb-ct__section' );
		sections.forEach( closeSection );
	}

	// Expose API for external use.
	window.sfbComparisonTable = {
		init: init,
		openSection: openSection,
		closeSection: closeSection,
		toggleSection: toggleSection,
		openAllSections: openAllSections,
		closeAllSections: closeAllSections,
	};

	// Initialize on DOM ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
