<?php
/**
 * Meta Box - Documentation Types
 */

$args = \SimpleDocumentation\Utilities\Loader::get_current_component_args();
$post = $args['post'];

?>
Types <?= $post->ID; ?>
