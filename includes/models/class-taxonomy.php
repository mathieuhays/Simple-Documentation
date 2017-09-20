<?php
/**
 * Simple Documentation
 * Taxonomy Item
 */

namespace SimpleDocumentation\Models;

class Taxonomy extends Base_Model {
	/**
	 * @var \WP_Term
	 */
	protected $term;

	/**
	 * Set as the default category taxonomy (for posts)
	 * Should be overridden in child class
	 */
	const TAXONOMY = 'category';

	/**
	 * TaxonomyItem constructor.
	 *
	 * @param \WP_Term $term
	 */
	public function __construct( $term ) {
		$this->term = $term;
	}

	/**
	 * @return \WP_Term
	 */
	public function get_wp_term() {
		return $this->term;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->get_wp_term()->term_id;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->get_wp_term()->slug;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->get_wp_term()->name;
	}

	/**
	 * @return bool|int|\WP_Error
	 */
	public function delete() {
		return wp_delete_term( $this->get_id(), static::TAXONOMY );
	}

	/**
	 * @param array $custom_args
	 * @param string $post_type_slug
	 *
	 * @return bool|\WP_Error
	 */
	public static function register( $custom_args = [], $post_type_slug = null ) {
		$built_in_terms = [
			'category',
			'post_tag',
			'nav_menu',
			'link_category',
			'post_format',
		];

		// This class represents a built-in taxonomy so we don't need to register
		if ( in_array( static::TAXONOMY, $built_in_terms, true ) ) {
			return false;
		}

		$reserved_words = [
			'attachment',
			'attachment_id',
			'author',
			'author_name',
			'calendar',
			'cat',
			'category',
			'category__and',
			'category__in',
			'category__not_in',
			'category_name',
			'comments_per_page',
			'comments_popup',
			'customize_messenger_channel',
			'customized',
			'cpage',
			'day',
			'debug',
			'error',
			'exact',
			'feed',
			'fields',
			'hour',
			'link_category',
			'm',
			'minute',
			'monthnum',
			'more',
			'name',
			'nav_menu',
			'nonce',
			'nopaging',
			'offset',
			'order',
			'orderby',
			'p',
			'page',
			'page_id',
			'paged',
			'pagename',
			'pb',
			'perm',
			'post',
			'post__in',
			'post__not_in',
			'post_format',
			'post_mime_type',
			'post_status',
			'post_tag',
			'post_type',
			'posts',
			'posts_per_archive_page',
			'posts_per_page',
			'preview',
			'robots',
			's',
			'search',
			'second',
			'sentence',
			'showposts',
			'static',
			'subpost',
			'subpost_id',
			'tag',
			'tag__and',
			'tag__in',
			'tag__not_in',
			'tag_id',
			'tag_slug__and',
			'tag_slug__in',
			'taxonomy',
			'tb',
			'term',
			'theme',
			'type',
			'w',
			'withcomments',
			'withoutcomments',
			'year',
		];

		if ( in_array( static::TAXONOMY, $reserved_words ) ) {
			return new \WP_Error( 'invalid_taxonomy', 'Taxonomy can\'t be a reserved word.' );
		}

		register_taxonomy( static::TAXONOMY, $post_type_slug, wp_parse_args( $custom_args, [
			'label' => ucfirst( static::TAXONOMY ),
		]));

		return true;
	}

	/**
	 * @param string $post_type_slug
	 *
	 * @return bool
	 */
	public static function associate_to_post_type( $post_type_slug ) {
		return register_taxonomy_for_object_type( static::TAXONOMY, $post_type_slug );
	}

	/**
	 * @param string $name
	 * @param array $custom_args
	 */
	public static function insert( $name, $custom_args = [] ) {
		wp_insert_term( $name, static::TAXONOMY, $custom_args );
	}

	/**
	 * @param int|\WP_Term $mixed
	 * @return static|false
	 */
	public static function from_term( $mixed ) {
		$term = get_term( $mixed, static::TAXONOMY );

		if ( empty( $term ) ||
			 is_wp_error( $term ) ) {
			return false;
		}

		return new static( $term );
	}

	/**
	 * @param int $term_id
	 *
	 * @return false|static
	 */
	public static function from_id( $term_id ) {
		return self::from_term( $term_id );
	}

	/**
	 * @return static[]
	 */
	public static function get_all() {
		$terms = get_terms([
			'taxonomy' => static::TAXONOMY,
			'hide_empty' => false,
		]);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return [];
		}

		return array_map( [ get_called_class(), 'from_term' ], $terms );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public static function get_for_post( $post ) {
		$terms = get_the_terms( $post, static::TAXONOMY );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return [];
		}

		return array_map( [ get_called_class(), 'from_term' ], $terms );
	}
}
