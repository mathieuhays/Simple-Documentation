<?php
/**
 * Simple Documentation
 * Taxonomy Model - Documentation Type
 */

namespace SimpleDocumentation\Models;

class Documentation_Type extends Taxonomy {
	const TAXONOMY = 'simple-documentation-type';

	/**
	 * @return string
	 */
	public function get_icon_class() {
		switch ( $slug = $this->get_slug() ) {
			case 'video':
				$class_name = 'dashicons-video-alt3';
				break;

			case 'link':
				$class_name = 'dashicons-admin-links';
				break;

			case 'file':
				$class_name = 'dashicons-media-default';
				break;

			case 'note':
			default:
				$class_name = 'dashicons-align-left';
				break;
		}

		/**
		 * @param string $class_name
		 * @param string $slug - Documentation type slug
		 */
		$class_name = apply_filters( 'simple_documentation_type_icon_class', $class_name, $slug );

		if ( strpos( $class_name, 'dashicons' ) !== false ) {
			$class_name .= ' dashicons';
		}

		return $class_name;
	}

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
