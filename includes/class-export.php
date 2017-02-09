<?php
/**
 *  Simple Documentation - Export
 */

namespace SimpleDocumentation;

class Export {
    /**
     *  @var Export singleton instance
     */
    private static $instance;


    /**
     *  Bootstrap
     */
    public function bootstrap() {
        /**
         *  @TODO
         *  Check what's the best option for the export
         *  Check if the WordPress export functionality enables us to attach options
         */
    }


    /**
     *  Get Instance
     *
     *  @return Export singleton instance
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
