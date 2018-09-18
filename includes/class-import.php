<?php
/**
 * Import
 */

namespace Simple_Documentation;


use Simple_Documentation\Models\Documentation;

class Import {

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var array
	 */
	protected $options;


	/**
	 * @var \WP_Error[]
	 */
	protected $errors = [];


	/**
	 * Import constructor.
	 *
	 * @param array $raw_json
	 */
	public function __construct( $raw_json ) {
		if ( is_string( $raw_json ) ) {
			try {
				$import_data = json_decode( $raw_json, true, 512, JSON_THROW_ON_ERROR );
			} catch (\JsonException $exception) {
				$this->log_error( new \WP_Error(
					$exception->getCode(),
					$exception->getMessage()
				) );
			}
		} else if ( is_array( $raw_json ) ) {
			$import_data = $raw_json;
		} else {
			$this->log_error( new \WP_Error(
				'unknown-import-format',
				'Import data not recognised'
			) );
		}

		if ( $this->has_errors() ) {
			return;
		}

		if ( count( $import_data ) !== 2 ) {
			$this->log_error( new \WP_Error(
				'invalid-format',
				'Invalid format'
			) );
			return;
		}

		$this->data = $import_data[0];
		$this->options = $import_data[1];

		$this->maybe_import_data();
		$this->maybe_import_options();
	}


	/**
	 * @param \WP_Error $error
	 */
	public function log_error( \WP_Error $error ) {
		$this->errors[] = $error;
	}


	/**
	 * @return bool
	 */
	public function has_errors() {
		return ! empty( $this->errors );
	}


	/**
	 * @return \WP_Error[]
	 */
	public function get_errors() {
		return $this->errors;
	}


	/**
	 * @return \WP_Error|bool
	 */
	public function get_error() {
		if ( isset( $this->errors[0] ) ) {
			return $this->errors[0];
		}

		return false;
	}


	public function maybe_import_options() {
		if ( ! isset( $this->options['included'] ) ||
			 ! $this->options['included'] ) {
			return;
		}

		$plugin = Simple_Documentation::get_instance();

		$options = $this->options['data'];

		$option_to_import = [
			'user_role',
			'item_per_page',
			'label_widget_title',
			'label_welcome_title',
			'label_welcome_message',
		];

		foreach ( $option_to_import as $option_name ) {
			$value = $options[ $option_name ];

			if ( $option_name === 'user_role' ) {
				$value = json_decode( $value );
			}

			$plugin->settings[ $option_name ] = $value;
		}

		$plugin->update_settings();
	}


	public function maybe_import_data() {
		if ( empty( $this->data ) ) {
			return;
		}

		$fields = [
			'ID',
			'title',
			'content',
			'type',
			'attachment_id',
			'attachment_filename',
			'attachment_url',
			// items below should be renamed
			'ordered',
			'restricted',
		];

		foreach ( $this->data as $row ) {
			$insert_data = [];

			foreach ( $fields as $field_name ) {
				if ( isset( $row[ $field_name ] ) ) {
					$insert_data[ $field_name ] = $row[ $field_name ];
				}
			}

			$item = Documentation::insert( $insert_data );

			if ( is_wp_error( $item ) ) {
				$this->log_error( $item );
			}
		}
	}
}
