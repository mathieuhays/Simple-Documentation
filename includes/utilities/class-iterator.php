<?php
/**
 *  Simple Documentation - Utilities - Iterator
 *  Replicates the WordPress loop but for other objects than WP_Posts
 *  Enable to loop custom array of data accros different components
 */

namespace SimpleDocumentation\Utilities;

class Iterator {
    private $items = [];
    private $current = -1;


    /**
     *  Construct
     *
     *  @param  array   $items
     */
    public function __construct( $items ) {
        /**
         *  Ensure we have a linear array (no gap between indexes)
         */
        $this->items = array_values( $items );
    }


    /**
     *  Have Items - replicate have_posts
     *
     *  @return bool
     */
    public function have_items() {
        return isset( $this->items[ $this->current + 1 ] );
    }


    /**
     *  Load Next Item - replicate the_post
     *  Returns current item at the same time
     *
     *  @return mixed or false when there's no more items to go through
     */
    public function the_item() {
        if ( $this->have_items() ) {
            $this->current++;
            return $this->get_current_item();
        }

        return false;
    }


    /**
     *  Get Current Item
     *
     *  @return mixed or false if reached the end of the iterator
     */
    public function get_current_item() {
        if ( !isset( $this->items[ $this->current ] ) ) {
            return false;
        }

        return $this->items[ $this->current ];
    }


    /**
     *  Whether the item is the first in the iterator or not
     *
     *  @return bool
     */
    public function current_item_is_first() {
        return $this->current === 0;
    }


    /**
     *  Rewind Iterator - starts over with the first item
     */
    public function rewind() {
        $this->current = -1;
    }
}
