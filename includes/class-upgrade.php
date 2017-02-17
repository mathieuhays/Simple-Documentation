<?php
/**
 *  Simple Documentation - Upgrade
 *  handle migration from previous versions (v1.x.x => v2.0.0)
 */

namespace SimpleDocumentation;

class Upgrade {
	/**
	 *  @var Upgrade singleton instance
	 */
	private static $instance;


	/**
	 *	Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  @TODO
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
		 *  @TODO
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
		 *  @TODO
		 *  - Detect version of the data structure used
		 *  - Rename/move/delete relevant options
		 *  - Delete legacy options entry
		 *  - Update data structure version
		 */
	}


	/**
	 *  Migrate Documentation Item from the legacy custom tables to the new
	 *  structure which uses a custom post type instead for better support
	 *  accross environments and languages
	 */
	public function migrate_table_data() {
		/**
		 *  @TODO
		 *  - Detect Table structure version
		 *  - move items making possible empty metas are filled with defaults
		 *    when relevant
		 *  - Delete custom tables when migration done
		 */
	}


	/**
	 *  Get Instance
	 *
	 *  @return Upgrade singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
