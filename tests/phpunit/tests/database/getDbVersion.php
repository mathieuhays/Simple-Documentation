<?php

class Tests_Database_GetDbVersion extends SimpleDocumentation_DB_UnitTestCase {
	public function test_should_detect_uninstalled() {
		$this->assertFalse( \Simple_Documentation\get_db_version() );
	}

	public function test_should_detect_version_1() {
		global $wpdb;

		$wpdb->query(
			"
			CREATE TABLE `{$wpdb->prefix}simpledocumentation` (
			ID bigint(20) NOT NULL auto_increment,
			type varchar(200) NOT NULL default 'note',
			title varchar(255) NOT NULL default 'New document',
			content text NOT NULL,
			etoile_b tinyint(1) NOT NULL default 0,
			etoile_t datetime,
			PRIMARY KEY (ID) );"
		);

		$this->assertSame( 1, \Simple_Documentation\get_db_version() );
	}

	public function test_should_detect_version_2_after_upgrade_from_v1() {
		global $wpdb;

		// Initial v1 version
		$wpdb->query(
			"
			CREATE TABLE `{$wpdb->prefix}simpledocumentation` (
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
			"ALTER TABLE `{$wpdb->prefix}simpledocumentation`
			ADD COLUMN restricted varchar(500),
			ADD COLUMN attachment_id int(5),
			ADD COLUMN ordered int(5);"
		);

		$this->assertSame( 2, \Simple_Documentation\get_db_version() );
	}

	public function test_should_detect_version_2() {
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

		$this->assertSame( 2, \Simple_Documentation\get_db_version() );
	}

	public function test_should_detect_version_3() {
		\Simple_Documentation\create_table();

		$this->assertSame( 3, \Simple_Documentation\get_db_version() );
	}
}
