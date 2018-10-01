<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_roles, $wpdb;

$items_per_page = $this->settings['item_per_page'];
$current_user   = wp_get_current_user();

/**
 * @TODO tailor request to widget needs
 */
$entries = \Simple_Documentation\Models\Documentation::get_page( 1 );

$final_entries = [];

foreach ( $entries as $documentation ) {
	if ( $documentation->user_has_access( $current_user ) ) {
		$final_entries[] = $documentation;
	}
}

$pages_count = floor( count( $final_entries ) / $items_per_page );

if ( ( count($final_entries) % $items_per_page) > 0 ) {
	$pages_count++;
}

$current_page = isset( $_GET['sd'] ) ? intval( $_GET['sd'] ) : 1;

?>
<div id="simpledocumentation_inside">
	<?php

	if ( count( $final_entries ) > 0 ) {
		?>
		<div class="widget_header">
			<h4><?php echo htmlspecialchars(stripslashes($this->settings['label_welcome_title'])); ?></h4>
			<div class="welcome_message">
				<?php echo htmlspecialchars(stripslashes($this->settings['label_welcome_message'])); ?>
			</div>
		</div>
		<ul class="list_doc" id="simpledoc_list">
			<?php

			$page_index = $current_page - 1;

			/**
			 * @var \Simple_Documentation\Models\Documentation[] $page_entries
			 */
			$page_entries = array_slice(
				$final_entries,
				$page_index * $items_per_page,
				$items_per_page
			);

			foreach ( $page_entries as $index => $documentation ) {
				$id = $documentation->get_id();
				$type = $documentation->get_type();
				$icon = $this->icon( $type );
				$title = $documentation->get_title();
				$content = $documentation->get_content();
				$url = null;

				if ( $type === \Simple_Documentation\Models\Documentation_Type::FILE ) {
					$attachment_id = $documentation->get_attachment_id();

					// File from import
					if ( empty( $attachment_id ) ) {
						$url = $content;
					} else {
						$url = wp_get_attachment_url( $attachment_id );
					}
				}

				if ( $type == \Simple_Documentation\Models\Documentation_Type::LINK ) {
					$url = $content;
				}

				if ( ! empty( $url ) ) {
					$title = sprintf(
						'<a href="%s">%s</a>',
						esc_url( $url ),
						$title
					);
				}

				$expand_excluding_types = [
					\Simple_Documentation\Models\Documentation_Type::FILE,
					\Simple_Documentation\Models\Documentation_Type::LINK,
				];

				$expand = '';

				if ( ! in_array( $type, $expand_excluding_types ) ) {
					$expand = "
						<div class='el_expand'>
							{$content}
						</div>";
				}

				echo "
				<li id='simpledoc_{$id}' class='smpldoc_li'>
					<div class='el_front' data-id='{$id}' data-order='{$index}'>
						<span class='el_front_bf'>
							<i class='fa fa-{$icon}'></i>
						</span>
						<span class='el_title'>
							{$title}
						</span>
					</div>
					{$expand}
				</li>";
			}

			?>
		</ul>
		<div class="widget_footer">
			<?php if ( $pages_count > 0 ): ?>
				<nav>
					<?php _e('Pages', 'client-documentation' ); ?>:
				<?php
					for ( $i = 1; $i <= $pages_count; $i++ ) {
						$class = ( $i == $current_page ) ? " class='active'" : '';
						echo "<a href='?sd=$i'$class>$i</a>";
					}
				?>
				</nav>
			<?php endif; ?>
		</div>
		<?php
	} else {
		echo "<p>" . __('No Documentation available yet.', 'client-documentation' ) . "</p>";
	}
	
	?>
</div>
