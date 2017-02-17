<?php
/**
 *  Simple Documentation - Post Type Column Manager
 *  Helps adding/removing columns on the post type posts listing.
 */

namespace SimpleDocumentation\Utilities;

class PostTypeColumnHelper {
	/**
	 *  @var string Post Type
	 */
	private $post_type;
	private $columns = [];
	private $before = [];
	private $after = [];
	private $remove = [];
	private $done = [];
	private $sizes = [];


	/**
	 *  @param  string  $post_type
	 */
	public function __construct( $post_type = null ) {
		$this->post_type = empty( $post_type ) ? 'post' : $post_type;

		$this->bootstrap();
	}


	public function bootstrap() {
		/**
		 *  Handles which content is displayed where
		 */
		add_action(
			sprintf( 'manage_%s_posts_custom_columns', $this->post_type ),
			[ $this, 'handle_column_content' ],
			12,
			2
		);

		/**
		 *  Add/Remove Columns
		 */
		add_filter(
			sprintf( 'manage_%s_posts_columns', $this->post_type ),
			[ $this, 'handle_columns' ],
			12
		);

		/**
		 *  Add Custom CSS in case we use a custom size for the column
		 */
		add_action( 'admin_head', [ $this, 'handle_column_size' ] );
	}


	/**
	 *  Trigger Content Callback if it has been registered with this class.
	 *  The callback is meant to echo content.
	 *
	 *  @param  string  $column_slug
	 *  @param  int     $post_id
	 */
	public function handle_column_content( $column_slug, $post_id ) {
		// Skip if haven't registered custom columns or this column is one hasn't
		// been registered as part of this class.
		if ( empty( $this->columns ) || ! isset( $this->columns[ $column_slug ] ) ) {
			return;
		}

		call_user_func( $this->columns[ $column_slug ]['callback'], $post_id );
	}


	/**
	 *  Handle Columns
	 *
	 *  @param  array   $default_columns
	 *  @return array
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
		// use-case: Columns registered without requesting to be 'before' or 'after'
		// an existing column
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
	 *  Add CSS to admin_head to handle columns custom size
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
	 *  Register Column to be added later
	 *
	 *  @param
	 */
	public function add( $slug_raw, $options, $callback ) {
		$slug = sanitize_key( $slug_raw );

		$option_defaults = [
			'label' => $slug_raw,
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
	 *  Register Column to be removed later
	 *
	 *  @param  string  $slug
	 */
	public function remove( $slug ) {
		if ( ! in_array( $slug, $this->remove ) ) {
			$this->remove[] = $slug;
		}
	}


	/**
	 *  Append data for key in array. Creates array if not defined already
	 *
	 *  @param  array   $array
	 *  @param  string  $key
	 *  @param  mixed   $data
	 */
	public static function append( $array, $key, $data ) {
		if ( ! isset( $array[ $key ] ) ) {
			$array[ $key ] = [];
		}

		$array[ $key ][] = $data;

		return $array;
	}
}
