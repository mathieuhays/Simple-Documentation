<?php
/**
 * Simple Documentation
 * Model - Documentation Category
 */

namespace SimpleDocumentation\Models;

class Documentation_Category extends Taxonomy {
	const TAXONOMY = 'simpledocumentation-category';

	public static function register( $custom_args = [], $post_type_slug = null ) {
		return parent::register( wp_parse_args( $custom_args, [
			'labels' => [
				'name' => 'Categories',
				'singular_name' => 'Category',
			],
			'public' => false,
			'show_ui' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
			'query_var' => false,
			'rewrite' => false,
		]), $post_type_slug );
	}
}
