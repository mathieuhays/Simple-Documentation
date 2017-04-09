/**
 *  Simple Documentation - Edit Screen
 *  Handle JS on post.php && post-new.php (when creating/editing items)
 */
(function($, window) {
    'use strict';

    var $types,
    	$formType;

    function onReady() {
        $types = $('.js-simpledoc-meta-box');
        $formType = $('#simpledocumentation_type');

        initTypeSelector();
        handleTypeSelector();
    }

	/**
	 * Initialise Type Selector. Hide all
	 */
	function initTypeSelector() {
    	if ( ! ( 'simpleDocumentationEditScreen' in window ) ) {
    		return;
		}

		// hide all boxes
		$types.hide();

    	var types = simpleDocumentationEditScreen.metaboxes || [],
    		current = simpleDocumentationEditScreen.current_type || 'note';

    	$.each( types, function( index, type ) {
    		var $type = $('.js-simpledoc-meta-box--' + type );

			if ( $type.length ) {

    			// Show the current type's box
    			if ( type === current ) {
    				$type.show();
    				$formType.val( type );
				}
			}
		});
	}

    function handleTypeSelector() {
    	var $buttons = $('.js-edit-type-selector');

    	if ( ! $buttons.length ) {
    		return false;
		}

		$buttons.on( 'click', function() {
			var $button = $(this),
				type = $button.attr('data-type');

			// reset all
			$buttons.parent().removeClass('selected');
			$types.hide();

			// update state of selected type
			$('.js-simpledoc-meta-box--' + type).show();
			$button.parent().addClass('selected');
			$formType.val(type);
		});
	}

    $(document).ready(onReady);

})(jQuery, window);
