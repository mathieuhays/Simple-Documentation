<?php
/**
 * Meta Box - Documentation Types
 */

use SimpleDocumentation\Models\Documentation_Type;

$args = \SimpleDocumentation\Utilities\Loader::get_current_component_args();

/**
 * @var \SimpleDocumentation\Models\Documentation $documentation
 */
$documentation = $args['documentation'];
$documentation_type = $documentation->get_type();

$types = Documentation_Type::get_all();

foreach ( $types as $type ) {
	$attributes = [
		'type' => 'radio',
		'name' => 'documentation-type',
		'value' => $type->get_id(),
	];

	if ( Documentation_Type::equals( $documentation_type, $type ) ) {
		$attributes['checked'] = 'checked';
	}

	?>
	<label>
		<input <?php echo \SimpleDocumentation\Utilities\inline_attributes( $attributes ); ?>>
		<?php echo $type->get_name(); ?>
	</label>
	<br>
	<?php
}
