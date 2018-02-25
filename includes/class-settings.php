<?php
/**
 * Settings
 */

namespace Simple_Documentation;


class Settings {

	const OPTION_NAME = 'simpledocumentation_main_settings';

	/**
	 * @var array
	 */
	private $settings;

	public static function bootstrap() {
		$instance = static::get_instance();

		$instance->maybe_upgrade();
		$instance->load();
	}

	protected function maybe_upgrade() {
		$table = get_site_option( 'clientDocumentation_table', false );

		if ( $table === false ) {
			return;
		}

		$defaults = static::get_defaults();

		$rename = [
			'table' => 'table',
			'user_role' => 'clientRole',
			'first_activation' => '',
			'db_version' => 'dbVersion',
			'item_per_page' => 'itemNumber',
			'label_widget_title' => 'widgetTitle',
			'label_welcome_title' => 'welcomeTitle',
			'label_welcome_message' => 'welcomeMessage',
		];

		$final = [];

		foreach ( $defaults as $setting_name => $default_value ) {
			if ( $setting_name['table'] ) {
				$final[ $setting_name ] = $table;
				continue;
			}

			$final[ $setting_name ] = get_option( 'clientDocumentation_' . $rename[ $setting_name ], $default_value );
		}

		if ( ! is_array( $final['user_role'] ) ) {
			$final['user_role'] = [ $final['user_role'] ];
		}

		if ( ! in_array( 'administrator', $final['user_role'] ) ) {
			$final['user_role'][] = 'administrator';
		}

		if ( add_site_option( static::OPTION_NAME, $final ) ) {
			/* Clean previous version settings */
			delete_option( 'clientDocumentation_clientRole' );
			delete_option( 'clientDocumentation_dbVersion' );
			delete_option( 'clientDocumentation_widgetTitle' );
			delete_option( 'clientDocumentation_itemNumber' );
			delete_option( 'clientDocumentation_welcomeMessage' );
			delete_option( 'clientDocumentation_welcomeTitle' );
			delete_option( 'clientDocumentation_allitems' );
			delete_option( 'clientDocumentation_pinned' );
			delete_site_option( 'clientDocumentation_table' );
		}
	}

	protected function load() {
		if ( is_null( $this->settings ) ) {
			$this->settings = wp_parse_args(
				get_site_option( static::OPTION_NAME, [] ),
				static::get_defaults()
			);
		}
	}

	/**
	 * @return array
	 */
	protected function get_defaults() {
		global $wpdb;

		return [
			'table' => $wpdb->prefix . 'simpledocumentation', // @note deprecated
			'user_role' => array( 'administrator', 'editor' ),
			'first_activation' => true,
			'db_version' => '3.0',
			'item_per_page' => 10,
			'label_widget_title' => __( 'Resources' , 'client-documentation' ),
			'label_welcome_title' => __( 'Welcome', 'client-documentation' ),
			'label_welcome_message' => __( 'Need Help ? All you need is here !', 'client-documentation' ),
		];
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get( $name, $default = null ) {
		$this->load();

		$value = $default;

		if ( isset( $this->settings[ $name ] ) ) {
			$value = $this->settings[ $name ];
		}

		return $value;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function update( $name, $value ) {
		$this->load();

		$this->settings[ $name ] = $value;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function save() {
		$this->load();

		update_site_option( static::OPTION_NAME, $this->settings );

		return $this;
	}

	/**
	 * @return Settings
	 */
	public static function get_instance() {
	    static $instance;

	    if ( is_null( $instance ) ) {
	        $instance = new self;
	    }

	    return $instance;
	}
}
