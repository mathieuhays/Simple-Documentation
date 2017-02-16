<?php
/**
 *  Plugin Name: Simple Documentation
 *  Plugin URI: https://mathieuhays.co.uk/simple-documentation/
 *  Description: Plugin description here... @TODO
 *  Version: 2.0.0-alpha
 *  Author: Mathieu Hays
 *  Author URI: https://mathieuhays.co.uk
 *  License: GPL2
 *  Text Domain: simple-documentation
 *  Domain Path: /languages
 *  Copyright: Mathieu Hays
 */

require_once 'constants.php';

require_once SIMPLEDOC_INCLUDES_DIR . '/class-core.php';

// Start Plugin
\SimpleDocumentation\Core::get_instance()->bootstrap();
