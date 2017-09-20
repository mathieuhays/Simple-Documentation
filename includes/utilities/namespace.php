<?php
/**
 * Utilities
 */

namespace SimpleDocumentation\Utilities;

/**
 * Inline key/value pair as attributes (for html elements mainly).
 *
 * @param array $attributes
 *
 * @return string
 */
function inline_attributes( $attributes ) {
	$output = [];

	foreach ( $attributes as $key => $value ) {
		if ( $value === null ) {
			$output[] = $key;
			continue;
		}

		$output[] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
	}

	return join( ' ', $output );
}

/**
 * @param array $attributes
 *
 * @return string
 */
function inline_style_attributes( $attributes ) {
	$output = [];

	foreach ( $attributes as $key => $value ) {
		$output[] = sprintf( '%s:%s', $key, $value );
	}

	return join( ';', $output );
}
