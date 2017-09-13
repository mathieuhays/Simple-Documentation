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

// Load Plugin's Constant
require_once 'constants.php';

/**
 * Load Plugin's files
 */

// Utilities
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-post-type-column-helper.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterator.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterators.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-loader.php';

// Models
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-post-type.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-taxonomy.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-user.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation.php';

require_once SIMPLEDOC_INCLUDES_DIR . '/class-core.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-dashboard.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-edit-screen.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-export.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-import.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-plugin-page.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-settings.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-upgrade.php';

/**
 * Instantiate Models when relevant
 */
\SimpleDocumentation\Models\Documentation::bootstrap();

/**
 * Instantiate Classes
 */
\SimpleDocumentation\Core::instance()->bootstrap();
\SimpleDocumentation\Dashboard::instance()->bootstrap();
\SimpleDocumentation\Edit_Screen::instance()->bootstrap();
\SimpleDocumentation\Export::instance()->bootstrap();
\SimpleDocumentation\Import::instance()->bootstrap();
\SimpleDocumentation\Plugin_Page::instance()->bootstrap();
\SimpleDocumentation\Settings::instance()->bootstrap();
\SimpleDocumentation\Upgrade::instance()->bootstrap();
