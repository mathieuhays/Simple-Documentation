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
		<ol class="sd-list">';

	while ( $iterator->have_items() ) {
		$item = $iterator->the_item();
		/**
		 * @TODO display edit button only if user has the right capibility
		 */

		printf(
			'<li class="sd-list__item sd-doc sd-doc--list-item">
				<a href="%s" class="sd-doc__title">%s</a>
				<div class="sd-doc__actions">
					<a href="%s">Edit</a>
				</div>
			</li>',
			$item->get_view_link(),
			$item->get_title(),
			$item->get_edit_link()
		);
	}

	echo '</ol>';
}

/**
 * @TODO handle pagination (js async ideally with browser history mutation)
 */
