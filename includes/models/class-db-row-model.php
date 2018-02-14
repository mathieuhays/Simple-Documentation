<?php
/**
 * Simple Documentation
 * Models
 */

namespace Simple_Documentation\Models;

abstract class DB_Row_Model extends Base_Model {
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
	 * DB_Row_Model constructor.
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

		if ( isset( $this->row->{$prop_name} ) ) {
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

		foreach ( $fields as $field_name => $field_format ) {
			$original_value = $this->row->{$field_name};
			$new_value = $this->updated_row->{$field_name};

			if ( $original_value !== $new_value ) {
				$update[ $field_name ] = $new_value;
				$update_format[] = $field_format;
			}
		}

		if ( ! empty( $update ) ) {
			$result = $wpdb->update(
				static::get_table(),
				$update,
				[ 'ID' => $this->get_id() ],
				$update_format,
				[ '%d' ]
			);

			if ( $result === false ) {
				return new \WP_Error( 'request-error', 'Something went wrong when update entity.' );
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

		// @TODO check and return from cache

		$table = static::get_table();

		$query_string = $wpdb->prepare(
			"SELECT * FROM {$table} WHERE ID = %d",
			(int) $entity_id
		);

		$row = $wpdb->get_row( $query_string );

		$model_instance = static::from_row( $row );

		// @TODO cache output

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
				$validation = new \WP_Error( 'validation-error', 'Error while validating data.' );
			}

			return $validation;
		}

		$sanitised_args = [];
		$format_args = [];
		$fields = static::get_fields();
		$field_keys = array_keys( $fields );

		foreach ( $args as $field_name => $field_value ) {
			if ( in_array( $field_name, $field_keys, true ) ) {
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
				'db-error',
				'Error while inserting data.'
			);
		}

		$entity_id = $wpdb->insert_id;

		// @TODO clear caches related to counts, etc..

		return static::from_id( $entity_id );
	}


	/**
	 * @param int $page
	 * @param int $entities_per_page
	 *
	 * @return static[]|\WP_Error
	 */
	public static function get( $page = 1, $entities_per_page = 20 ) {
		global $wpdb;

		$table = static::get_table();

		if ( empty( $page ) || $page < 1 ) {
			return new \WP_Error( 'page-error', 'Invalid page parameter.' );
		}

		$limit_start = $entities_per_page * ($page - 1);
		$limit_count = $entities_per_page;

		$query_string = $wpdb->prepare(
			"SELECT * FROM {$table} ORDER BY ID DESC LIMIT %d OFFSET %d",
			$limit_count,
			$limit_start
		);

		$results = $wpdb->get_results( $query_string );

		if ( ! is_array( $results ) ) {
			return new \WP_Error( 'request-error', 'Error while querying entities.' );
		}

		$model_instances = array_map( [ get_called_class(), 'from_row' ], $results );

		return $model_instances;
	}


	/**
	 * @return int
	 */
	public static function get_count() {
		global $wpdb;

		// @TODO get cached

		$table = static::get_table();
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );

		// @TODO set cache

		return (int) $count;
	}


	/**
	 * @return array
	 */
	public static function get_default_args() {
		return [];
	}


	/**
	 * @param array $args
	 *
	 * @return bool|\WP_Error
	 */
	public static function validate_args( $args = [] ) {
		// @TODO override in child class when necessary
		return true;
	}


	/**
	 * @important must be overridden in child class
	 *
	 * @return string
	 */
	public static function get_table() {
		_doing_it_wrong(
			__FUNCTION__,
			'must be overridden in child class',
			SIMPLE_DOCUMENTATION_VERSION
		);

		return '';
	}


	/**
	 * @important must be overridden in child class
	 *
	 * @return array
	 */
	public static function get_fields() {
		_doing_it_wrong(
			__FUNCTION__,
			'must be overridden in child class',
			SIMPLE_DOCUMENTATION_VERSION
		);

//		Example:
//		return [
//			'name' => '%s',
//			'status' => '%d',
//		];

		return [];
	}
}
