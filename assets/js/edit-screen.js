/**
 *  Simple Documentation - Edit Screen
 *  Handle JS on post.php && post-new.php (when creating/editing items)
 */
(function($, window) {
    'use strict';

	var $attachmentUploadButton,
		$attachmentField,
		$attachmentContainer,
		fileFrame;

    function onReady() {
		$attachmentUploadButton = $('.js-sd-add-attachment');
		$attachmentField = $('input[name="sd_attachments"]');
        $attachmentContainer = $('.js-sd-attachment-list');

        if ( $attachmentField.length ) {
        	handleAttachmentUpload();
		}
    }

	function handleAttachmentUpload() {
		fileFrame = wp.media.frames.file_frame = wp.media({
			multiple: false
		});

		fileFrame.on('select', function() {
			var attachment = fileFrame.state().get('selection').first().toJSON(),
				$renderedAttachment = renderAttachmentItem(attachment),
				ids = getAttachmentIds($attachmentField);

			// Render new attachment in list
			$attachmentContainer.append($renderedAttachment);

			ids.push(attachment.id);

			setAttachmentIds($attachmentField, ids);
		});

		$attachmentUploadButton.on('click', function(e) {
			e.preventDefault();
			fileFrame.open();
		});

		$attachmentContainer.on('click', '.js-sd-attachment-remove', function(e) {
			e.preventDefault();

			var $button = $(this),
				$attachment = $button.parent().parent(),
				attachmentID = $attachment.attr('data-id'),
				ids = getAttachmentIds($attachmentField);

			// Remove id from list
			ids = ids.filter(function(id) {
				return id !== attachmentID
			});

			setAttachmentIds($attachmentField, ids);

			$attachment.remove();
		});
	}

	/**
	 * Prepend namespace to string
	 *
	 * @param {string} className
	 * @param {string} mode
	 * @returns {string}
	 */
	function namespaceIt(className, mode) {
    	var namespace = 'sd-';

    	if (window.simpleDocumentation && window.simpleDocumentation.namespace) {
    		namespace = window.simpleDocumentation.namespace;
		}

		if (mode === 'js') {
    		namespace = 'js-' + namespace;
		}

		return namespace + className;
	}

	/**
	 * Render HTML for given attachment
	 *
	 * @param {Object} attachment
	 * @returns {jQuery}
	 */
	function renderAttachmentItem(attachment) {
		var $wrapper = $('<div />'),
			$filename = $('<h3 />'),
			$description = $('<div />'),
			$actions = $('<div />'),
			$viewAction = $('<a target="_blank" />'),
			$removeAction = $('<button type="button" />');

		/**
		 * Attachment Object (not all props though)
		 *
		 * attachment
		 * .author -- int - ID
		 * .authorName -- string
		 * .caption -- string
		 * .date
		 * .dateFormatted -- string
		 * .description -- string
		 * .editLink -- string
		 * .filename -- string
		 * .filesizeHumanReadable -- string - ex: 7 KB
		 * .icon -- string
		 * .id
		 * .meta
		 * .mime -- string -- ex application/pdf
		 * .name -- string
		 * .sizes
		 * 		.full
		 * 			.height
		 * 			.orientation -- string - ex: 'portrait'
		 * 			.url
		 * 			.width
		 * 		.large
		 * 		.medium
		 * 		.thumbnail
		 * .subtype -- string - ex: pdf
		 * .title
		 * .type -- string - ex: application
		 * .uploadedTo -- int - post id
		 * .url
		 */

		$wrapper.addClass(namespaceIt('attachment'));
		$wrapper.addClass(namespaceIt('attachment--' + attachment.id));

		$filename.addClass(namespaceIt('attachment__title'));
		$filename.text(attachment.filename);

		$description.addClass(namespaceIt('attachment__description'));
		$description.text(attachment.description);

		$actions.addClass(namespaceIt('attachment__actions'));

		$viewAction.text('View');
		$actions.append($viewAction);

		$removeAction.text('Remove');
		$removeAction.addClass('js-sd-attachment-remove');
		$actions.append($removeAction);

		$wrapper
			.append($filename)
			.append($description)
			.append($actions);

		console.log(attachment);

		return $wrapper;
	}

	function keepNumbers(item) {
		return !isNaN(item);
	}

	/**
	 * Set Attachment IDs to field
	 *
	 * @param {jQuery} $field
	 * @param {int[]} attachment_ids
	 */
	function setAttachmentIds($field, attachment_ids) {
		$field.val( attachment_ids.filter(keepNumbers).join(',') );
	}

	/**
	 * Get Attachments IDs
	 *
	 * @param {jQuery} $field
	 * @return {int[]}
	 */
	function getAttachmentIds($field) {
		return $field.val().split(',').map(parseInt);
	}

    $(document).ready(onReady);

})(jQuery, window);
