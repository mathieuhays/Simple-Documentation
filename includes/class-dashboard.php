<?php
/**
 *  Simple Documentation - Dashboard
 */

namespace SimpleDocumentation;

use Utilities\Loader;

class Dashboard {
    /**
     *  @var Dashboard singleton instance
     */
    private static $instance;


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
            Settings::get_instance()->get( 'label_widget_title' ),
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
     *  Get Instance
     *
     *  @return Dashboard singleton instance
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
