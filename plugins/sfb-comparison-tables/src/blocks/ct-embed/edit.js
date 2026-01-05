/**
 * Comparison Table Embed block editor component.
 *
 * @package SFB\ComparisonTables
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Placeholder, Spinner, ComboboxControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { tableId } = attributes;
	const [ tables, setTables ] = useState( [] );
	const [ isLoading, setIsLoading ] = useState( true );

	const blockProps = useBlockProps();

	// Fetch available tables.
	useEffect( () => {
		setIsLoading( true );
		apiFetch( { path: '/sfb-ct/v1/tables' } )
			.then( ( data ) => {
				setTables( data );
				setIsLoading( false );
			} )
			.catch( () => {
				setIsLoading( false );
			} );
	}, [] );

	// Transform tables to ComboboxControl options.
	const tableOptions = tables.map( ( table ) => ( {
		value: table.id,
		label: `${ table.title } (${ table.productCount } products, ${ table.sectionCount } sections)`,
	} ) );

	// Find selected table.
	const selectedTable = tables.find( ( t ) => t.id === tableId );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Table Settings', 'sfb-comparison-tables' ) }>
					<ComboboxControl
						label={ __( 'Select Table', 'sfb-comparison-tables' ) }
						value={ tableId }
						options={ tableOptions }
						onChange={ ( value ) => setAttributes( { tableId: parseInt( value, 10 ) || 0 } ) }
						help={ __( 'Choose a comparison table to embed.', 'sfb-comparison-tables' ) }
					/>
					{ selectedTable && (
						<p>
							<a
								href={ `/wp-admin/post.php?post=${ tableId }&action=edit` }
								target="_blank"
								rel="noopener noreferrer"
							>
								{ __( 'Edit this table', 'sfb-comparison-tables' ) } →
							</a>
						</p>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ isLoading ? (
					<Placeholder
						icon="editor-table"
						label={ __( 'Comparison Table', 'sfb-comparison-tables' ) }
					>
						<Spinner />
					</Placeholder>
				) : ! tableId ? (
					<Placeholder
						icon="editor-table"
						label={ __( 'Comparison Table', 'sfb-comparison-tables' ) }
						instructions={ __( 'Select a comparison table to embed.', 'sfb-comparison-tables' ) }
					>
						{ tables.length > 0 ? (
							<ComboboxControl
								value={ tableId }
								options={ tableOptions }
								onChange={ ( value ) => setAttributes( { tableId: parseInt( value, 10 ) || 0 } ) }
							/>
						) : (
							<p>
								{ __( 'No comparison tables found.', 'sfb-comparison-tables' ) }{ ' ' }
								<a href="/wp-admin/post-new.php?post_type=sfb_comparison_table">
									{ __( 'Create one', 'sfb-comparison-tables' ) }
								</a>
							</p>
						) }
					</Placeholder>
				) : (
					<div className="sfb-ct-embed-preview">
						<div className="sfb-ct-embed-preview__header">
							<span className="dashicons dashicons-editor-table"></span>
							<span>{ selectedTable?.title || __( 'Comparison Table', 'sfb-comparison-tables' ) }</span>
							<a
								href={ `/wp-admin/post.php?post=${ tableId }&action=edit` }
								target="_blank"
								rel="noopener noreferrer"
								className="sfb-ct-embed-preview__edit"
							>
								{ __( 'Edit', 'sfb-comparison-tables' ) }
							</a>
						</div>
						<ServerSideRender
							block="sfb/ct-embed"
							attributes={ attributes }
							EmptyResponsePlaceholder={ () => (
								<p className="sfb-ct-embed-preview__empty">
									{ __( 'Table preview not available. The table may be empty.', 'sfb-comparison-tables' ) }
								</p>
							) }
						/>
					</div>
				) }
			</div>
		</>
	);
}
