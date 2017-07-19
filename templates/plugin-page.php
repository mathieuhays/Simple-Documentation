<?php
/**
 *  Simple Documentation -- Main Plugin Page Template
 */

use SimpleDocumentation\DocumentationItem;
use \SimpleDocumentation\Utilities\Iterator;
use \SimpleDocumentation\Utilities\Iterators;
use \SimpleDocumentation\Utilities\Loader;
use \SimpleDocumentation\PluginPage;

$plugin_page = PluginPage::get_instance();

// Create an iterators for our documentation items
$iterator = new Iterator(
	DocumentationItem::get_page()
);

// Register iterator as our current one.
Iterators::get_instance()->setup( $iterator );


?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo __( 'Documentation', 'simple-documentation' ) ?></h1>
	<a href="<?php echo esc_attr( $plugin_page->get_permalink() ) ?>" class="page-title-action">View list</a>
	<a href="<?php echo esc_attr( $plugin_page->get_add_new_link() ) ?>" class="page-title-action">Add New</a>

	<?php

	if ( $plugin_page->is_single() ) {
		$documentation_id = $plugin_page->get_documentation_id();
		$item = DocumentationItem::get( $documentation_id );

		/**
		 *  Load Default single component
		 */
		Loader::component( 'single' );
	} else {
		/**
		 * Highlight
		 */
		Loader::component( 'highlight' );

		?>
		<div class="sp-container">
			<div class="sp-container__item sp-container__item--main">
				<?php

				Loader::component( 'list' );

				?>
			</div>
			<div class="sp-container__item sp-container__item--side">
				<?php

				Loader::component( 'sidebar' );

				?>
			</div>
		</div>
		<?php
	}

	?>
</div>
<?php

// Reset to previous iterator if necessary
Iterators::get_instance()->reset();
