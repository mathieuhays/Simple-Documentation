<?php
/**
 * Simple Documentation
 */

namespace SimpleDocumentation;

/**
 * Bootstrap our controllers
 */
function bootstrap_controllers() {
	Dashboard::instance()->bootstrap();
	Edit_Screen::instance()->bootstrap();
	Export::instance()->bootstrap();
	Import::instance()->bootstrap();
	Plugin_Page::instance()->bootstrap();
	Upgrade::instance()->bootstrap();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap_controllers' );


/**
 * Load Text Domain
 */
function load_text_domain() {
	load_plugin_textdomain(
		'simple-documentation',
		false,
		str_replace( WP_PLUGIN_DIR, '', SIMPLEDOC_LANGUAGES_DIR )
	);
}
add_action( 'init', __NAMESPACE__ . '\\load_text_domain' );


/**
 * Add custom links next to `Deactivate` on plugins page.
 *
 * @param array $links
 *
 * @return array
 */
function get_plugin_action_links( $links ) {
	/**
	 * @TODO add actual action link
	 */
	$links[] = '<a href="#">Settings</a>';

	return $links;
}
add_filter(
	'plugin_action_links_' . plugin_basename( SIMPLEDOC_ROOT_DIR . '/client-documentation.php' ),
	__NAMESPACE__ . '\\get_plugin_action_links'
);


/**
 * Add custom links after `View details` on plugins page.
 *
 * @param array $links
 * @param string $file
 *
 * @return array
 */
function get_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, basename( SIMPLEDOC_ROOT_DIR ) ) !== false ) {
		/**
		 * @TODO add actual link
		 */
		$links[] = '<a href="#">Github</a>';
	}

	return $links;
}
add_filter( 'plugin_row_meta', __NAMESPACE__ . '\\get_plugin_row_meta', 10, 2 );


/**
 * Whether the plugin is properly installed or not.
 * Might just replace this with a call from upgrade class? like Upgrade::instance()->is_ready()
 *
 * @return bool
 */
function is_installed() {
	/**
	 * @TODO implement is_installed
	 */
	return false;
}
