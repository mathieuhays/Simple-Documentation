<?php
/**
 * Simple Documentation - Model - Document
 */

namespace Simple_Documentation\Models;


class Document extends DB_Row_Model {

	/**
	 * @return string
	 */
	public static function get_table() {
		global $wpdb;
		return $wpdb->prefix . 'simpledocumentation';
	}


	/**
	 * @return array
	 */
	public static function get_fields() {
		return [
			'type' => '%s',
			'title' => '%s',
			'content' => '%s',
			'restricted' => '%s',
			'attachment_id' => '%d',
			'ordered' => '%d',
		];
	}
}
