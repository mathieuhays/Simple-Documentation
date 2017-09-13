<?php
/**
 *  Simple Documentation - Settings
 *  Centralised management of the settings
 */

namespace SimpleDocumentation;

class Settings {
	private $settings = [];


	public function bootstrap() {
		// @TODO $settings_db = get_site_option( ... )
		$settings_db = [];

		$this->settings = wp_parse_args(
			$settings_db,
			[
				'label_widget_title' => __( 'Documentation', 'simple-documentation' ),
			]
		);
	}


	/**
	 *  Get Setting
	 *
	 *  @param  string  $setting_key
	 *  @return mixed
	 */
	public function get( $setting_key ) {
		if ( isset( $this->settings[ $setting_key ] ) ) {
			return $this->settings[ $setting_key ];
		}

		return false;
	}


	/**
	 *  Update Given Setting
	 *
	 *  @param  string  $setting_key
	 *  @param  mixed   $data
	 *  @return bool
	 */
	public function update( $setting_key, $data ) {
		$this->settings[ $setting_key ] = $data;

		// @TODO update_site_option( ... )

		return false;
	}


	/**
	 * @return Settings
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
