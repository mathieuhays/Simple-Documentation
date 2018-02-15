<?php
/**
 * Simple Documentation
 * Test database related functions
 */

namespace Simple_Documentation;


class Test_Database extends \WP_UnitTestCase {

	public function test_is_installed() {
		/**
		 * @TODO implement is_installed() test
		 *
		 * - Not installed yet
		 * - Fully installed
		 * - V1 not upgraded yet (maybe)
		 * - V2 not upgraded yet (maybe)
		 */
	}


	public function test_get_db_version() {
		/**
		 * @TODO implement get_db_version() tests
		 *
		 * - Test empty install (db not yet installed)
		 * - Test V1
		 * - Test V2
		 * - Test V2 after being upgraded from V1
		 * - Test V3
		 */
	}


	public function test_create_table() {
		/**
		 * @TODO implement create_table() test
		 */
	}


	public function test_upgrade_db_from_v1() {
		/**
		 * @TODO implement upgrade_db_from_v1() tests
		 */
	}


	public function test_upgrade_db_from_v2() {
		/**
		 * @TODO implement upgrade_db_from_v2() tests
		 *
		 * - Test for fresh v2 installs
		 * - Test for upgraded v2 (int(5) when upgrading from v1)
		 */
	}


	public function test_maybe_setup_db() {
		/**
		 * @TODO implement maybe_setup_db() tests
		 *
		 * - Test fresh install
		 * - Test on fully setup installs
		 * - Test on V1 installs
		 * - Test on V2 fresh installs
		 * - Test on V2 upgraded installs
		 */
	}
}
