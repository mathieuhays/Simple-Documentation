<?php
/**
 * Ajax Manager
 */

namespace Simple_Documentation\Ajax;


use Simple_Documentation\Utilities\Singleton;

class Ajax_Manager extends Singleton {

	const PREFIX = 'simpledocumentation_';

	/**
	 * @var Base_Ajax[]
	 */
	protected $instances = [];


	public static function bootstrap() {
		/**
		 * @var static $instance
		 */
	    $instance = parent::bootstrap();

		$instance->maybe_register_instances();

	    return $instance;
	}


	protected function maybe_register_instances() {
		if ( empty( $this->instances ) ) {
			return;
		}

		foreach ( $this->instances as $instance ) {
			add_action(
				'wp_ajax_' . static::PREFIX . $instance->get_action_name(),
				[ $instance, '_render' ]
			);

			if ( $instance->is_public() ) {
				add_action(
					'wp_ajax_nopriv_' . static::PREFIX . $instance->get_action_name(),
					[ $instance, '_render' ]
				);
			}
		}
	}


	/**
	 * @param Base_Ajax[]|Base_Ajax $ajax_instance
	 */
	public static function register( $ajax_instances ) {
		if ( empty( $ajax_instances ) ) {
			return;
		}

		if ( ! is_array( $ajax_instances ) ) {
			$ajax_instances = [ $ajax_instances ];
		}

		$instance = static::get_instance();

		foreach ( $ajax_instances as $ajax_instance ) {
			$instance->instances[] = $ajax_instance;
		}
	}
}
