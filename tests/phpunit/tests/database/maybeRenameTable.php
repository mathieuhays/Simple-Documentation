<?php

class Tests_Database_MaybeRenameTable extends SimpleDocumentation_DB_UnitTestCase {
	/**
	 * @return bool
	 */
	protected function _renamed_table_exists() {
		global $wpdb;

		return $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}simpledocumentation'" ) === 1;
	}

	/**
	 * @return bool
	 */
	protected function _legacy_table_exists() {
		global $wpdb;

		return $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}clientDocumentation'" ) === 1;
	}

	public function test_rename_from_v1_install() {
		global $wpdb;

		$wpdb->query(
			"
			CREATE TABLE `{$wpdb->prefix}clientDocumentation` (
			ID bigint(20) NOT NULL auto_increment,
			type varchar(200) NOT NULL default 'note',
			title varchar(255) NOT NULL default 'New document',
			content text NOT NULL,
			etoile_b tinyint(1) NOT NULL default 0,
			etoile_t datetime,
			PRIMARY KEY (ID) );"
		);

		// We rely on this being set
		add_site_option( 'clientDocumentation_table', $wpdb->prefix . 'clientDocumentation' );

		// function we're testing
		\Simple_Documentation\maybe_rename_table();

		// Test that the table has been renamed
		$this->assertTrue( $this->_renamed_table_exists() );
		$this->assertFalse( $this->_legacy_table_exists() );

		/**
		 * Cleanup
		 * We've to do it manually because we're not relying on the WordPress UnitTestCase that cleans that up
		 * automatically since it prevent up to test the table manipulation.
		 * Our custom table is cleaned-up though.
		 */
		delete_site_option( 'clientDocumentation_table' );
	}

	public function test_rename_from_upgraded_v2_install() {
		global $wpdb;

		// Initial v1 version
		$wpdb->query(
			"
			CREATE TABLE `{$wpdb->prefix}clientDocumentation` (
			ID bigint(20) NOT NULL auto_increment,
			type varchar(200) NOT NULL default 'note',
			title varchar(255) NOT NULL default 'New document',
			content text NOT NULL,
			etoile_b tinyint(1) NOT NULL default 0,
			etoile_t datetime,
			PRIMARY KEY (ID) );"
		);

		// db v2 upgrade ( with wrong field formats :x )
		$wpdb->query(
			"ALTER TABLE `{$wpdb->prefix}clientDocumentation`
			ADD COLUMN restricted varchar(500),
			ADD COLUMN attachment_id int(5),
			ADD COLUMN ordered int(5);"
		);

		add_site_option( 'simpledocumentation_main_settings', [
			'table' => $wpdb->prefix . 'clientDocumentation',
		] );

		// Test
		\Simple_Documentation\maybe_rename_table();

		// Assertion
		$this->assertFalse( $this->_legacy_table_exists() );
		$this->assertTrue( $this->_renamed_table_exists() );

		// Cleanup
		delete_site_option( 'simpledocumentation_main_settings' );
	}

	public function test_rename_from_fresh_v2_install() {
		global $wpdb;

		$wpdb->query(
			"CREATE TABLE `{$wpdb->prefix}simpledocumentation` (
				ID bigint(20) NOT NULL auto_increment,
				type varchar(200) NOT NULL default 'note',
				title varchar(255) NOT NULL default 'New document',
				content text NOT NULL,
				etoile_b tinyint(1) NOT NULL default 0,
				etoile_t datetime,
				restricted varchar(500),
				attachment_id bigint(20),
				ordered bigint(20),
				UNIQUE KEY ID (ID)
			);"
		);

		add_site_option( 'simpledocumentation_main_settings', [
			'table' => $wpdb->prefix . 'simpledocumentation',
		] );

		// Test
		\Simple_Documentation\maybe_rename_table();

		// Assertion
		$this->assertTrue( $this->_renamed_table_exists() );
		$this->assertFalse( $this->_legacy_table_exists() );

		// Cleanup
		delete_site_option( 'simpledocumentation_main_settings' );
	}

	public function test_should_not_rename_on_new_install() {
		// No DB set yet.

		// Test
		\Simple_Documentation\maybe_rename_table();

		// Assertion
		$this->assertFalse( $this->_legacy_table_exists() );
		$this->assertFalse( $this->_renamed_table_exists() );
	}

	public function test_should_not_rename_v3_install() {
		// Latest DB fully installed.

		// Setup
		\Simple_Documentation\create_table();

		// Test
		\Simple_Documentation\maybe_rename_table();

		// Assertion
		$this->assertFalse( $this->_legacy_table_exists() );
		$this->assertTrue( $this->_renamed_table_exists() );
	}
}
