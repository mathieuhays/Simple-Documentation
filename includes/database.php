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

	$installed = wp_cache_get( 'installed', 'simpledocumentation', false, $found );

	if ( ! $found ) {
		$installed = count( $wpdb->get_col( "SHOW TABLES LIKE `{$wpdb->prefix}simpledocumentation`" ) ) === 1;

		wp_cache_set( 'installed', $installed, 'simpledocumentation' );
	}

	return $installed;
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


/**
 * @return bool|int
 */
function get_db_version() {
	global $wpdb;

	if ( ! is_installed() ) {
		return false;
	}

	$etoile_b_count = count( $wpdb->get_col(
		"SHOW COLUMNS FROM `{$wpdb->prefix}simpledocumentation` LIKE 'etoile_b'"
	) );

	if ( $etoile_b_count === 0 ) {
		/**
		 * Version 3 removed un-used `etoile_b` column
		 */
		return 3;
	}

	$restricted_count = count( $wpdb->get_col(
		"SHOW COLUMNS FROM `{$wpdb->prefix}simpledocumentation` LIKE 'restricted'"
	) );

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

	$query = "ALTER TABLE `{$wpdb->prefix}simpledocumentation`
		MODIFY COLUMN `ID` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
		MODIFY COLUMN `type` VARCHAR(255) NOT NULL,
		MODIFY COLUMN `content` LONGTEXT,
		DROP COLUMN `etoile_b`,
		DROP_COLUMN `etoile_t`,
		ADD COLUMN `restricted` VARCHAR(500),
		ADD COLUMN `attachment_id` BIGINT(20) unsigned,
		ADD COLUMN `ordered` BIGINT(20) unsigned;";

	$result = $wpdb->query( $query );

	if ( $result !== 1 ) {
		// @TODO handle error
	}
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

	$query = "ALTER TABLE `{$wpdb->prefix}simpledocumentation`
		MODIFY COLUMN `ID` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
		MODIFY COLUMN `type` VARCHAR(255) NOT NULL,
		MODIFY COLUMN `content` LONGTEXT,
		MODIFY COLUMN `attachment_id` BIGINT(20) unsigned,
		MODIFY COLUMN `ordered` BIGINT(20) unsigned,
		DROP COLUMN `etoile_b`,
		DROP_COLUMN `etoile_t`;";

	$result = $wpdb->query( $query );

	if ( $result !== 1 ) {
		// @TODO handle error
	}
}


function maybe_setup_db() {
	$db_version = get_db_version();

	if ( $db_version === 1 ) {
		upgrade_db_from_v1();
	} else if ( $db_version === 2 ) {
		upgrade_db_from_v2();
	} else if ( $db_version === false ) {
		create_table();
	}
}
