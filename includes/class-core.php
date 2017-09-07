<?php

namespace SimpleDocumentation;

use SimpleDocumentation\Models\Documentation_Item;

class Core {

	const SLUG = 'simple-documentation';


	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		$this->load_files();
		$this->load_textdomain();

		add_filter( 'plugin_action_links_' . plugin_basename( SIMPLEDOC_ROOT_DIR . '/client-documentation.php' ), [ $this, 'add_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_row_meta' ], 10, 2 );
	}


	/**
	 *  Load Plugin Files & Bootstrap classes when necessary
	 */
	public function load_files() {
		// Utilities
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-post-type-column-helper.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterator.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterators.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-loader.php';

		// Models
		require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-post-type-item.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-taxonomy-item.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-user.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/models/class-documentation-item.php';

		require_once SIMPLEDOC_INCLUDES_DIR . '/class-dashboard.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-edit-screen.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-export.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-import.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-plugin-page.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-settings.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-upgrade.php';

		/**
		 *  Load bootstraps when relevant
		 */

		// Models (register)
		Documentation_Item::bootstrap();

		// Getters & Setters for the plugin's settings
		Settings::instance()->bootstrap();

		// Upgrade - handle data structure conversion from previous versions
		Upgrade::instance()->bootstrap();

		// Dashboard
		Dashboard::instance()->bootstrap();

		// Plugin Page
		Plugin_Page::instance()->bootstrap();

		// Customize Post Type Edit Screen
		Edit_Screen::instance()->bootstrap();

		// Import
		Import::instance()->bootstrap();

		// Export
		Export::instance()->bootstrap();
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
