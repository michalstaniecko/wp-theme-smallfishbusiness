/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	RichText,
} from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

/**
 * Editor styles
 */
import './editor.scss';

/**
 * Allowed blocks - only FAQ items
 */
const ALLOWED_BLOCKS = [ 'sfb/faq-item' ];

/**
 * Block template - default structure
 */
const TEMPLATE = [
	[
		'sfb/faq-item',
		{
			question: __( 'What is your question?', 'smallfishbusiness' ),
		},
	],
];

/**
 * Edit component for FAQ block
 *
 * @param {Object} props Block properties
 * @return {Element} Element to render
 */
export default function Edit( { attributes, setAttributes } ) {
	const { title, showTitle } = attributes;
	const blockProps = useBlockProps( {
		className: 'wp-block-sfb-faq',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'FAQ Settings', 'smallfishbusiness' ) }>
					<ToggleControl
						label={ __( 'Show title', 'smallfishbusiness' ) }
						checked={ showTitle }
						onChange={ ( value ) =>
							setAttributes( { showTitle: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ showTitle && (
					<RichText
						tagName="h2"
						className="sfb-faq__title"
						value={ title }
						onChange={ ( value ) =>
							setAttributes( { title: value } )
						}
						placeholder={ __(
							'Enter FAQ title...',
							'smallfishbusiness'
						) }
					/>
				) }

				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
					renderAppender={ InnerBlocks.ButtonBlockAppender }
				/>
			</div>
		</>
	);
}
