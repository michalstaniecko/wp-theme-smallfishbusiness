/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InnerBlocks,
} from '@wordpress/block-editor';

/**
 * Editor styles
 */
import './editor.scss';

/**
 * Allowed blocks for answer content
 */
const ALLOWED_BLOCKS = [
	'core/paragraph',
	'core/heading',
	'core/list',
	'core/image',
	'core/quote',
];

/**
 * Block template - default answer structure
 */
const TEMPLATE = [
	[
		'core/paragraph',
		{
			placeholder: __( 'Enter your answer here...', 'smallfishbusiness' ),
		},
	],
];

/**
 * Edit component for FAQ Item block
 *
 * @param {Object} props Block properties
 * @return {Element} Element to render
 */
export default function Edit( { attributes, setAttributes } ) {
	const { question } = attributes;
	const blockProps = useBlockProps( {
		className: 'sfb-faq-item',
	} );

	return (
		<div { ...blockProps }>
			<div className="sfb-faq-item__question">
				<RichText
					tagName="div"
					className="sfb-faq-item__question-text"
					value={ question }
					onChange={ ( value ) =>
						setAttributes( { question: value } )
					}
					placeholder={ __(
						'Enter question...',
						'smallfishbusiness'
					) }
					allowedFormats={ [] }
				/>
			</div>

			<div className="sfb-faq-item__answer">
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</div>
		</div>
	);
}
