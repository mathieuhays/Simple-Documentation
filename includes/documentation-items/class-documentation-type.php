<?php
/**
 *  Simple Documentation - Documentation Type
 */

namespace SimpleDocumentation\DocumentationItems;

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
}
