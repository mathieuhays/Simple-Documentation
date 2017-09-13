<?php
/**
 *  Simple Documentation - Dashboard
 */

namespace SimpleDocumentation;

use \SimpleDocumentation\Utilities\Loader;

class Dashboard {
	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		// add dashboard to regular WordPress Dashboard screen
		add_action( 'wp_dashboard_setup', [ $this, 'register' ] );

		// Add dashboard to network level dashboard screen
		if ( is_multisite() ) {
			add_action( 'wp_network_dashboard_setup', [ $this, 'register' ] );
		}
	}


	/**
	 *  Register Dashboard Widget to WordPress
	 */
	public function register() {
		wp_add_dashboard_widget(
			Core::SLUG,
			Settings::instance()->get( 'label_widget_title' ),
			[ $this, 'render' ]
		);
	}


	/**
	 *  Render Widget
	 */
	public function render() {
		Loader::template( 'dashboard' );
	}


	/**
	 * @return Dashboard
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
