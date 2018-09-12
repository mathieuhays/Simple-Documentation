<?php
/**
 * Table Model
 * Base Model for custom table manipulation in WordPress.
 */

namespace Simple_Documentation\Models;


class Table_Model extends Base_Model {

	/**
	 * @var \stdClass
	 */
	protected $row;

	/**
	 * @var \stdClass
	 */
	protected $updated_row;

	/**
	 * @var bool
	 */
	protected $is_dirty;


	/**
	 * Table_Model constructor.
	 *
	 * @param \stdClass $row
	 */
	protected function __construct( $row ) {
		$this->row = $row;
		$this->updated_row = clone $row;
		$this->is_dirty = false;
	}


	/**
	 * @param string $prop_name
	 * @param mixed $default
	 * @param string $context
	 *
	 * @return mixed
	 */
	public function get_prop( $prop_name, $default = null, $context = 'db' ) {
		$value = $default;

		if ( property_exists( $this->row, $prop_name ) ) {
			$value = $this->row->{$prop_name};
		}

		if ( $context === 'display' ) {
			$value = stripslashes( $value );
		}

		return $value;
	}


	/**
	 * @param string $prop_name
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function update_prop( $prop_name, $value ) {
		if ( property_exists( $this->updated_row, $prop_name ) ) {
			$this->updated_row->{$prop_name} = $value;
			$this->is_dirty = true;
		}

		return true;
	}


	/**
	 * @return int
	 */
	public function get_id() {
		return (int) $this->get_prop( 'ID' );
	}


	/**
	 * @return bool|\WP_Error
	 */
	public function save() {
		global $wpdb;

		$fields = static::get_fields();
		$update = [];
		$update_format = [];

		foreach ( $fields as $name => $format ) {
			$original_value = $this->row->{$name};
			$new_value = $this->updated_row->{$name};

			if ( $original_value !== $new_value ) {
				$update[ $name ] = $new_value;
				$update_format[] = $format;
			}
		}

		if ( ! empty( $update ) ) {
			$result =$wpdb->update(
				static::get_table(),
				$update,
				[ 'ID' => $this->get_id() ],
				$update_format,
				[ '%d' ]
			);

			if ( $result === false ) {
				return new \WP_Error( 'update-error', 'Something went wrong while updating entity.' );
			}

			$this->row = clone $this->updated_row;

			// @TODO clear cache
		}

		$this->is_dirty = false;

		return true;
	}


	/**
	 * @param \stdClass $row
	 *
	 * @return bool|static
	 */
	public static function from_row( $row ) {
		if ( empty( $row ) ) {
			return false;
		}

		return new static( $row );
	}


	/**
	 * @param int $entity_id
	 *
	 * @return bool|static
	 */
	public static function from_id( $entity_id ) {
		global $wpdb;

		if ( ! is_numeric( $entity_id ) ) {
			return false;
		}

		// @TODO return from cache if available

		$table = static::get_table();

		$query_string = $wpdb->prepare(
			"SELECT * FROM {$table} WHERE ID = %d;",
			(int) $entity_id
		);

		$row = $wpdb->get_row( $query_string );

		$model_instance = static::from_row( $row );

		// @TODO cache

		return $model_instance;
	}


	/**
	 * @param array $custom_args
	 *
	 * @return bool|static|\WP_Error
	 */
	public static function insert( $custom_args = [] ) {
		global $wpdb;

		$args = wp_parse_args( $custom_args, static::get_default_args() );

		$validation = static::validate_args( $args );

		if ( $validation !== true ) {
			if ( ! is_wp_error( $validation ) ) {
				$validation = new \WP_Error(
					'validation-error',
					'Validation error'
				);
			}

			return $validation;
		}

		$sanitised_args = [];
		$format_args = [];
		$fields = static::get_fields();
		$fields_keys = array_keys( $fields );

		foreach ( $args as $field_name => $field_value ) {
			if ( in_array( $field_name, $fields_keys, true ) ) {
				$sanitised_args[ $field_name ] = $field_value;
				$format_args[] = $fields[ $field_name ];
			}
		}

		$result = $wpdb->insert(
			static::get_table(),
			$sanitised_args,
			$format_args
		);

		if ( $result === false ) {
			return new \WP_Error(
				'insert-error',
				'Error while inserting entity'
			);
		}

		$entity_id = $wpdb->insert_id;

		// @TODO clear count caches, etc..

		return static::from_id( $entity_id );
	}


	/**
	 * @param int $page
	 * @param int $posts_per_page
	 *
	 * @return static[]
	 */
	public static function get_page( $page, $posts_per_page = 20 ) {
		global $wpdb;

		$table = static::get_table();

		if ( empty( $page ) || $page < 1 ) {
			// @TODO log error in debug mode
			return [];
		}

		$limit_start = $posts_per_page * ( $page - 1 );
		$limit_count = $posts_per_page;

		$query_string = $wpdb->prepare(
			"SELECT * FROM {$table} ORDER BY ID DESC LIMIT %d OFFSET %d;",
			$limit_count,
			$limit_start
		);

		$results = $wpdb->get_results( $query_string );

		if ( ! is_array( $results ) ) {
			// @TODO log error in debug mode
			return [];
		}

		$model_instances = array_map([ get_called_class(), 'from_row' ], $results);

		return array_filter( $model_instances );
	}


	/**
	 * @return int
	 */
	public static function get_count() {
		global $wpdb;

		// @TODO get cached

		$table = static::get_table();
		$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table};" );

		// @TODO set cache

		return $count;
	}


	protected static function validate_args( $args = [] ) {
		return true;
	}


	protected static function get_table() {
		return '';
	}


	protected static function get_fields() {
		return [];
	}


	protected static function get_default_args() {
		return [];
	}
}
