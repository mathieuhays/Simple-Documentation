<?php
/**
 * Post Type Item Base Class
 */

namespace SimpleDocumentation\Models;

class Post_Type extends Base_Model {
	/**
	 * @var \WP_Post
	 */
	protected $post;

	/**
	 * To be overridden in child class
	 */
	const POST_TYPE = 'post';

	/**
	 * PostTypeItem constructor.
	 *
	 * @param \WP_Post $post
	 */
	public function __construct( $post ) {
		$this->post = get_post( $post );
	}

	/**
	 * @return \WP_Post
	 */
	public function get_post() {
		return $this->post;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->get_post()->ID;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return get_the_title( $this->get_id() );
	}

	/**
	 * @return false|string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * @return string
	 */
	public function get_content() {
		$content = apply_filters( 'the_content', $this->get_post()->post_content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

	/** ======
	 *
	 * Static
	 *
	 * ======= */

	/**
	 * Register Post Type
	 *
	 * @param array $custom_args - optional arguments for register_post_type()
	 *
	 * @return \WP_Error|\WP_Post_Type
	 */
	public static function register( $custom_args = [] ) {
		$is_built_in_type = in_array(
			static::POST_TYPE,
			[
				'post',
				'page',
				'attachment',
				'revision',
				'nav_menu_item',
				'custom_css',
				'customize_changeset',
			]
		);

		if ( $is_built_in_type ) {
			/**
			 * Built-in Post-types don't need to be registered
			 * Returns the WP_Post_Type object so we remain consistent with what register_post_type() returns.
			 */
			return get_post_type_object( static::POST_TYPE );
		}

		$args = wp_parse_args( $custom_args, [
			'label' => ucfirst( static::POST_TYPE ),
		]);

		return register_post_type( static::POST_TYPE, $args );
	}

	/**
	 * @param \WP_Post $wp_post
	 *
	 * @return static|\WP_Error|false
	 */
	public static function from_post( $wp_post ) {
		if ( ! is_a( $wp_post, 'WP_Post' ) ) {
			return new \WP_Error(
				'invalid-argument',
				'Invalid parameter provided. Expected an argument of type: ' . \WP_Post::class
			);
		}

		if ( get_post_type( $wp_post ) !== static::POST_TYPE ) {
			return new \WP_Error(
				'invalid-type',
				'Invalid parameter provided. Expected a post of type: ' . static::POST_TYPE
			);
		}

		return new static( $wp_post );
	}

	/**
	 * Get Item based on an ID.
	 * Alias for from_posts( $post_id ).
	 *
	 * @param int $post_id
	 * @return static|false
	 */
	public static function from_id( $post_id ) {
		$wp_post = get_post( $post_id );

		if ( empty( $wp_post ) ) {
			return false;
		}

		return static::from_post( $wp_post );
	}

	/**
	 * Setup query for this post type
	 *
	 * @param array $args
	 * @return \WP_Query
	 */
	public static function query( $args = [] ) {
		return new \WP_Query( wp_parse_args( $args, [
			'post_type' => static::POST_TYPE,
		]));
	}

	/**
	 * Get Items for given page index.
	 *
	 * @param int $page_number
	 * @param int $number_of_post optional - defaults to default WordPress posts per page option
	 *
	 * @return static[]
	 */
	public static function get_page( $page_number = 1, $number_of_post = null ) {
		if ( $number_of_post === null ) {
			$number_of_post = get_option( 'posts_per_page', 10 );
		}

		$query = static::query([
			'paged' => $page_number,
			'posts_per_page' => $number_of_post,
		]);

		return array_map( [ get_called_class(), 'from_post' ], $query->posts );
	}

	/**
	 * @param Taxonomy $taxonomy
	 * @param int $limit
	 *
	 * @return static[]
	 */
	public static function get_for_taxonomy( $taxonomy, $limit = -1 ) {
		$query = new \WP_Query([
			'post_type' => static::POST_TYPE,
			'posts_per_page' => $limit,
			'tax_query' => [
				[
					'taxonomy' => $taxonomy::TAXONOMY,
					'terms' => $taxonomy->get_id(),
				],
			],
		]);

		if ( ! $query->have_posts() ) {
			return [];
		}

		return array_map(
			[ get_called_class(), 'from_post' ],
			$query->posts
		);
	}
}
