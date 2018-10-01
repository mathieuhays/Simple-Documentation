<?php
/**
 * Simple Documentation Base Controller
 */

namespace Simple_Documentation\Rest\V1;


class Simple_Documentation_Controller extends \WP_REST_Controller {

	/**
	 * @return string
	 */
	protected function get_namespace() {
		return 'simple-documentation/v1';
	}
}
