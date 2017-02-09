<?php
/**
 *  Simple Documentation - Utilities
 *  Namespaced functions
 */

namespace SimpleDocumentation\Utilities;


/**
 *  Get Post ID from a variant of post object
 *
 *  @param  Int|WP_Post     $post_mixed
 *  @return Int
 */
function get_the_id( $post_mixed = null ) {
    if ( empty( $post_mixed ) ) {
        return get_the_id();
    }

    if ( is_numeric( $post_mixed ) ) {
        return (int) $post_mixed;
    }

    if ( is_a( $post_mixed, 'WP_Post' ) ) {
        return $post_mixed->ID;
    }

    return 0;
}
