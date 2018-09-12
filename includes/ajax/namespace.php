<?php
/**
 * Ajax
 */

namespace Simple_Documentation\Ajax;


/**
 * Classes
 */
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-base-ajax.php'; // dependency
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-delete.php';
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-edit.php';
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-export.php';
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-get.php';
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-import.php';


// Manager
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/ajax/class-ajax-manager.php';


add_action( 'plugins_loaded', function() {
	Ajax_Manager::register([
		new Ajax_Delete,
		new Ajax_Edit,
		new Ajax_Export,
		new Ajax_Get,
		new Ajax_Import,
	]);

	Ajax_Manager::bootstrap();
} );
