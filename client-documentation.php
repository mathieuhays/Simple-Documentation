<?php
/**
 * Plugin Name: Simple Documentation
 * Plugin URI: https://mathieuhays.co.uk/simple-documentation/
 * Description: This plugin helps webmasters/developers to provide documentation through the wordpress dashboard.
 * Version: 1.2.6
 * Author: Mathieu Hays
 * Author URI: https://mathieuhays.co.uk
 * License: GPL2
 * Text Domain: client-documentation
 * Domain Path: /languages
 */

require_once 'constants.php';

require_once 'includes/namespace.php';

register_activation_hook(
	__FILE__,
	[ \Simple_Documentation\Simple_Documentation::get_instance(), 'setup_tables' ]
);

register_uninstall_hook(
	__FILE__,
	[ \Simple_Documentation\Simple_Documentation::class, 'uninstall' ]
);
