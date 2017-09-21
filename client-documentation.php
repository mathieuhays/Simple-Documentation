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
use SimpleDocumentation\Core;
use SimpleDocumentation\Dashboard;
use SimpleDocumentation\Edit_Screen;
use SimpleDocumentation\Export;
use SimpleDocumentation\Import;
use SimpleDocumentation\Plugin_Page;
use SimpleDocumentation\Upgrade;

require_once 'constants.php';

/**
 * Load Plugin's files
 */

// Utilities
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/namespace.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-debug.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterator.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterators.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-loader.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-post-type-column-helper.php';

// Models
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-base-model.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-post-type.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-taxonomy.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-user.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation-legacy.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation-category.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation-type.php';

require_once SIMPLEDOC_INCLUDES_DIR . '/class-core.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-dashboard.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-edit-screen.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-export.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-import.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-plugin-page.php';
require_once SIMPLEDOC_INCLUDES_DIR . '/class-upgrade.php';

/**
 * Instantiate Classes
 */
Core::instance()->bootstrap();
Dashboard::instance()->bootstrap();
Edit_Screen::instance()->bootstrap();
Export::instance()->bootstrap();
Import::instance()->bootstrap();
Plugin_Page::instance()->bootstrap();
Upgrade::instance()->bootstrap();
