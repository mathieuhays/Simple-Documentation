(function($) {
	'use strict';

	$(document).ready(function() {
		var $metaboxContainer = $('.postbox-container--simple-documentation');

		closeMetaBoxes( $metaboxContainer, true );
		handleMetaboxAutoClose( $metaboxContainer );
	});

	/**
	 * Close all meta boxes
	 *
	 * @param {jQuery} $container
	 * @param {bool} ignore_first
	 */
	function closeMetaBoxes( $container, ignore_first ) {
		$container.find('.postbox').each(function(index) {
			if ( ignore_first && index === 0 ) {
				return true;
			}

			$(this).addClass('closed');
		});
	}

	/**
	 * Close any opened meta box if we're opening a new one.
	 *
	 * @param {jQuery} $container
	 */
	function handleMetaboxAutoClose( $container ) {
		$container.on('click', '.hndle,.handlediv', function() {
			var $button = $(this),
				$postBox = $button.parents('.postbox');

			if ( ! $postBox.hasClass('closed') ) {
				$container.find('.postbox').each(function() {
					if ( $(this).attr('id') === $postBox.attr('id') ) {
						return true;
					}

					$(this).addClass('closed');
				});
			}
		});
	}
})(jQuery);
