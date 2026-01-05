/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText, InnerBlocks } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { title } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<div className="sfb-accordion-item__header-editor">
				<RichText
					tagName="div"
					className="sfb-accordion-item__title-editor"
					value={ title }
					onChange={ ( value ) => setAttributes( { title: value } ) }
					placeholder={ __( 'Enter question...', 'smallfishbusiness' ) }
					allowedFormats={ [] }
				/>
				<span className="sfb-accordion-item__icon-editor">▼</span>
			</div>
			<div className="sfb-accordion-item__content-editor">
				<InnerBlocks
					template={ [
						[
							'core/paragraph',
							{
								placeholder: __( 'Enter answer...', 'smallfishbusiness' ),
							},
						],
					] }
				/>
			</div>
		</div>
	);
}
