<?php
/**
 *  Simple Documentation - Edit Screen
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Utilities\Loader;
use SimpleDocumentation\Core;
use SimpleDocumentation\DocumentationItems\DocumentationItem;
use SimpleDocumentation\DocumentationItems\DocumentationItems;
use SimpleDocumentation\DocumentationItems\DocumentationTypes;

class EditScreen {
	private static $instance;


	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  Add type selector below title
		 */
		add_action( 'edit_form_after_title', [ $this, 'render_type_selector' ] );

		/**
		 *  Load JavaScript
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_js' ] );

		/**
		 * On Item Save
		 */
		add_action( 'save_post_' . DocumentationItems::POST_TYPE, [ $this, 'on_save' ], 12, 3 );
	}


	/**
	 *  Render Type Selector
	 */
	public function render_type_selector() {
		Loader::component( 'type-selector' );
	}


	/**
	 *  Enqueue JavaScript
	 *
	 * @param string $hook
	 */
	public function enqueue_js( $hook ) {
		/**
		 *  Restrict Asset loading for the edit screen
		 */
		if ( $hook !== 'post-new.php' &&
		     $hook !== 'post.php' ) {
			return;
		}

		$current_type = DocumentationTypes::get_instance()->get_default();

		if ( $hook === 'post.php' ) {
			$current_type = (new DocumentationItem)->get_type();
		}

		wp_enqueue_script(
			Core::SLUG . '-edit-screen',
			SIMPLEDOC_JS_URL . '/edit-screen.js',
			[ 'jquery' ],
			SIMPLEDOC_VERSION,
			true
		);

		wp_localize_script(
			CORE::SLUG . '-edit-screen',
			'simpleDocumentationEditScreen',
			[
				'metaboxes' => array_map( function( $type ) {
					return $type->get_slug();
				}, DocumentationTypes::get_instance()->get_all() ),
				'current_type' => $current_type->get_slug(),
			]
		);
	}


	/**
	 * On Item Save
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 * @param bool $update
	 */
	public function on_save( $post_id, $post, $update ) {
		if ( ! isset( $_REQUEST['simpledocumentation_type'] ) ) {
			return;
		}

		$type_slug = $_REQUEST['simpledocumentation_type'];
		$type = DocumentationTypes::get_instance()->get( $type_slug );

		if ( $type === false ) {
			/**
			 * Un-recognised type, something went wrong here
			 */
			return;
		}

		$data_field_name = sprintf( 'simpledocumentation_%s_data', $type->get_slug() );

		if ( ! isset( $_REQUEST[ $data_field_name ] ) ) {
			/**
			 * Data type should provide a data field to be valid
			 */
			return;
		}

		$item = new DocumentationItem( $post );
		$item->set_type( $type );
	}


	/**
	 *  Get Instance
	 *
	 *  @return EditScreen singleton instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
