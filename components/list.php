<?php
/**
 *  Simple Documentation -- List Component
 */

?>

<div id="poststuff" class="postbox-container postbox-container--simple-documentation">
	<?php

	/**
	 * Load Posts Meta Boxes
	 */
	$sample_data = [
		[
			'title' => 'Get Started',
			'items' => [
				'Vestibulum id ligula porta felis euismod semper',
				'Tellus Sem Adipiscing Aenean Tristique',
				'Vulputate Tellus Mollis',
				'Mollis Nibh Mattis Fringilla',
			],
		],
		[
			'title' => 'Events',
			'items' => [
				'Curabitur blandit tempus porttitor',
				'Bibendum Vulputate Ultricies Magna Tortor',
				'Ridiculus Ipsum Pellentesque Justo',
				'Aenean Bibendum Malesuada Justo Dapibus',
			],
		],
		[
			'title' => 'Form Applications',
			'items' => [
				'Curabitur blandit tempus porttitor',
				'Bibendum Vulputate Ultricies Magna Tortor',
				'Ridiculus Ipsum Pellentesque Justo',
				'Aenean Bibendum Malesuada Justo Dapibus',
			],
		],
		[
			'title' => 'Support / Help',
			'items' => [
				'Curabitur blandit tempus porttitor',
				'Bibendum Vulputate Ultricies Magna Tortor',
				'Ridiculus Ipsum Pellentesque Justo',
				'Aenean Bibendum Malesuada Justo Dapibus',
			],
		],
	];

	/**
	 * Register Meta boxes
	 */
	foreach ( $sample_data as $index => $data ) {
		add_meta_box(
			'simple-doc-meta-' . $index,
			$data['title'],
			function( $post, $options ) {
				$args = $options['args'];

				?>
				<ul>
					<?php foreach ( $args['items'] as $item ) : ?>
						<li>
							<span class="dashicons dashicons-editor-alignleft"></span>
							<?php echo $item; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
			},
			\SimpleDocumentation\Plugin_Page::instance()->get_slug(),
			'list',
			'default',
			$data
		);
	}

	/**
	 * Render Meta Boxes
	 */
	ob_start();
	do_meta_boxes( \SimpleDocumentation\Plugin_Page::instance()->get_slug(), 'list', null );
	$meta_boxes = ob_get_contents();
	ob_end_clean();

	/**
	 * Remove Sortable class to disable Drag&Drop functionality as we don't support it yet
	 */
	$meta_boxes = str_replace( 'meta-box-sortables', '', $meta_boxes );

	echo $meta_boxes;

	?>
</div>
