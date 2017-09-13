<?php
/**
 *  Simple Documentation - Upgrade
 *  handle migration from previous versions (v1.x.x => v2.0.0)
 */

namespace SimpleDocumentation;

class Upgrade {
	/**
	 * Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  @TODO implement bootstrap
		 *  if should_upgrade >> migrate()
		 */
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
	 */
	public function migrate_options() {
		/**
		 *  @TODO implement migrate_options
		 *  - Detect version of the data structure used
		 *  - Rename/move/delete relevant options
		 *  - Delete legacy options entry
		 *  - Update data structure version
		 */
	}


	/**
	 *  Migrate Documentation Item from the legacy custom tables to the new
	 *  structure which uses a custom post type instead for better support
	 *  across environments and languages
	 */
	public function migrate_table_data() {
		/**
		 *  @TODO implement migrate_table_data
		 *  - Detect Table structure version
		 *  - move items making possible empty metas are filled with defaults
		 *    when relevant
		 *  - Delete custom tables when migration done
		 */
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
