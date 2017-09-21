(function($) {
	'use strict';

	$(document).ready(function() {
		// Handle Section Toggle
		toggleElement($('.js-sd-section-toggle'), 'sd-section--retracted', true);
	});

	function toggleElement($elements, className, startToggled, customTriggerClass) {
		if ( ! $elements.length ) {
			return;
		}

		$elements.each(function() {
			var $element = $(this),
				triggerClass = customTriggerClass || 'js-sd-toggle-trigger',
				$trigger = $element.find('.' + triggerClass);

			if ( startToggled ) {
				$element.addClass(className);
			}

			$trigger.on('click', function() {
				$element.toggleClass(className);
			});
		});
	}
})(jQuery);
