<?php
/**
 *  Post Type Item
 */

namespace SimpleDocumentation;

class PostType_Item {
    /**
     *  @var WP_Post
     */
    private $post;

    /**
     *  @var int
     */
    public $ID;


    /**
     *  Construct
     *
     *  @param  WP_Post|Int $post - optional - default to current post
     */
    public function __construct( $post = null ) {
        $this->post = get_post( $post );
        $this->ID = $this->post->ID;
    }


    /**
     *  Get ID
     *
     *  @return int
     */
    public function get_id() {
        return $this->ID;
    }


    /**
     *  Get Title
     *
     *  @return string
     */
    public function get_title() {
        return get_the_title( $this->get_id() );
    }


    /**
     *  Get Permalink
     *
     *  @return string
     */
    public function get_permalink() {
        return get_permalink( $this->get_id() );
    }


    /**
     *  Get Post Content
     *
     *  @return string
     */
    public function get_content() {
        return apply_filters( 'the_content', $this->post->post_content );
    }


    /**
     *  Whether this post is the current post active in The Loop
     *
     *  @return bool
     */
    public function is_current() {
        return $this->get_id() === get_the_id();
    }
}
