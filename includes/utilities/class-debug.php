<?php
/**
 * Simple Documentation
 * Debug
 */

namespace SimpleDocumentation\Utilities;

class Debug {
	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * Debug constructor.
	 */
	public function __construct() {
		/**
		 * Render debug data to the console at the end of the page
		 */
		add_action( 'wp_footer', [ $this, 'render' ], 99 );
		add_action( 'admin_footer', [ $this, 'render' ], 99 );

		/**
		 * Process what's being rendered to the console
		 */
		add_filter( 'mh_utils_debug_js_arguments', [ $this, 'convert_arguments_for_js' ] );
	}

	/**
	 * @return bool
	 */
	public function is_enabled() {
		return (
			defined( 'SIMPLE_DOCUMENTATION_DEBUG' ) &&
			SIMPLE_DOCUMENTATION_DEBUG
		);
	}

	/**
	 * Record log
	 *
	 * @param string $caller
	 * @param array $arguments
	 *
	 * @return bool
	 */
	public function record( $caller, $arguments ) {
		if ( ! $this->is_enabled() ) {
			return false;
		}

		$this->data[] = [
			'caller' => $caller,
			'args' => $arguments,
		];

		return true;
	}

	/**
	 * @return bool
	 */
	public function render() {
		if ( ! $this->is_enabled() ||
			 empty( $this->data ) ) {
			return false;
		}

		$output = '';

		foreach ( $this->data as $index => $data ) {
			$arguments = apply_filters( 'mh_utils_debug_js_arguments', $data['args'] );

			if ( ! empty( $data['caller'] ) ) {
				$output .= sprintf(
					'/*/CALLER#%d/*/
					console.log(%s, %s)',
					$index,
					json_encode( '%c' . $data['caller'] ),
					json_encode( self::get_caller_style() )
				);
			}

			$output .= sprintf(
				'/*/DEBUG#%d/*/
				console.log(%s)',
				$index,
				join( ',', $arguments )
			);
		}

		printf(
			'<script>%s</script>',
			$output
		);

		return true;
	}

	/**
	 * Log
	 * Accept any number of arguments.
	 *
	 * @return bool
	 */
	public static function log() {
		return self::get_instance()->record(
			self::get_caller( debug_backtrace() ),
			func_get_args()
		);
	}

	/**
	 * Parse backtrace and return a human friendly name for the caller.
	 *
	 * @param array $backtrace
	 *
	 * @return string
	 */
	public static function get_caller( $backtrace ) {
		$out = [];
		$debug = $backtrace[1];

		if ( strpos( $debug['function'], 'call_user_func' ) !== false ) {
			$debug = $backtrace[2];
		}

		if ( ! empty( $debug['class'] ) ) {
			$out[] = $debug['class'];
			$out[] = $debug['type'];
		}

		$out[] = $debug['function'];

		return join( '', $out );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public static function convert_arguments_for_js( $args ) {
		return array_map( function( $arg ) {
			/**
			 * Since object are not rendered very well with json_encode and console.log
			 * we render a representation of the object.
			 * Note, we're only able to get public properties and methods.
			 */
			if ( is_object( $arg ) ) {
				$arg = [
					'class' => get_class( $arg ),
					'properties' => get_object_vars( $arg ),
					'methods' => get_class_methods( $arg ),
				];
			}

			return json_encode( $arg );
		}, $args );
	}

	/**
	 * @return string
	 */
	public static function get_caller_style() {
		return inline_style_attributes([
			'font-weight' => 'bold',
			'font-size' => '1.05em',
			'line-height' => '2',
			'vertical-align' => 'bottom',
		]);
	}

	/**
	 * @return Debug
	 */
	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}
}
