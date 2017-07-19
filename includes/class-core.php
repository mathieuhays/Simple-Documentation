<?php

namespace SimpleDocumentation;

class Core {

	/**
	 *  @var Core singleton instance
	 */
	private static $instance;

	const SLUG = 'simple-documentation';


	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  Load Utility Classes & Functions
		 */
		$this->load_files();

		/**
		 *  Load Textdomain
		 */
		$this->load_textdomain();
	}


	/**
	 *  Load Plugin Files & Bootstrap classes when necessary
	 */
	public function load_files() {
		// Require all files

		// Utilities
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-post-type-column-helper.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterator.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-iterators.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/utilities/class-loader.php';

		require_once SIMPLEDOC_INCLUDES_DIR . '/class-post-type-item.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-dashboard.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-documentation-item.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-edit-screen.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-export.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-import.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-plugin-page.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-settings.php';
		require_once SIMPLEDOC_INCLUDES_DIR . '/class-upgrade.php';

		/**
		 *  Load bootstraps when relevant
		 */

		// Getters & Setters for the plugin's settings
		Settings::get_instance()->bootstrap();

		// Upgrade - handle data structure convertion from previous versions
		Upgrade::get_instance()->bootstrap();

		// Dashboard
		Dashboard::get_instance()->bootstrap();

		// Plugin Page
		PluginPage::get_instance()->bootstrap();

		// Customize Post Type Edit Screen
		EditScreen::get_instance()->bootstrap();

		// Import
		Import::get_instance()->bootstrap();

		// Export
		Export::get_instance()->bootstrap();

		// Register & Handle Documentation Item Types
		DocumentationItem::bootstrap();

		// Register && handle Documentation Items
//		DocumentationItems\DocumentationItems::get_instance()->bootstrap();
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
	 *  Get Instance
	 *
	 *  @return Core singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
