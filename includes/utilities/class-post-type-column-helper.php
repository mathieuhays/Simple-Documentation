<?php
/**
 *  Simple Documentation - Post Type Column Manager
 *  Helps adding/removing columns on the post type posts listing.
 */

namespace SimpleDocumentation\Utilities;

class PostTypeColumnHelper {
	/**
	 * @var string Post Type
	 */
	private $post_type;

	/**
	 * @var array - List of custom columns
	 */
	private $columns = [];

	/**
	 * @var array[] - List of default columns having custom ones to be displayed before them.
	 */
	private $before = [];

	/**
	 * @var array[] - List of default columns having custom ones to be displayed after them.
	 */
	private $after = [];

	/**
	 * @var string[] - List of columns meant to be removed
	 */
	private $remove = [];

	/**
	 * @var string[] - List of columns ids that have already been processed
	 */
	private $done = [];

	/**
	 * @var string[] - List of custom column size (associated with their column slug)
	 */
	private $sizes = [];

	/**
	 * @param  string  $post_type
	 */
	public function __construct( $post_type = null ) {
		$this->post_type = empty( $post_type ) ? 'post' : $post_type;

		$this->bootstrap();
	}

	public function bootstrap() {
		/**
		 * Handles which content is displayed where
		 */
		add_action(
			sprintf( 'manage_%s_posts_custom_columns', $this->post_type ),
			[ $this, 'handle_column_content' ],
			12,
			2
		);

		/**
		 * Add/Remove Columns
		 */
		add_filter(
			sprintf( 'manage_%s_posts_columns', $this->post_type ),
			[ $this, 'handle_columns' ],
			12
		);

		/**
		 * Add Custom CSS in case we use a custom size for the column
		 */
		add_action( 'admin_head', [ $this, 'handle_column_size' ] );
	}

	/**
	 * Trigger Content Callback if it has been registered with this class.
	 * The callback is meant to echo content.
	 *
	 * @param  string  $column_slug
	 * @param  int     $post_id
	 */
	public function handle_column_content( $column_slug, $post_id ) {
		// Skip if we haven't registered custom columns
		if ( empty( $this->columns ) || ! isset( $this->columns[ $column_slug ] ) ) {
			return;
		}

		call_user_func( $this->columns[ $column_slug ]['callback'], $post_id );
	}

	/**
	 * Handle Columns (re-order, add, remove, etc..)
	 *
	 * @param  array   $default_columns
	 * @return array
	 */
	public function handle_columns( $default_columns ) {
		// Nothing to do here so we return early
		if ( empty( $this->columns ) && empty( $this->remove ) ) {
			return $default_columns;
		}

		$final = [];

		// Remove Columns
		if ( ! empty( $this->remove ) ) {
			foreach ( $this->remove as $key_to_remove ) {
				if ( isset( $default_columns[ $key_to_remove ] ) ) {
					unset( $default_columns[ $key_to_remove ] );
				}
			}
		}

		// Loop through already declared columns
		foreach ( $default_columns as $column_id => $column_label ) {
			// Check if we need to insert one of our custom columns before this column
			if ( isset( $this->before[ $column_id ] ) ) {
				foreach ( $this->before[ $column_id ] as $before_column_slug ) {
					$final[ $before_column_slug ] = $this->columns[ $before_column_slug ]['label'];
					$this->done[] = $before_column_slug;
				}
			}

			// Add actual column
			$final[ $column_id ] = $column_label;

			// Check if we need to insert one of our custom column after this column
			if ( isset( $this->after[ $column_id ] ) ) {
				foreach ( $this->after[ $column_id ] as $after_column_slug ) {
					$final[ $after_column_slug ] = $this->columns[ $after_column_slug ]['label'];
					$this->done[] = $after_column_slug;
				}
			}
		}

		// Parse remaining columns
		// use-case: Columns registered without requesting to be 'before' or 'after' an existing column
		foreach ( $this->columns as $column_id => $column_data ) {
			// Skip if we already handled this custom column
			if ( in_array( $column_id, $this->done ) ) {
				continue;
			}

			$final[ $column_id ] = $column_data['label'];
		}

		return $final;
	}

	/**
	 * Add CSS to admin_head to handle columns custom size
	 */
	public function handle_column_size() {
		if ( empty( $this->sizes ) ) {
			return;
		}

		$output = [];

		foreach ( $this->sizes as $column_slug => $size ) {
			$output[] = sprintf(
				'.fixed .column-%s{ width: %s; }',
				$column_slug,
				$size
			);
		}

		printf( '<style>%s</style>', join( '', $output ) );
	}

	/**
	 * Register Column to be added later
	 *
	 * @param string $slug_raw
	 * @param array $options
	 * @param callable $callback
	 */
	public function add( $slug_raw, $options, $callback ) {
		$slug = sanitize_key( $slug_raw );

		$option_defaults = [
			'label' => $slug_raw,
			'before' => null, // string - column slug
			'after' => null, // string - column slug
			'size' => null, // string - css value for width ex: '10px' or '10%'
		];

		$args = wp_parse_args( $options, $option_defaults );

		$this->columns[ $slug ] = [
			'slug' => $slug,
			'label' => $args['label'],
			'callback' => $callback,
		];

		if ( ! empty( $args['before'] ) ) {
			$this->before = self::append( $this->before, $args['before'], $slug );
		}

		if ( ! empty( $args['after'] ) ) {
			$this->after = self::append( $this->after, $args['after'], $slug );
		}

		if ( ! empty( $args['size'] ) ) {
			$this->sizes[ $slug ] = $args['size'];
		}
	}

	/**
	 * Register Column to be removed later
	 *
	 * @param  string  $slug
	 */
	public function remove( $slug ) {
		if ( ! in_array( $slug, $this->remove ) ) {
			$this->remove[] = $slug;
		}
	}

	/**
	 * Append data for key in array. Creates array if not defined already
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $data
	 * @return array
	 */
	public static function append( $array, $key, $data ) {
		if ( ! isset( $array[ $key ] ) ) {
			$array[ $key ] = [];
		}

		$array[ $key ][] = $data;

		return $array;
	}

	/**
	 * Sanitize the size string to make sure it's a valid css value (including the unit)
	 *
	 * @param string $size
	 * @return string
	 */
	public static function sanitize_size( $size ) {
		if ( ! is_string( $size ) && ! is_numeric( $size ) ) {
			return $size;
		}

		$pattern_count = preg_match( '/(p[cx]|r?em|%)$/', $size );

		// return early if an error occurred w/ the regex or if we did find what we were looking for.
		if ( $pattern_count === false ||
		     $pattern_count > 0 ) {
			return $size;
		}

		return $size . 'px'; // default to pixel
	}
}
