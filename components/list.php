<?php
/**
 *  Simple Documentation -- List Component
 */

use SimpleDocumentation\Models\Documentation;
use SimpleDocumentation\Models\Documentation_Category;

$categories = Documentation_Category::get_all( true );

if ( empty( $categories ) ) {
	echo 'No categories :(';
} else {
	foreach ( $categories as $category ) {
		$items = Documentation::from_taxonomy( $category, [
			'posts_per_page' => -1,
			'order' => 'ASC',
		] );

		?>
		<div class="sd-section js-sd-section-toggle">
			<h2 class="sd-section__title">
				<button class="sd-section__trigger js-sd-toggle-trigger">
					<?php echo $category->get_name(); ?>

					<span class="sd-section__count">
						<?php

						printf(
						// translators: Documentation item count
							_n( '%d item', '%d items', count( $items ), 'simple-documentation' ),
							count( $items )
						);

						?>
					</span>
				</button>
			</h2>

			<div class="sd-section__content">
				<ul class="sd-list">
					<?php

					foreach ( $items as $item ) {
						/** @var Documentation $item */
						$type = $item->get_type();
						$icon_class = '';

						if ( ! empty( $type ) ) {
							$icon_class = $type->get_icon_class();
						}

						?>
						<li class="sd-list__item sd-entry">
							<a href="<?php echo $item->get_view_link(); ?>" class="sd-entry__link">
								<?php

								if ( ! empty( $icon_class ) ) {
									printf( '<span class="%s"></span> ', $icon_class );
								}

								echo $item->get_title();

								?>
							</a>
						</li>
						<?php
					}

					?>
				</ul>
			</div>
		</div>
		<?php
	}
}

?>
