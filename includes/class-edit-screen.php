<?php
/**
 *  Simple Documentation - Edit Screen
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Utilities\Loader;
use SimpleDocumentation\Core;
use SimpleDocumentation\DocumentationItems\DocumentationItem;
use SimpleDocumentation\DocumentationItems\DocumentationItems;
use SimpleDocumentation\DocumentationItems\DocumentationTypes;

class EditScreen {
    private static $instance;


    /**
     *  Bootstrap
     */
    public function bootstrap() {
        /**
         *  Add type selector below title
         */
        add_action( 'edit_form_after_title', [ $this, 'render_type_selector' ] );

        /**
         *  Load JavaScript
         */
        add_action( 'admin_init', [ $this, 'enqueue_js' ] );

        /**
         *  Register Meta Boxes
         */
        add_action( 'add_meta_boxes_' . DocumentationItems::POST_TYPE, [ $this, 'register_meta_boxes' ] );
    }


    /**
     *  Render Type Selector
     */
    public function render_type_selector() {
        Loader::component( 'type-selector' );
    }


    /**
     *  Enqueue JavaScript
     */
    public function enqueue_js() {
        global $self;

        /**
         *  Restrict Asset loading for the edit screen
         */
        if ( $self !== 'post-new.php' &&
             $self !== 'post.php' ) {
            return false;
        }

        $current_type = DocumentationTypes::get_instance()->get_default();

        if ( $self  === 'post.php' ) {
            $current_type = (new DocumentationItem)->get_type();
        }

        wp_enqueue_script(
            Core::SLUG . '-edit-screen',
            SIMPLEDOC_JS_URL . '/edit-screen.js',
            [ 'jquery' ],
            SIMPLEDOC_VERSION,
            true
        );

        wp_localize_script(
            CORE::SLUG . '-edit-screen',
            'simpleDocumentationEditScreen',
            [
                'metaboxes' => array_map( function( $type ) {
                    return sprintf( '%s-%s', CORE::SLUG, $type->get_slug() );
                }, DocumentationTypes::get_instance()->get_all() ),
                'current_type' => $current_type->get_slug()
            ]
        );
    }


    /**
     *  Register Meta Boxes
     */
    public function register_meta_boxes() {
        /**
         *  @TODO generate this from DocumentationTypes list.
         *  add property to type object whether we need to register a meta box
         *  or not
         */

        /**
         *  Note Meta Box
         */
        add_meta_box(
            CORE::SLUG . '-note',
            __( 'Note settings', 'simple-documentation' ),
            [ $this, 'render_note_meta_box' ],
            DocumentationItems::POST_TYPE,
            'normal'
        );


        /**
         *  Note Meta Box
         */
        add_meta_box(
            CORE::SLUG . '-video',
            __( 'Video settings', 'simple-documentation' ),
            [ $this, 'render_video_meta_box' ],
            DocumentationItems::POST_TYPE,
            'normal'
        );


        /**
         *  Note Meta Box
         */
        add_meta_box(
            CORE::SLUG . '-link',
            __( 'Link settings', 'simple-documentation' ),
            [ $this, 'render_link_meta_box' ],
            DocumentationItems::POST_TYPE,
            'normal'
        );


        /**
         *  Note Meta Box
         */
        add_meta_box(
            CORE::SLUG . '-file',
            __( 'File settings', 'simple-documentation' ),
            [ $this, 'render_file_meta_box' ],
            DocumentationItems::POST_TYPE,
            'normal'
        );
    }


    /**
     *  Render Note Meta Box
     */
    public function render_note_meta_box() {
        Loader::component( 'edit-meta-box-note' );
    }


    /**
     *  Render Video Meta Box
     */
    public function render_video_meta_box() {
        Loader::component( 'edit-meta-box-video' );
    }


    /**
     *  Render Link Meta Box
     */
    public function render_link_meta_box() {
        Loader::component( 'edit-meta-box-link' );
    }


    /**
     *  Render File Meta Box
     */
    public function render_file_meta_box() {
        Loader::component( 'edit-meta-box-file' );
    }


    /**
     *  Get Instance
     *
     *  @return EditScreen singleton instance
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
