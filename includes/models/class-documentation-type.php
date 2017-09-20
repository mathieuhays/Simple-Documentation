<?php
/**
 * Simple Documentation
 * Taxonomy Model - Documentation Type
 */

namespace SimpleDocumentation\Models;

class Documentation_Type extends Taxonomy {
	const TAXONOMY = 'simple-documentation-type';

	/**
	 * @param array $custom_args
	 * @param string $post_type_slug
	 *
	 * @return bool|\WP_Error
	 */
	public static function register( $custom_args = [], $post_type_slug = null ) {
		return parent::register( wp_parse_args( $custom_args, [
			'labels' => [
				'name' => 'Types',
				'singular_name' => 'type',
			],
			'public' => false,
			'show_ui' => false,
			'show_admin_column' => true,
			'hierarchical' => true,
			'query_var' => false,
			'rewrite' => false,
		]), $post_type_slug );
	}
}
