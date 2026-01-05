/**
 * Admin repeater functionality for comparison table meta boxes.
 *
 * @package SFB\ComparisonTables
 */

( function () {
	'use strict';

	/**
	 * Initialize repeater functionality.
	 */
	function init() {
		// Product repeater.
		initRepeater( 'sfb-ct-products-repeater', 'sfb-ct-product-template' );

		// Section repeater.
		initRepeater( 'sfb-ct-sections-repeater', 'sfb-ct-section-template' );

		// Row add buttons (delegated).
		document.addEventListener( 'click', handleRowAdd );

		// Cell type change (delegated).
		document.addEventListener( 'change', handleCellTypeChange );

		// Title updates (delegated).
		document.addEventListener( 'input', handleTitleUpdate );

		// Toggle collapse (delegated).
		document.addEventListener( 'click', handleToggle );

		// Remove buttons (delegated).
		document.addEventListener( 'click', handleRemove );

		// Sync product count to sections when products change.
		observeProductChanges();
	}

	/**
	 * Initialize a repeater.
	 *
	 * @param {string} repeaterId  Repeater container ID.
	 * @param {string} templateId  Template ID for new items.
	 */
	function initRepeater( repeaterId, templateId ) {
		const repeater = document.getElementById( repeaterId );
		if ( ! repeater ) {
			return;
		}

		const addBtn = repeater.querySelector( '.sfb-ct-repeater__add' );
		const template = document.getElementById( templateId );
		const itemsContainer = repeater.querySelector( '.sfb-ct-repeater__items' );

		if ( addBtn && template && itemsContainer ) {
			addBtn.addEventListener( 'click', function () {
				addRepeaterItem( itemsContainer, template, repeaterId );
			} );
		}

		// Initialize drag and drop.
		initDragAndDrop( itemsContainer );
	}

	/**
	 * Add a new repeater item.
	 *
	 * @param {HTMLElement} container  Container element.
	 * @param {HTMLElement} template   Template element.
	 * @param {string}      repeaterId Repeater ID for context.
	 */
	function addRepeaterItem( container, template, repeaterId ) {
		const items = container.querySelectorAll( '.sfb-ct-repeater__item' );
		const newIndex = items.length;

		let html = template.innerHTML;
		html = html.replace( /__INDEX__/g, newIndex );
		html = html.replace( /__SECTION_INDEX__/g, newIndex );

		const tempDiv = document.createElement( 'div' );
		tempDiv.innerHTML = html;
		const newItem = tempDiv.firstElementChild;

		// If adding a section, ensure correct product count for cells.
		if ( repeaterId === 'sfb-ct-sections-repeater' ) {
			const productCount = getProductCount();
			updateRowCells( newItem, productCount );
		}

		container.appendChild( newItem );

		// Initialize drag and drop for rows in new section.
		const rowsContainer = newItem.querySelector( '.sfb-ct-rows-container' );
		if ( rowsContainer ) {
			initDragAndDrop( rowsContainer );
		}

		// Focus the first input.
		const firstInput = newItem.querySelector( 'input[type="text"]' );
		if ( firstInput ) {
			firstInput.focus();
		}
	}

	/**
	 * Handle row add button click.
	 *
	 * @param {Event} event Click event.
	 */
	function handleRowAdd( event ) {
		const btn = event.target.closest( '.sfb-ct-add-row' );
		if ( ! btn ) {
			return;
		}

		const sectionIndex = btn.dataset.sectionIndex;
		const section = btn.closest( '.sfb-ct-section' );
		const rowsContainer = section.querySelector( '.sfb-ct-rows-container' );
		const rowTemplate = document.getElementById( 'sfb-ct-row-template' );

		if ( ! rowsContainer || ! rowTemplate ) {
			return;
		}

		const rows = rowsContainer.querySelectorAll( '.sfb-ct-row' );
		const newRowIndex = rows.length;
		const productCount = getProductCount();

		let html = rowTemplate.innerHTML;
		html = html.replace( /__SECTION_INDEX__/g, sectionIndex );
		html = html.replace( /__ROW_INDEX__/g, newRowIndex );

		const tempDiv = document.createElement( 'div' );
		tempDiv.innerHTML = html;
		const newRow = tempDiv.firstElementChild;

		// Adjust cells to match product count.
		updateRowCells( newRow, productCount );

		rowsContainer.appendChild( newRow );

		// Focus the feature name input.
		const featureInput = newRow.querySelector( '.sfb-ct-row__feature input' );
		if ( featureInput ) {
			featureInput.focus();
		}
	}

	/**
	 * Handle cell type change.
	 *
	 * @param {Event} event Change event.
	 */
	function handleCellTypeChange( event ) {
		if ( ! event.target.classList.contains( 'sfb-ct-cell__type' ) ) {
			return;
		}

		const cell = event.target.closest( '.sfb-ct-cell' );
		const valueInput = cell.querySelector( '.sfb-ct-cell__value' );
		const urlInput = cell.querySelector( '.sfb-ct-cell__url' );
		const type = event.target.value;

		// Show/hide URL input for link type.
		if ( urlInput ) {
			urlInput.style.display = type === 'link' ? '' : 'none';
		}

		// Show/hide value input for check/dash types.
		if ( valueInput ) {
			valueInput.style.display = ( type === 'check' || type === 'dash' ) ? 'none' : '';
		}
	}

	/**
	 * Handle title input updates for live preview.
	 *
	 * @param {Event} event Input event.
	 */
	function handleTitleUpdate( event ) {
		const input = event.target;

		// Product name.
		if ( input.classList.contains( 'sfb-ct-product-name' ) ) {
			const item = input.closest( '.sfb-ct-product' );
			const title = item.querySelector( '.sfb-ct-repeater__item-title' );
			title.textContent = input.value || sfbCtAdmin.i18n.addProduct;
		}

		// Section title.
		if ( input.classList.contains( 'sfb-ct-section-title' ) ) {
			const item = input.closest( '.sfb-ct-section' );
			const title = item.querySelector( '.sfb-ct-repeater__item-title' );
			title.textContent = input.value || sfbCtAdmin.i18n.addSection;
		}
	}

	/**
	 * Handle toggle collapse.
	 *
	 * @param {Event} event Click event.
	 */
	function handleToggle( event ) {
		const btn = event.target.closest( '.sfb-ct-repeater__item-toggle' );
		if ( ! btn ) {
			return;
		}

		const item = btn.closest( '.sfb-ct-repeater__item' );
		item.classList.toggle( 'is-collapsed' );

		const isExpanded = ! item.classList.contains( 'is-collapsed' );
		btn.setAttribute( 'aria-expanded', isExpanded );
	}

	/**
	 * Handle remove button click.
	 *
	 * @param {Event} event Click event.
	 */
	function handleRemove( event ) {
		// Item remove.
		let btn = event.target.closest( '.sfb-ct-repeater__item-remove' );
		if ( btn ) {
			if ( confirm( sfbCtAdmin.i18n.confirmDelete ) ) {
				const item = btn.closest( '.sfb-ct-repeater__item' );
				const container = item.parentElement;
				item.remove();
				reindexItems( container );
			}
			return;
		}

		// Row remove.
		btn = event.target.closest( '.sfb-ct-row__remove' );
		if ( btn ) {
			const row = btn.closest( '.sfb-ct-row' );
			const container = row.parentElement;
			row.remove();
			reindexRows( container );
		}
	}

	/**
	 * Reindex items after removal.
	 *
	 * @param {HTMLElement} container Items container.
	 */
	function reindexItems( container ) {
		const items = container.querySelectorAll( '.sfb-ct-repeater__item' );

		items.forEach( ( item, index ) => {
			item.dataset.index = index;

			// Update input names.
			const inputs = item.querySelectorAll( 'input, select, textarea' );
			inputs.forEach( ( input ) => {
				const name = input.getAttribute( 'name' );
				if ( name ) {
					// Replace index in name attribute.
					const newName = name.replace( /\[\d+\]/, `[${index}]` );
					input.setAttribute( 'name', newName );
				}
			} );

			// Update nested row indices if section.
			const rowsContainer = item.querySelector( '.sfb-ct-rows-container' );
			if ( rowsContainer ) {
				rowsContainer.dataset.sectionIndex = index;
				reindexRows( rowsContainer );

				// Update add row button.
				const addRowBtn = item.querySelector( '.sfb-ct-add-row' );
				if ( addRowBtn ) {
					addRowBtn.dataset.sectionIndex = index;
				}
			}
		} );
	}

	/**
	 * Reindex rows within a section.
	 *
	 * @param {HTMLElement} container Rows container.
	 */
	function reindexRows( container ) {
		const sectionIndex = container.dataset.sectionIndex;
		const rows = container.querySelectorAll( '.sfb-ct-row' );

		rows.forEach( ( row, rowIndex ) => {
			row.dataset.rowIndex = rowIndex;

			// Update input names.
			const inputs = row.querySelectorAll( 'input, select' );
			inputs.forEach( ( input ) => {
				const name = input.getAttribute( 'name' );
				if ( name ) {
					// Update section and row indices.
					let newName = name.replace(
						/sfb_ct_sections\[\d+\]\[rows\]\[\d+\]/,
						`sfb_ct_sections[${sectionIndex}][rows][${rowIndex}]`
					);
					input.setAttribute( 'name', newName );
				}
			} );
		} );
	}

	/**
	 * Get current product count.
	 *
	 * @return {number} Number of products.
	 */
	function getProductCount() {
		const products = document.querySelectorAll( '#sfb-ct-products-items .sfb-ct-product' );
		return Math.max( 1, products.length );
	}

	/**
	 * Update row cells to match product count.
	 *
	 * @param {HTMLElement} element       Element containing cells.
	 * @param {number}      productCount  Target product count.
	 */
	function updateRowCells( element, productCount ) {
		const rows = element.querySelectorAll( '.sfb-ct-row' );

		rows.forEach( ( row ) => {
			const valuesContainer = row.querySelector( '.sfb-ct-row__values' );
			const cells = valuesContainer.querySelectorAll( '.sfb-ct-cell' );
			const currentCount = cells.length;

			// Add cells if needed.
			while ( valuesContainer.querySelectorAll( '.sfb-ct-cell' ).length < productCount ) {
				const cellIndex = valuesContainer.querySelectorAll( '.sfb-ct-cell' ).length;
				const sectionIndex = row.closest( '.sfb-ct-section' )?.dataset.index || '__SECTION_INDEX__';
				const rowIndex = row.dataset.rowIndex || '__ROW_INDEX__';

				const cell = createCell( sectionIndex, rowIndex, cellIndex );
				valuesContainer.appendChild( cell );
			}

			// Remove excess cells if needed.
			while ( valuesContainer.querySelectorAll( '.sfb-ct-cell' ).length > productCount ) {
				const lastCell = valuesContainer.querySelector( '.sfb-ct-cell:last-child' );
				if ( lastCell ) {
					lastCell.remove();
				}
			}
		} );
	}

	/**
	 * Create a new cell element.
	 *
	 * @param {string|number} sectionIndex Section index.
	 * @param {string|number} rowIndex     Row index.
	 * @param {number}        cellIndex    Cell index.
	 * @return {HTMLElement} Cell element.
	 */
	function createCell( sectionIndex, rowIndex, cellIndex ) {
		const namePrefix = `sfb_ct_sections[${sectionIndex}][rows][${rowIndex}][values][${cellIndex}]`;

		const cell = document.createElement( 'div' );
		cell.className = 'sfb-ct-cell';
		cell.dataset.valueIndex = cellIndex;

		cell.innerHTML = `
			<select name="${namePrefix}[type]" class="sfb-ct-cell__type">
				<option value="text">${sfbCtAdmin.i18n.valueTypes.text}</option>
				<option value="check">${sfbCtAdmin.i18n.valueTypes.check}</option>
				<option value="dash">${sfbCtAdmin.i18n.valueTypes.dash}</option>
				<option value="link">${sfbCtAdmin.i18n.valueTypes.link}</option>
			</select>
			<input type="text" name="${namePrefix}[value]" class="sfb-ct-cell__value" placeholder="Value">
			<input type="url" name="${namePrefix}[url]" class="sfb-ct-cell__url" placeholder="URL" style="display:none;">
		`;

		return cell;
	}

	/**
	 * Observe product changes and sync to sections.
	 */
	function observeProductChanges() {
		const productsContainer = document.getElementById( 'sfb-ct-products-items' );
		if ( ! productsContainer ) {
			return;
		}

		const observer = new MutationObserver( function ( mutations ) {
			const productCount = getProductCount();
			const sectionsRepeater = document.getElementById( 'sfb-ct-sections-repeater' );

			if ( sectionsRepeater ) {
				sectionsRepeater.dataset.productCount = productCount;

				// Update existing rows.
				const sections = sectionsRepeater.querySelectorAll( '.sfb-ct-section' );
				sections.forEach( ( section ) => {
					updateRowCells( section, productCount );
				} );
			}
		} );

		observer.observe( productsContainer, { childList: true } );
	}

	/**
	 * Initialize drag and drop for a container.
	 *
	 * @param {HTMLElement} container Container element.
	 */
	function initDragAndDrop( container ) {
		if ( ! container ) {
			return;
		}

		const items = container.children;
		Array.from( items ).forEach( ( item ) => {
			const handle = item.querySelector( '.sfb-ct-repeater__item-handle, .sfb-ct-row__handle' );
			if ( handle ) {
				item.setAttribute( 'draggable', 'true' );

				item.addEventListener( 'dragstart', handleDragStart );
				item.addEventListener( 'dragend', handleDragEnd );
				item.addEventListener( 'dragover', handleDragOver );
				item.addEventListener( 'drop', handleDrop );
				item.addEventListener( 'dragleave', handleDragLeave );
			}
		} );
	}

	let draggedItem = null;

	/**
	 * Handle drag start.
	 *
	 * @param {DragEvent} event Drag event.
	 */
	function handleDragStart( event ) {
		draggedItem = event.target.closest( '.sfb-ct-repeater__item, .sfb-ct-row' );
		draggedItem.classList.add( 'is-dragging' );
		event.dataTransfer.effectAllowed = 'move';
	}

	/**
	 * Handle drag end.
	 *
	 * @param {DragEvent} event Drag event.
	 */
	function handleDragEnd( event ) {
		if ( draggedItem ) {
			draggedItem.classList.remove( 'is-dragging' );
		}
		document.querySelectorAll( '.drag-over' ).forEach( ( el ) => el.classList.remove( 'drag-over' ) );
		draggedItem = null;
	}

	/**
	 * Handle drag over.
	 *
	 * @param {DragEvent} event Drag event.
	 */
	function handleDragOver( event ) {
		event.preventDefault();
		event.dataTransfer.dropEffect = 'move';

		const target = event.target.closest( '.sfb-ct-repeater__item, .sfb-ct-row' );
		if ( target && target !== draggedItem ) {
			target.classList.add( 'drag-over' );
		}
	}

	/**
	 * Handle drag leave.
	 *
	 * @param {DragEvent} event Drag event.
	 */
	function handleDragLeave( event ) {
		const target = event.target.closest( '.sfb-ct-repeater__item, .sfb-ct-row' );
		if ( target ) {
			target.classList.remove( 'drag-over' );
		}
	}

	/**
	 * Handle drop.
	 *
	 * @param {DragEvent} event Drop event.
	 */
	function handleDrop( event ) {
		event.preventDefault();

		const target = event.target.closest( '.sfb-ct-repeater__item, .sfb-ct-row' );
		if ( ! target || target === draggedItem ) {
			return;
		}

		target.classList.remove( 'drag-over' );

		const container = target.parentElement;
		const items = Array.from( container.children );
		const draggedIndex = items.indexOf( draggedItem );
		const targetIndex = items.indexOf( target );

		if ( draggedIndex < targetIndex ) {
			container.insertBefore( draggedItem, target.nextSibling );
		} else {
			container.insertBefore( draggedItem, target );
		}

		// Reindex after drag.
		if ( container.classList.contains( 'sfb-ct-repeater__items' ) ) {
			reindexItems( container );
		} else if ( container.classList.contains( 'sfb-ct-rows-container' ) ) {
			reindexRows( container );
		}
	}

	// Initialize on DOM ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
