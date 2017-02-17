<?php
/**
 *  Simple Documentation -- List Component
 */

use \SimpleDocumentation\Utilities\Iterators;

$iterator = Iterators::get_instance()->get();

if ( ! $iterator->have_items() ) {
	echo 'No documentation available at the moment.';
} else {
	echo '
		<h2>Full list:</h2>
		<ol>';

	while ( $iterator->have_items() ) {
		$item = $iterator->the_item();
		$type = $item->get_type();
		$type_icon = ! empty( $type ) ? $type->get_icon() : '';

		printf(
			'<li>
				%s %s
				<a href="%s">(View)</a>
				-
				<a href="%s">(Edit)</a>
			</li>',
			$type_icon,
			$item->get_title(),
			$item->get_view_link(),
			$item->get_edit_link()
		);
	}

	echo '</ol>';
}
