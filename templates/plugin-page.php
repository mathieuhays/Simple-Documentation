<?php
/**
 *  Simple Documentation -- Main Plugin Page Template
 */

use \SimpleDocumentation\Utilities\Iterator;
use \SimpleDocumentation\Utilities\Iterators;
use \SimpleDocumentation\Utilities\Loader;
use \SimpleDocumentation\DocumentationItems\DocumentationItems;
use \SimpleDocumentation\DocumentationItems\DocumentationItem;
use \SimpleDocumentation\PluginPage;

$plugin_page = PluginPage::get_instance();

// Create an iterators for our documentation items
$iterator = new Iterator(
    DocumentationItems::get_instance()->query([
        'posts_per_page' => -1
    ])
);

// Register iterator as our current one.
Iterators::get_instance()->setup( $iterator );


?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?= __('Documentation', 'simple-documentation') ?></h1>
    <a href="<?= esc_attr( $plugin_page->get_permalink() ) ?>" class="page-title-action">View list</a>
    <a href="<?= esc_attr( $plugin_page->get_add_new_link() ) ?>" class="page-title-action">Add New</a>

    <?php

    if ($plugin_page->is_single()) {
        $documentation_id = $plugin_page->get_documentation_id();
        $item = new DocumentationItem( $documentation_id );
        $item_type = $item->get_type();

        /**
         *  @TODO Maybe check for custom component in theme in case the user
         *  wants to define a custom layout here.
         */
        if ( empty( $item_type ) ||
            Loader::component( sprintf( 'single-%s', $item_type->get_slug() ) ) === false ) {
            /**
             *  Load Default single component if a type-specific one doesn't exist.
             */
            Loader::component( 'single' );
        }
    } else {
        /**
         *  @TODO Maybe check for custom component in theme in case the user
         *  wants to define a custom layout here.
         */
        Loader::component( 'highlight' );

        Loader::component( 'list' );
    }

    ?>
</div>
<?php

// Reset to previous iterator if necessary
Iterators::get_instance()->reset();
