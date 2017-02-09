<?php
/**
 *  Simple Documentation - Documentation Item
 */

namespace SimpleDocumentation\DocumentationItems;

use SimpleDocumentation\DocumentationItems\DocumentationItems;
use SimpleDocumentation\DocumentationItems\DocumentationTypes;

class DocumentationItem {
    public $ID;
    public $post;

    /**
     *  Construct
     *
     *  @param  Int|WP_Post     $post_mixed
     */
    public function __construct( $post_mixed ) {
        $this->ID = Utilities\get_post_id( $post_mixed );
    }


    /**
     *  Get Post ID For Object
     *
     *  @return int
     */
    public function get_id() {
        return $this->ID;
    }


    /**
     *  Get WP_Post for object
     *
     *  @return WP_Post
     */
    public function get_post() {
        if ( empty( $this->post ) ) {
            $this->post = get_post( $this->get_id() );
        }

        return $this->post;
    }


    /**
     *  Get Item Title
     *
     *  @return string
     */
    public function get_title() {
        return get_the_title( $this->get_id() );
    }


    /**
     *  Get Item Content
     *
     *  @return string
     */
    public function get_content() {
        $post = $this->get_post();

        if ( !empty( $post->post_content ) ) {
            return apply_filters( 'the_content', $post->post_content );
        }

        return '';
    }


    /**
     *  Get View Link
     *
     *  @return string
     */
    public function get_view_link() {
        // @TODO
    }


    /**
     *  Get Edit Link
     *
     *  @return string
     */
    public function get_edit_link() {
        return add_query_arg(
            [
                'post' => $this->get_id(),
                'action' => 'edit'
            ],
            admin_url( 'post.php' )
        );
    }


    /**
     *  Get Type
     *
     *  @return DocumentationType
     */
    public function get_type() {
        $type = get_post_meta(
            $this->get_id(),
            DocumentationItems::META_ITEM_TYPE,
            true
        );

        if (  empty( $type ) ) {
            return DocumentationTypes::get_instance()->get_default();
        }

        return DocumentationTypes::get_instance()->get( $type );
    }


    /**
     *  Item Has Type
     *
     *  @param  DocumentationType|string    $type
     *  @return bool
     */
    public function has_type( $type ) {
        $current_type = $this->get_type();

        if ( is_string( $type ) ) {
            return $current_type->get_slug() == $type;
        } else if ( is_a( $type, '\SimpleDocumentation\DocumentationItems\DocumentationType' ) ) {
            return $current_type->get_slug() == $type->get_slug();
        }

        return false;
    }


    /**
     *  Whether this item has been highlighted by the editor or not
     *
     *  @return bool
     */
    public function is_highlighted() {
        // @TODO

        return get_post_meta(
            $this->get_id(),
            DocumentationItems::META_HIGHLIGHT,
            true
        );
    }
}
