<?php
/**
 *  Simple Documentation - Documentation Items
 */

namespace SimpleDocumentation\DocumentationItems;

use SimpleDocumentation\Utilities\PostTypeColumnHelper;

class DocumentationItems {
	/**
	 *  @var DocumentationItems singleton instance
	 */
	private static $instance;

	const POST_TYPE = 'simpledocumentation';

	const META_ITEM_TYPE = 'simpledoc_item_type';

	const META_HIGHLIGHT = 'simpledoc_item_highlight';


	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		add_action( 'init', [ $this, 'register_post_type' ] );

		$this->register_custom_columns();
	}


	/**
	 *  Register Post Type
	 */
	public function register_post_type() {
		// Create ACF post type
		$labels = array(
			'name' => __( 'Documentation Items', 'simple-documentation' ),
			'singular_name' => __( 'Simple Documentation', 'simple-documentation' ),
			'add_new' => __( 'Add New' , 'simple-documentation' ),
			'add_new_item' => __( 'Add New Item' , 'simple-documentation' ),
			'edit_item' => __( 'Edit Item' , 'simple-documentation' ),
			'new_item' => __( 'New Item' , 'simple-documentation' ),
			'view_item' => __( 'View Item', 'simple-documentation' ),
			'search_items' => __( 'Search Items', 'simple-documentation' ),
			'not_found' => __( 'No Items found', 'simple-documentation' ),
			'not_found_in_trash' => __( 'No Items found in Trash', 'simple-documentation' ),
		);

		register_post_type( self::POST_TYPE, [
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			// '_builtin' =>  false,
			'capability_type' => 'page',
			'hierarchical' => true,
			'rewrite' => false,
			// 'query_var' => self::POST_TYPE,
			'supports' => [
				'title',
				// 'editor',
				'revisions',
			],
			'show_in_menu'	=> false,
		]);
	}


	/**
	 *  Register Custom Columns
	 */
	public function register_custom_columns() {
		$columns = new PostTypeColumnHelper( self::POST_TYPE );

		$columns->add(
			__( 'Type', 'simpledocumentation' ),
			[ 'before' => 'title', 'size' => '50px' ],
			function( $post_id ) {
				$document_item = new DocumentationItem( $post_id );
				$document_type = $document_item->get_type();

				if ( ! empty( $document_type ) ) {
					echo $document_type->get_icon();
				}
			}
		);
	}


	/**
	 *  Query Documentation Items
	 *
	 *  @param  array   $custom_args
	 *  @return array
	 */
	public function query( $custom_args = [] ) {
		$args = wp_parse_args( $custom_args, [
			'post_type' => self::POST_TYPE,
		]);

		$items = (new \WP_Query( $args ))->posts;

		if ( empty( $items ) ) {
			return [];
		}

		return array_map( [ $this, 'convert_post_to_documentation_item' ], $items );
	}


	/**
	 *  Convert WP_Post to DocumentationItem
	 *
	 *  @param  WP_Post     $post
	 *  @return DocumentationItem
	 */
	public function convert_post_to_documentation_item( $post ) {
		return new DocumentationItem( $post );
	}


	/**
	 *  Get Highlighted Items
	 *
	 *  @return array
	 */
	public function get_highlighted_items() {
		// @TODO

		return [];
	}


	/**
	 *  Get instance
	 *
	 *  @return DocumentationItems singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
