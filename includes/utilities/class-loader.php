<?php
/**
 *  Simple Documentation -- Loader Utils
 */

namespace SimpleDocumentation\Utilities;

class Loader {
	private static $current_component_args = [];
	private static $current_template_args = [];

	/**
	 * @param string $base
	 * @param string $filename
	 * @param array $arg_source
	 * @param array $args
	 *
	 * @return bool
	 */
	public static function load( $base, $filename, &$arg_source, $args = [] ) {
		$file_path = sprintf(
			'%s/%s.php',
			$base,
			$filename
		);

		$success = false;

		$arg_source[] = $args;

		if ( file_exists( $file_path ) ) {
			require $file_path;
			$success = true;
		}

		array_pop( $arg_source );

		return $success;
	}

	/**
	 *  Load Template
	 *
	 *  @param  string  $template_name
	 *  @return bool
	 */
	public static function template( $template_name, $args = [] ) {
		return self::load(
			SIMPLEDOC_TEMPLATES_DIR,
			$template_name,
			self::$current_template_args,
			$args
		);
	}


	/**
	 *  Load Component
	 *
	 *  @param  string  $component_name
	 *  @return bool
	 */
	public static function component( $component_name, $args = [] ) {
		return self::load(
			SIMPLEDOC_COMPONENTS_DIR,
			$component_name,
			self::$current_component_args,
			$args
		);
	}

	/**
	 * @param string $context
	 *
	 * @return mixed
	 */
	public static function get_current_args( $context = 'component' ) {
		$source = null;

		if ( $context === 'component' ) {
			$source = self::$current_component_args;
		} elseif ( $context === 'template' ) {
			$source = self::$current_template_args;
		}

		if ( empty( $source ) ) {
			return [];
		}

		return array_slice( $source, -1 )[0];
	}

	/**
	 * @return mixed
	 */
	public static function get_current_component_args() {
		return self::get_current_args( 'component' );
	}

	/**
	 * @return mixed
	 */
	public static function get_current_template_args() {
		return self::get_current_args( 'template' );
	}
}
