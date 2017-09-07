<?php
/**
 *  Simple Documentation -- Single View Component
 */

$documentation_id = \SimpleDocumentation\Plugin_Page::instance()->get_documentation_id();
$item = \SimpleDocumentation\Models\Documentation_Item::from_id( $documentation_id );

?>
<div>
	<h1><?php echo $item->get_title() ?></h1>

	<div>
		<?php echo $item->get_content() ?>
	</div>
</div>
