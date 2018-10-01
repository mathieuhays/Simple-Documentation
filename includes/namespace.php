<?php
/**
 * Simple Documentation
 */

namespace Simple_Documentation;


/**
 * Sub-namespaces
 */
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/utilities/namespace.php';
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/models/namespace.php';

/**
 * Classes
 */
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/class-simple-documentation.php';
//require_once SIMPLE_DOCUMENTATION_INCLUDES . '/class-export.php';


/**
 * Functions
 */
require_once SIMPLE_DOCUMENTATION_INCLUDES . '/functions.php';


/**
 * Bootstrap
 */
Simple_Documentation::bootstrap();
