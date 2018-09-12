<?php
/**
 * Functions
 */

namespace Simple_Documentation;


/**
 * @param string $role_slug
 *
 * @return string
 */
function get_role_label( $role_slug ) {
	global $wp_roles;

	if ( isset( $wp_roles->roles[ $role_slug ] ) ) {
		return $wp_roles->roles[ $role_slug ]['name'];
	}

	return $role_slug;
}
