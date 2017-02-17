<?php
/**
 *  Simple Documentation - Type Selector
 *  Used on the edit screen
 */

use \SimpleDocumentation\Utilities\Iterators;
use \SimpleDocumentation\Utilities\Iterator;
use \SimpleDocumentation\DocumentationItems\DocumentationItem;
use \SimpleDocumentation\DocumentationItems\DocumentationTypes;

$types = DocumentationTypes::get_instance()->get_all();
$iterator = Iterators::get_instance()->setup( new Iterator( $types ) );
$item = new DocumentationItem;

if ( $iterator->have_items() ) {
	echo '<ol class="simpledoc-type-selector">';

	while ( $iterator->have_items() ) {
		$type = $iterator->the_item();
		$selected = $item->has_type( $type );

		printf(
			'<li class="simpledoc-type-selector__item %s %s">
				<button type="button" class="simpledoc-type-selector__button">
					%s %s
				</button>
			</li>',
			$iterator->current_item_is_first() ? 'first' : '',
			$selected ? 'selected' : '',
			$type->get_icon(),
			$type->get_label()
		);
	}

	echo '</ol>';
}
