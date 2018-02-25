<?php
/**
 * Simple Documentation
 */

namespace Simple_Documentation;


/**
 * Sub namespaces
 */
require_once 'models/namespace.php';


/**
 * Classes
 */
require_once 'class-simple-documentation.php';


/**
 * Functions
 */
require_once 'database.php';


/**
 * Bootstrap
 */
function bootstrap() {
	maybe_setup_db();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );


/**
 * Legacy initialisation
 */
new Simple_Documentation();
