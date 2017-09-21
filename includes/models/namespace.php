<?php
/**
 * Simple Documentation
 * Models
 */

namespace SimpleDocumentation\Models;

function bootstrap_models() {
	Documentation::register();
	Documentation_Type::register( [], Documentation::POST_TYPE );
	Documentation_Category::register( [], Documentation::POST_TYPE );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap_models' );
