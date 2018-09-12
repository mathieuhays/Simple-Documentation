<?php
/**
 * Export functionality
 */

namespace Simple_Documentation;


use Simple_Documentation\Models\Documentation;

class Export {

	protected $args;

	protected $data;

	protected $options;


	public function __construct( $custom_args = [] ) {
		$this->args = wp_parse_args( $custom_args, [
			'options' => false,
			'data' => true,
		] );

		$this->options = [
			'included' => false,
			'data' => null,
		];

		$this->maybe_generate_options();
		$this->maybe_generate_data();
	}


	public function maybe_generate_options() {
		if ( ! $this->args['options'] ) {
			return;
		}

		$settings = Simple_Documentation::get_instance()->settings;

		$this->options['included'] = true;
		$this->options['data'] = [];

		$settings_to_import = [
			'user_role',
			'item_per_page',
			'label_widget_title',
			'label_welcome_title',
			'label_welcome_message',
		];

		foreach ( $settings_to_import as $setting_name ) {
			$this->options['data'][ $setting_name ] = $settings[ $setting_name ];
		}
	}


	public function maybe_generate_data() {
		if ( ! $this->args['data'] ) {
			return;
		}

		$entries = Documentation::get_all();
		$this->data = array_map([ $this, 'format_data' ], $entries );
	}


	/**
	 * @param Documentation $item
	 *
	 * @return array
	 */
	protected function format_data( Documentation $item ) {
		return $item->to_array();
	}


	/**
	 * Serve as downloadable JSON file
	 */
	public function serve() {
		/**
		 * @TODO implement serve
		 */
	}


	/**
	 * @return array
	 */
	public function to_array() {
		return [
			$this->data,
			$this->options
		];
	}

}
