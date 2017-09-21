<?php
/**
 *  Simple Documentation - Plugin Page
 *  Handle the main plugin page which itself contains both the listing view
 *  and the single view
 */

namespace SimpleDocumentation;

use SimpleDocumentation\Models\Documentation;
use SimpleDocumentation\Utilities\Loader;

class Plugin_Page {
	const ITEM_ID_PARAM = 'documentation_id';

	/**
	 *  Bootstrap
	 */
	public function bootstrap() {
		/**
		 *  Register Menu Item for both regular & multisite installations
		 */
		add_action( 'admin_menu', [ $this, 'register' ] );
		add_action( 'network_admin_menu', [ $this, 'register' ] );

		/**
		 *  Highlight plugin menu item when editing a documentation item
		 */
		add_filter( 'parent_file', [ $this, 'highlight_menu_item' ] );

		/**
		 *  Hide Quick Edit for Documentation items
		 */
		add_action( 'admin_head', [ $this, 'hide_quick_edit' ] );

		/**
		 * Load plugin assets
		 */
		add_action( 'admin_init', [ $this, 'load_plugin_assets' ] );
	}


	public function load_plugin_assets() {
		if ( ! $this->is_plugin_page() ) {
			return;
		}

		/**
		 * @TODO add option and/or filter to disable use of Google Fonts
		 */
		wp_enqueue_style( 'sp-roboto-font', 'https://fonts.googleapis.com/css?family=Roboto:400,700,900' );

		// Meta box dependency
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'post' );

		// Plugin
		wp_enqueue_style(
			SIMPLEDOC_SLUG . '-main',
			SIMPLEDOC_CSS_URL . '/simple-documentation.css',
			false,
			SIMPLEDOC_VERSION
		);

		// Plugin
		wp_enqueue_script(
			SIMPLEDOC_SLUG . '-list',
			SIMPLEDOC_JS_URL . '/list.js',
			false,
			SIMPLEDOC_VERSION
		);
	}


	/**
	 *  Register Plugin Menu Items & page
	 */
	public function register() {
		global $submenu;

		add_menu_page(
			__( 'Documentation', 'simple-documentation' ),
			__( 'Documentation', 'simple-documentation' ),
			'manage_options', // @TODO should be more open
			$this->get_slug(),
			[ $this, 'render' ],
			'dashicons-editor-help'
		);

		add_submenu_page(
			$this->get_slug(),
			__( 'Manage', 'simple-documentation' ),
			__( 'Manage', 'simple-documentation' ),
			'manage_options', // @TODO should be a custom cap
			$this->get_manage_link( 'relative' ),
			false
		);

		if ( isset( $submenu[ $this->get_slug() ] ) ) {
			$submenu[ $this->get_slug() ][0][0] = __( 'View', 'simple-documentation' );
		}
	}


	/**
	 *  Render Plugin page
	 */
	public function render() {
		Loader::template( 'plugin-page' );
	}


	/**
	 *  Highlight Plugin menu Item when editing a Documentation item
	 *
	 *  @param  string  $parent_file
	 *  @return string
	 */
	public function highlight_menu_item( $parent_file ) {
		global $self, $submenu_file;

		$force_highlight = false;

		// Editing a Documentation Item
		if ( $self === 'post.php' && isset( $_GET['post'] ) ) {
			$post_id = (int) $_GET['post'];

			if ( get_post_type( $post_id ) === Documentation::POST_TYPE ) {
				$force_highlight = true;
			}
		}

		// Creating a new documentation item
		if ( $self === 'post-new.php' && isset( $_GET['post_type'] ) &&
			 $_GET['post_type'] === Documentation::POST_TYPE ) {
			$force_highlight = true;
		}

		if ( $force_highlight ) {
			$parent_file = $this->get_slug();
			$submenu_file = $this->get_manage_link( 'relative' );
		}

		return $parent_file;
	}


	/**
	 *  Hide Quick Edit Option on the post type listing page
	 */
	public function hide_quick_edit() {
		printf(
			'<style>.type-%s .row-actions .inline { display: none; }</style>',
			Documentation::POST_TYPE
		);
	}


	/**
	 *  Get Plugin Page Slug
	 *
	 *  @return string
	 */
	public function get_slug() {
		return SIMPLEDOC_SLUG . '-top-level';
	}


	/**
	 *  Get Plugin Page Permalink
	 *
	 *  @return string
	 */
	public function get_permalink() {
		return add_query_arg(
			[
				'page' => $this->get_slug(),
			],
			admin_url( 'admin.php' )
		);
	}


	/**
	 *  Get Manage link (WordPress Listing Screen for a given post type)
	 *
	 *  @param  string  $format - 'absolute' or 'relative'
	 *  @return string
	 */
	public function get_manage_link( $format = 'absolute' ) {
		$relative_url = add_query_arg(
			[
				'post_type' => Documentation::POST_TYPE,
			],
			'edit.php'
		);

		if ( $format == 'relative' ) {
			return $relative_url;
		}

		// absolute
		return admin_url( $relative_url );
	}


	/**
	 *  Get 'Add new' Link
	 *
	 *  @return string
	 */
	public function get_add_new_link() {
		return add_query_arg(
			[
				'post_type' => Documentation::POST_TYPE,
			],
			admin_url( 'post-new.php' )
		);
	}


	/**
	 *  Get Plugin View Link for Given Documentation Item
	 *
	 *  @param  Documentation   $item
	 *  @return string
	 */
	public function get_view_link_for_item( $item ) {
		return add_query_arg(
			[
				self::ITEM_ID_PARAM => $item->get_id(),
			],
			$this->get_permalink()
		);
	}


	/**
	 *  Whether the current page is the listing view or not
	 *
	 *  @return bool
	 */
	public function is_plugin_page() {
		return is_admin() &&
			isset( $_GET['page'] ) &&
			$_GET['page'] === $this->get_slug();
	}


	/**
	 *  Whether the current is a single view or not
	 *
	 *  @return bool
	 */
	public function is_single() {
		return $this->is_plugin_page() &&
			! empty( $this->get_documentation_id() );
	}


	/**
	 *  Get Documentation Item ID for the single view
	 *
	 *  @return Int
	 */
	public function get_documentation_id() {
		if ( isset( $_GET[ self::ITEM_ID_PARAM ] ) ) {
			$item_id = (int) $_GET[ self::ITEM_ID_PARAM ];

			if ( ! empty( $item_id ) &&
				get_post_type( $item_id ) === Documentation::POST_TYPE ) {
				return $item_id;
			}
		}

		return 0;
	}


	/**
	 * @return Plugin_Page
	 */
	public static function instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
