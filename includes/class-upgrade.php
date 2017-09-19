<?php
/**
 *  Simple Documentation - Upgrade
 *  handle migration from previous versions (v1.x.x => v2.0.0)
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Models\Documentation_Type;

class Upgrade {
	/**
	 * Bootstrap
	 */
	public function bootstrap() {
		// Use init hook to ensure we registered every post types / terms
		add_action( 'init', [ $this, 'maybe_setup_plugin_data' ] );
	}

	/**
	 * Setup plugin data if migration is need or the plugin has just been installed
	 */
	public function maybe_setup_plugin_data() {
		if ( $this->should_upgrade() ) {
			$this->migrate_options();
			$this->migrate_table_data();
		}

		if ( $this->is_first_load() ) {
			$this->load_initial_data();
		}
	}

	/**
	 *  Whether we detected the existing data format is from a previous version
	 *  of the plugin and requires upgrade, or not.
	 *
	 *  @return bool
	 */
	public function should_upgrade() {
		/**
		 *  @TODO implement should_upgrade
		 *  - Check for old option structure using get_option and the version number
		 *  - Check if previous version's table exists
		 */

		return false;
	}

	/**
	 *  Migrate Legacy Options to the current data structure for settings
	 *
	 * @return bool
	 */
	public function migrate_options() {
		/**
		 *  @TODO implement migrate_options
		 *  - Detect version of the data structure used
		 *  - Rename/move/delete relevant options
		 *  - Delete legacy options entry
		 *  - Update data structure version
		 */

		return false;
	}

	/**
	 *  Migrate Documentation Item from the legacy custom tables to the new
	 *  structure which uses a custom post type instead for better support
	 *  across environments and languages
	 *
	 * @return bool
	 */
	public function migrate_table_data() {
		/**
		 *  @TODO implement migrate_table_data
		 *  - Detect Table structure version
		 *  - move items making possible empty metas are filled with defaults
		 *    when relevant
		 *  - Delete custom tables when migration done
		 */

		return false;
	}

	/**
	 * Whether the current load is the first one or not.
	 *
	 * @return bool
	 */
	public function is_first_load() {
		return false;
	}

	/**
	 * Setup basic data so the user have some data to play with and understand the plugin better.
	 */
	public function load_initial_data() {
		// Insert default documentation types
		array_map( [ Documentation_Type::class, 'insert' ], [ 'Note', 'File', 'Link', 'Video' ] );

		// Default options
		if ( is_multisite() ) {
			//.
		}
	}

	/**
	 * @return Upgrade
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
