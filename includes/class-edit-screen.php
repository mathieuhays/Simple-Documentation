<?php
/**
 *  Simple Documentation - Edit Screen
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Models\Documentation;
use SimpleDocumentation\Models\Documentation_Type;
use SimpleDocumentation\Utilities\Loader;

class Edit_Screen {
	private $documentation;
	private $post;

	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  Load JavaScript
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_js' ] );

		/**
		 * On Item Save
		 */
		add_action( 'save_post_' . Documentation::POST_TYPE, [ $this, 'on_save' ], 12, 3 );

		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
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
			SIMPLEDOC_SLUG . '-edit-screen',
			SIMPLEDOC_JS_URL . '/edit-screen.js',
			[ 'jquery' ],
			SIMPLEDOC_VERSION,
			true
		);

		wp_localize_script(
			SIMPLEDOC_SLUG . '-edit-screen',
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
		$doc = Documentation::from_post( $post );

		/**
		 * Save Documentation Type
		 * Type is mandatory so we don't do anything if empty.
		 */
		if ( ! empty( $_REQUEST['documentation-type'] ) ) {
			$type = Documentation_Type::from_id( $_REQUEST['documentation-type'] );

			if ( ! empty( $type ) ) {
				$doc->update_type( $type );
			}
		}

		/**
		 * @TODO handle attachments -- $_REQUEST['sd_attachments'] - comma separated list of attachment ids.
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
			[ $this, 'render_meta_box' ],
			Documentation::POST_TYPE,
			'normal',
			'default',
			[
				'component' => 'meta-box-attachments',
			]
		);

		/**
		 * Documentation Type
		 */
		add_meta_box(
			'simpledocumentation-type',
			'Type',
			[ $this, 'render_meta_box' ],
			Documentation::POST_TYPE,
			'side',
			'default',
			[
				'component' => 'meta-box-types',
			]
		);

		/**
		 * User Role Restriction
		 */
		add_meta_box(
			'simpledocumentation-user-roles',
			'Restrict access to user roles',
			[ $this, 'render_meta_box' ],
			Documentation::POST_TYPE,
			'side',
			'default',
			[
				'component' => 'meta-box-user-roles',
			]
		);

		/**
		 * Multisite options
		 */
		add_meta_box(
			'simpledocumentation-multisite-options',
			'Multisite Options',
			[ $this, 'render_meta_box' ],
			Documentation::POST_TYPE,
			'normal',
			'default',
			[
				'component' => 'meta-box-multisite',
			]
		);
	}

	/**
	 * @param \WP_Post $post
	 * @param array $args
	 */
	public function render_meta_box( $post, $options = [] ) {
		if ( empty( $this->post ) ) {
			$this->post = $post;
			$this->documentation = Documentation::from_post( $this->post );
		}

		if ( isset( $options['args']['component'] ) ) {
			Loader::component(
				$options['args']['component'],
				[
					'post' => $this->post,
					'documentation' => $this->documentation,
				]
			);
		}
	}

	/**
	 * @return Edit_Screen
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
