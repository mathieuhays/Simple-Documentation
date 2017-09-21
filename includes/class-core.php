<?php

namespace SimpleDocumentation;

use SimpleDocumentation\Models\Documentation;
use SimpleDocumentation\Models\Documentation_Category;
use SimpleDocumentation\Models\Documentation_Type;

class Core {
	const SLUG = 'simple-documentation';

	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		$this->load_textdomain();
		$this->register_models();

		add_filter( 'plugin_action_links_' . plugin_basename( SIMPLEDOC_ROOT_DIR . '/client-documentation.php' ), [ $this, 'add_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_row_meta' ], 10, 2 );
	}

	/**
	 * @return bool
	 */
	public function is_installed() {
		// @TODO implement is_installed
		return false;
	}

	/**
	 *  Internationalise the plugin
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'simple-documentation',
			false,
			str_replace( WP_PLUGIN_DIR, '', SIMPLEDOC_LANGUAGES_DIR )
		);
	}

	public function register_models() {
		Documentation::register();
		Documentation_Type::register( [], Documentation::POST_TYPE );
		Documentation_Category::register( [], Documentation::POST_TYPE );
	}

	/**
	 * Add custom action link on plugin entry on the WordPress Plugins page.
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function add_action_links( $links ) {
		/**
		 * @TODO add actual action link
		 */
		$links[] = '<a href="#">Settings</a>';

		return $links;
	}


	/**
	 * Add Plugin row meta.
	 *
	 * @param array $links
	 * @param string $file
	 *
	 * @return array
	 */
	public function add_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, basename( SIMPLEDOC_ROOT_DIR ) ) !== false ) {
			/**
			 * @TODO add actual link
			 */
			$links[] = '<a href="#">Github</a>';
		}

		return $links;
	}


	/**
	 * @return Core
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
