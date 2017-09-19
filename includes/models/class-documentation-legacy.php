<?php
/**
 * Simple Documentation
 * Model - Simple Documentation Custom Table
 */

namespace SimpleDocumentation\Models;

class Documentation_Legacy {
	/**
	 * Simple Documentation Custom Table Row Object
	 *
	 * @var \stdClass
	 */
	private $row;

	/**
	 * Simple_Documentation_DB constructor.
	 *
	 * @param \stdClass $row
	 */
	public function __construct( $row ) {
		$this->row = $row;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return (int) $this->row->ID;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		/**
		 * Can be one of the following:
		 * note, link, file, video
		 */
		return $this->row->type;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->row->title;
	}

	/**
	 * Get entry content. Format changes based on the type of the entry
	 * Should be thoroughly tested w/ Unit-tests to ease the import/upgrade process
	 *
	 * @return string
	 */
	public function get_content() {
		$content = $this->row->content;

		if ( $this->get_type() === 'note' ||
			 $this->get_type() === 'link' ) {
			/**
			 * In the case of notes entries were saved encrypted, sometimes note so we must make sure we get back
			 * to a properly decoded string
			 *
			 * @TODO implement unit-test to ensure we don't have discrepancies depending on the source encoding
			 * - test when string is bare. ex: <p>Entries' content</p>
			 * - test when there's add slash applied alone. ex: <p>Entries\' content</p>
			 * - encoded string. ex: &lt;p&gt;Entries\' content&lt;/p&gt;
			 *
			 * this should return "<p>Entries' Content</p>" in all cases
			 */

			// stripslashes and htmlspecialchars_decode are run in that order in previous versions of the plugin
			$content = stripslashes( $content );
			$content = htmlspecialchars_decode( $content );
			return $content;
		}

		if ( $this->get_type() === 'file' ) {
			if ( empty( $this->get_attachment_id() ) ) {
				$content = htmlspecialchars_decode( $content );
				$content = stripslashes( $content );
				$content_object = json_decode( $content );

				if ( is_array( $content_object ) && ! empty( $content_object['url'] ) ) {
					// @TODO Check if we'd rather have an attachment id there
					return $content_object['url'];
				}

				return false;
			}
		}

		return $content;
	}

	/**
	 * Get allowed user roles.
	 *
	 * @return string[] - array of role slug
	 */
	public function get_allowed_user_roles() {
		/**
		 * If restricted is empty we should in theory pull the list from the settings
		 * Given this is only used to import towards the new version we just want know what the user set before
		 */
		if ( empty( $this->row->restricted ) ) {
			return [];
		}

		$roles = json_decode( $this->row->restricted );

		return $roles;
	}

	/**
	 * attachment_id
	 */
	public function get_attachment_id() {
		return $this->row->attachment_id;
	}

	/**
	 * @return int
	 */
	public function get_order_index() {
		return $this->row->ordered;
	}

	/**
	 * @return bool|int
	 */
	public function delete() {
		global $wpdb;

		$where = [
			'ID' => $this->get_id(),
		];

		return $wpdb->delete( self::get_table(), $where, [ '%d' ] );
	}

	/**
	 * @param \stdClass $row
	 *
	 * @return bool|static
	 */
	public static function from_db_row( $row ) {
		if ( ! empty( $row->ID ) ) {
			return new static( $row );
		}

		return false;
	}

	protected static function get_table() {
		global $wpdb;
		return $wpdb->prefix . 'simpledocumentation';
	}

	/**
	 * @param int $entry_id
	 *
	 * @return static|bool
	 */
	public static function get( $entry_id ) {
		global $wpdb;

		$table = self::get_table();

		$query = $wpdb->prepare(
			"SELECT * FROM {$table} WHERE `ID` = %d;",
			$entry_id
		);

		$row = $wpdb->get_row( $query );

		return static::from_db_row( $row );
	}

	/**
	 * @return static[]
	 */
	public static function get_all() {
		global $wpdb;

		$table = self::get_table();

		$rows = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY ID;" );

		if ( is_array( $rows ) ) {
			return array_map( [ get_called_class(), 'from_db_row' ], $rows );
		}

		return [];
	}

	/**
	 * @param array $custom_arguments
	 *
	 * @return bool|int
	 */
	public static function insert( $custom_arguments = [] ) {
		global $wpdb;

		if ( empty( $custom_arguments['title'] ) ) {
			return false;
		}

		$default_args = [
			'type' => 'note',
			'title' => '',
			'content' => '',
			'restricted' => '',
			'attachment_id' => 0,
			'ordered' => 0,
		];

		$format = [
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
		];

		$args = wp_parse_args( $custom_arguments, $default_args );

		$data = [];

		foreach ( array_keys( $default_args ) as $key ) {
			$data[ $key ] = $args[ $key ];
		}

		$rows_affected = $wpdb->insert(
			self::get_table(),
			$data,
			$format
		);

		if ( $rows_affected === false ) {
			return false;
		}

		return $wpdb->insert_id;
	}
}
