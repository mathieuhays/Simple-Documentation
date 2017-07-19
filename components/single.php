<?php
/**
 *  Simple Documentation -- Single View Component
 */

use \SimpleDocumentation\PluginPage;

$documentation_id = PluginPage::get_instance()->get_documentation_id();
$item = \SimpleDocumentation\DocumentationItem::get( $documentation_id );

?>
<div>
	<h1><?php echo $item->get_title() ?></h1>

	<div>
		<?php echo $item->get_content() ?>
	</div>
</div>
