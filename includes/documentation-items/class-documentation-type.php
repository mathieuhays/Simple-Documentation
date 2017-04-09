<?php
/**
 *  Simple Documentation - Documentation Type
 */

namespace SimpleDocumentation\DocumentationItems;

use SimpleDocumentation\Core;
use SimpleDocumentation\Utilities\Loader;

class DocumentationType {
	public $slug;
	public $label;
	public $icon;


	/**
	 *  Construct
	 *
	 *  @param  string  $slug
	 *  @param  string  $label
	 *  @param  string  $icon
	 */
	public function __construct( $slug, $label, $icon = null ) {
		$this->slug = $slug;
		$this->label = $label;
		$this->icon = $icon;
	}


	/**
	 *  Get Slug
	 *
	 *  @return string
	 */
	public function get_slug() {
		return $this->slug;
	}


	/**
	 *  Get Label
	 *
	 *  @return string
	 */
	public function get_label() {
		return $this->label;
	}


	/**
	 *  Get Icon Classname
	 *
	 *  @return string
	 */
	public function get_icon_classname() {
		$class = $this->icon;

		if ( strpos( $this->icon, 'dashicon' ) !== false ) {
			return sprintf( 'dashicons %s', $class );
		}

		return (string) $class;
	}


	/**
	 *  Get Icon
	 *
	 *  @return string
	 */
	public function get_icon() {
		$class = $this->get_icon_classname();

		if ( ! empty( $class ) ) {
			return sprintf(
				'<div class="%s"></div>',
				$class
			);
		}

		return '';
	}


	/**
	 * Setup Type
	 */
	public function setup() {
		// Register Meta Box
		add_action( 'add_meta_boxes_' . DocumentationItems::POST_TYPE, [ $this, 'register_meta_box' ] );

		// Add Custom Class to meta box
		add_action(
			sprintf(
				'postbox_classes_%s_%s',
				DocumentationItems::POST_TYPE,
				Core::SLUG . '-' . $this->get_slug()
			),
			[ $this, 'add_custom_meta_box_classname' ]
		);
	}


	/**
	 * Register Meta Box for given Type
	 */
	public function register_meta_box() {
		$meta_box_label = __( '%s settings', 'simple-documentation' );

		add_meta_box(
			Core::SLUG . '-' . $this->get_slug(),
			sprintf( $meta_box_label, $this->get_label() ),
			[ $this, 'render_meta_box' ],
			DocumentationItems::POST_TYPE,
			'normal'
		);
	}


	/**
	 * Render Meta Box Based on the Type's Slug
	 */
	public function render_meta_box() {
		Loader::component( 'edit-meta-box-' . $this->get_slug() );
	}

	/**
	 * Add Custom Class For Meta Box
	 *
	 * @param array $classes
	 * @return array
	 */
	public function add_custom_meta_box_classname( $classes = [] ) {
		$classes[] = 'js-simpledoc-meta-box';
		$classes[] = 'js-simpledoc-meta-box--' . $this->get_slug();

		return $classes;
	}
}
