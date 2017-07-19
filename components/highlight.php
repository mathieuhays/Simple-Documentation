<?php
/**
 *  Simple Documentation -- Plugin Page Highlight Component
 */
?>
<div class="sp-grid">
	<h2>Highlight</h2>
	<?php

	for ( $i = 0; $i < 4; $i++ ):

	?>
	<div class="sp-grid__item">
		<a href="#" class="sp-doc">
			<div class="sp-doc__preview">
				<img src="http://lorempixel.com/400/250/business?v=<?php echo $i; ?>" class="sp-doc__img" alt="test">
			</div>
			<div class="sp-doc__header">
				<h3 class="sp-doc__title">
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
