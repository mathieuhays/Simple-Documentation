<?php
/**
 * Post Type Item Base Class
 */

namespace SimpleDocumentation\Models;

class Post_Type {
	/**
	 * @var bool
	 */
	protected static $is_bootstrapped = false;

	/**
	 * @var int
	 */
	protected $ID;

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
	 * @param \WP_Post|Int $post_mixed
	 */
	public function __construct( $post_mixed = null ) {
		$this->post = get_post( $post_mixed );
		$this->ID = $this->post->ID;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->ID;
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
		return apply_filters( 'the_content', $this->post->post_content );
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
	 * Whether the specified object is an instance of the current class
	 *
	 * @param mixed $object
	 *
	 * @return bool
	 */
	public static function is_instance( $object ) {
		return is_a( $object, get_called_class() );
	}

	/**
	 * Whether the two specified objects refers to the same post
	 *
	 * @param mixed $mixed_1
	 * @param mixed $mixed_2
	 *
	 * @return bool
	 */
	public static function equals( $mixed_1, $mixed_2 ) {
		if ( ! self::is_instance( $mixed_1 ) ||
			 ! self::is_instance( $mixed_2 )
		) {
			return false;
		}

		/**
		 * @var static $mixed_1
		 * @var static $mixed_2
		 */
		return $mixed_1->get_id() === $mixed_2->get_id();
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
}
