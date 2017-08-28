<?php
/**
 *  Simple Documentation - Edit Screen
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Utilities\Loader;

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
		add_action( 'save_post_' . DocumentationItem::POST_TYPE, [ $this, 'on_save' ], 12, 3 );

		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
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
				'metaboxes' => [],
				'current_type' => false,
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
		//.

		/**
		 * @TODO handle attachments -- $_REQUEST['sd_attachments'] - comma separated list of attachment ids.
		 */

		/**
		 * @TODO handle video
		 */

		/**
		 * @TODO handle multisite option
		 */

		/**
		 * @TODO handle user role restriction option
		 */
	}


	/**
	 * Add attachment meta box to the edit screen
	 */
	public function register_meta_boxes() {
		/**
		 * Attachments Meta Boxes
		 */
		add_meta_box(
			'simpledocumentation-attachment',
			'Attachments',
			[ $this, 'attachment_meta_box_callback' ],
			DocumentationItem::POST_TYPE,
			'side'
		);

		/**
		 * User Role Restriction
		 */
		add_meta_box(
			'simpledocumentation-user-roles',
			'Restrict access to user roles',
			[ $this, 'user_roles_meta_box_callback' ],
			DocumentationItem::POST_TYPE,
			'side'
		);

		/**
		 * Multisite options
		 */
		add_meta_box(
			'simpledocumentation-multisite-options',
			'Multisite Options',
			[ $this, 'multisite_meta_box_callback' ],
			DocumentationItem::POST_TYPE,
			'side'
		);
	}


	/**
	 * Attachment Meta Box Component Loader
	 */
	public function attachment_meta_box_callback() {
		Loader::component( 'meta-box-attachments' );
	}

	/**
	 * Restrict User Roles Meta Box Component Loader
	 */
	public function user_roles_meta_box_callback() {
		Loader::component( 'meta-box-user-roles' );
	}

	public function multisite_meta_box_callback() {
		Loader::component( 'meta-box-multisite' );
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
