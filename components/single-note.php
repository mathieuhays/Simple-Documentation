<?php
/**
 *  Simple Documentation -- Single -- Note Type
 */

use \SimpleDocumentation\DocumentationItems\DocumentationItem;
use \SimpleDocumentation\PluginPage;

$documentation_id = PluginPage::get_instance()->get_documentation_id();
$item = new DocumentationItem( $documentation_id );

?>
<div>
    <h1>Note: <?php echo $item->get_title() ?></h1>

    <div>
        <?php echo $item->get_content() ?>
    </div>
</div>