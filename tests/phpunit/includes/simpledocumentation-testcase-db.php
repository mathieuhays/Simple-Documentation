<?php
/**
 * Database test cases
 *
 * @package SimpleDocumentation
 * @subpackage UnitTests
 * @since 2.0
 */

abstract class SimpleDocumentation_DB_UnitTestCase extends PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		global $wpdb;

		$wpdb->suppress_errors = false;
		$wpdb->show_errors     = true;
		$wpdb->db_connect();
		ini_set( 'display_errors', 1 );

		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}simpledocumentation`" );
	}

	public static function tearDownAfterClass() {
		/**
		 * Re-add the expected table for the current version
		 */
		\Simple_Documentation\maybe_setup_db();
	}

	public function tearDown() {
		global $wpdb;

		/**
		 * Remove any existing test table.
		 */
		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}simpledocumentation`" );
		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}clientDocumentation`" );
	}
}
