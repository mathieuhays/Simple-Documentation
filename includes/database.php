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

	$installed = wp_cache_get( 'installed', 'simpledocumentation', false, $found )

	if ( ! $found ) {
		$installed = count( $wpdb->get_col( "SHOW TABLES LIKE `{$wpdb->prefix}simpledocumentation`" ) ) === 1;

		wp_cache_set( 'installed', $installed, 'simpledocumentation' );
	}

	return $installed;
}



function create_tables() {
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
	// @TODO
}


function upgrade_db_from_v2() {
	// @TODO
}
