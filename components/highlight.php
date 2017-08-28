<?php
/**
 *  Simple Documentation -- Plugin Page Highlight Component
 */
?>
<div class="sd-grid">
	<h2>Highlight</h2>
	<?php

	for ( $i = 0; $i < 4; $i++ ):

	?>
	<div class="sd-grid__item">
		<a href="#" class="sd-doc">
			<div class="sd-doc__preview">
				<img src="http://lorempixel.com/400/250/business?v=<?php echo $i; ?>" class="sd-doc__img" alt="test">
			</div>
			<div class="sd-doc__header">
				<h3 class="sd-doc__title">
					How to publish a new product<br>
					<small>Video</small>
				</h3>
			</div>
		</a>
	</div>
	<?php

	endfor;

	?>
</div>
