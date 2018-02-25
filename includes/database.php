<?php
/**
 * Simple Documentation
 * DB Operations
 * Install / Upgrade / Delete
 */

namespace Simple_Documentation;


/**
 * @return bool|mixed
 */
function is_installed() {
	global $wpdb;

	return $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}simpledocumentation'" ) === 1;
}



function create_table() {
	global $wpdb;

	$query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}simpledocumentation` (
		`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		`type` varchar(255) NOT NULL,
		`title` varchar(255) NOT NULL,
		`content` longtext,
		`restricted` varchar(500),
		`attachment_id` bigint(20) unsigned,
		`ordered` bigint(20) unsigned,
		
		PRIMARY KEY (`ID`)
	) ENGINE=InnoDB;";

	$result = $wpdb->query( $query );

	if ( $result !== 1 ) {
		// @TODO something went wrong
	}
}


function maybe_rename_table() {
	global $wpdb;

	$table = get_site_option( 'clientDocumentation_table', false );

	if ( $table === false ) {
		$main_settings = get_site_option( 'simpledocumentation_main_settings', false );

		if ( isset( $main_settings['table'] ) ) {
			$table = $main_settings['table'];
		}
	}

	if ( empty( $table ) ) {
		return;
	}

	$new_table_name = $wpdb->prefix . 'simpledocumentation';

	if ( $table === $new_table_name ) {
		return;
	}

	$wpdb->query( "RENAME TABLE `{$table}` TO `{$new_table_name}`" );
}


/**
 * This function relies on the fact that the table has been renamed first.
 *
 * @return bool|int
 */
function get_db_version() {
	global $wpdb;

	if ( ! is_installed() ) {
		return false;
	}

	$etoile_b_column = $wpdb->query(
		"SHOW COLUMNS FROM `{$wpdb->prefix}simpledocumentation` LIKE 'etoile_b'"
	);

	if ( $etoile_b_column === 0 ) {
		/**
		 * Version 3 removed un-used `etoile_b` column
		 */
		return 3;
	}

	$restricted_count = $wpdb->query(
		"SHOW COLUMNS FROM `{$wpdb->prefix}simpledocumentation` LIKE 'restricted'"
	);

	if ( $restricted_count === 1 ) {
		/**
		 * Version 2 added `restricted` column
		 */
		return 2;
	}

	return 1;
}


function upgrade_db_from_v1() {
	global $wpdb;

	/**
	 * V1 Structure:
	 *	CREATE TABLE {$wpdb->clientDocumentation} (
	 *	ID bigint(20) NOT NULL auto_increment,
	 *	type varchar(200) NOT NULL default 'note',
	 *	title varchar(255) NOT NULL default 'New document',
	 *	content text NOT NULL,
	 *	etoile_b tinyint(1) NOT NULL default 0,
	 *	etoile_t datetime,
	 *	PRIMARY KEY  (ID) )";
	 */

	$query = "
		ALTER TABLE `{$wpdb->prefix}simpledocumentation`
		MODIFY COLUMN `ID` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
		MODIFY COLUMN `type` VARCHAR(255) NOT NULL,
		MODIFY COLUMN `content` LONGTEXT,
		DROP COLUMN `etoile_b`,
		DROP_COLUMN `etoile_t`,
		ADD COLUMN `restricted` VARCHAR(500),
		ADD COLUMN `attachment_id` BIGINT(20) unsigned,
		ADD COLUMN `ordered` BIGINT(20) unsigned,
		ENGINE=InnoDB;";

	$wpdb->query( $query );
}


function upgrade_db_from_v2() {
	global $wpdb;

	/**
	 * V2 Structure:
	 *  CREATE TABLE $table (
	 *  ID bigint(20) NOT NULL auto_increment,
	 *  type varchar(200) NOT NULL default 'note',
	 *  title varchar(255) NOT NULL default 'New document',
	 *  content text NOT NULL,
	 *  etoile_b tinyint(1) NOT NULL default 0,
	 *  etoile_t datetime,
	 *  restricted varchar(500),
	 *  attachment_id bigint(20), // 1.
	 *  ordered bigint(20), // 1.
	 *  UNIQUE KEY ID (ID)
	 *  );";
	 *
	 * 1. set to int(5) on upgrade from v1 by mistake :-(
	 */

	$query = "
		ALTER TABLE `{$wpdb->prefix}simpledocumentation`
		MODIFY COLUMN `ID` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
		MODIFY COLUMN `type` VARCHAR(255) NOT NULL,
		MODIFY COLUMN `content` LONGTEXT,
		MODIFY COLUMN `attachment_id` BIGINT(20) unsigned,
		MODIFY COLUMN `ordered` BIGINT(20) unsigned,
		DROP COLUMN `etoile_b`,
		DROP COLUMN `etoile_t`,
		ENGINE=InnoDB;";

	$wpdb->query( $query );

	$has_unique_key = count( $wpdb->get_col(
		"SHOW INDEX FROM `{$wpdb->prefix}simpledocumentation` WHERE Key_name = 'ID' "
	) );

	if ( $has_unique_key === 1 ) {
		$wpdb->query(
			"ALTER TABLE `{$wpdb->prefix}simpledocumentation` DROP INDEX `ID`, ADD PRIMARY KEY (`ID`);"
		);
	}
}

function maybe_setup_db() {
	maybe_rename_table();

	$db_version = get_db_version();

	if ( $db_version === 1 ) {
		upgrade_db_from_v1();
	} else if ( $db_version === 2 ) {
		upgrade_db_from_v2();
	} else if ( $db_version === false ) {
		create_table();
	}
}
