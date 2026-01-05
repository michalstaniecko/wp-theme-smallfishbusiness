/**
 * Comparison Table Embed block registration.
 *
 * @package SFB\ComparisonTables
 */

import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import './editor.scss';

registerBlockType( metadata.name, {
	edit: Edit,
} );
