<?php
/**
 *  Simple Documentation -- Main Plugin Page Template
 */

use SimpleDocumentation\Documentation_Item;
use SimpleDocumentation\Utilities\Iterator;
use SimpleDocumentation\Utilities\Iterators;
use \SimpleDocumentation\Utilities\Loader;
use \SimpleDocumentation\Plugin_Page;

$plugin_page = Plugin_Page::instance();

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo __( 'Documentation', 'simple-documentation' ) ?></h1>
	<a href="<?php echo esc_attr( $plugin_page->get_permalink() ) ?>" class="page-title-action">View list</a>
	<a href="<?php echo esc_attr( $plugin_page->get_add_new_link() ) ?>" class="page-title-action">Add New</a>

	<?php

	if ( $plugin_page->is_single() ) {
		$documentation_id = $plugin_page->get_documentation_id();
		$item = Documentation_Item::from_id( $documentation_id );

		/**
		 *  Load Default single component
		 */
		Loader::component( 'single' );
	} else {
		// Create an iterators for our documentation items
		$iterator = new Iterator(
			Documentation_Item::get_page()
		);

		// Register iterator as our current one.
		Iterators::instance()->setup( $iterator );

		/**
		 * Highlight
		 */
//		Loader::component( 'highlight' );

		?>
		<div class="sd-container">
			<div class="sd-container__item sd-container__item--main">
				<?php

				Loader::component( 'list' );

				?>
			</div>
			<div class="sd-container__item sd-container__item--side">
				<?php

				Loader::component( 'sidebar' );

				?>
			</div>
		</div>
		<?php
	}

	?>
</div>
