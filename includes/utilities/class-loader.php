<?php
/**
 *  Simple Documentation -- Loader Utils
 */

namespace SimpleDocumentation\Utilities;

class Loader {
	/**
	 *  Load Template
	 *
	 *  @param  string  $template_name
	 *  @return bool
	 */
	public static function template( $template_name ) {
		$file_path = sprintf(
			'%s/%s.php',
			SIMPLEDOC_TEMPLATES_DIR,
			$template_name
		);

		if ( file_exists( $file_path ) ) {
			require $file_path;
			return true;
		}

		return false;
	}


	/**
	 *  Load Component
	 *
	 *  @param  string  $component_name
	 *  @return bool
	 */
	public static function component( $component_name ) {
		$file_path = sprintf(
			'%s/%s.php',
			SIMPLEDOC_COMPONENTS_DIR,
			$component_name
		);

		if ( file_exists( $file_path ) ) {
			require $file_path;
			return true;
		}

		return false;
	}
}
