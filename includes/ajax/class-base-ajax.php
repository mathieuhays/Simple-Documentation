<?php
/**
 * Simple Documentation
 * Base Ajax
 */

namespace Simple_Documentation\Ajax;

abstract class Base_Ajax {

	/**
	 * @return string
	 */
	abstract public function get_action_name();

	abstract public function render();


	/**
	 * @return bool
	 */
	public function is_public() {
		return false;
	}


	public function _render() {
		$this->render();
		exit;
	}


	/**
	 * @TODO Handler should use wp_send_json_success directly in the future
	 *
	 * @param array $data
	 */
	public function response( $data ) {
		wp_send_json( $data );
	}


	/**
	 * @param array $data
	 */
	public function send_success( $data = [] ) {
		$data['status'] = 'ok';

		$this->response( $data );
	}


	/**
	 * @param array $data
	 */
	public function send_error( $type, $data = [] ) {
		$data['status'] = 'error';
		$data['type'] = $type;

		$this->response( $data );
	}

}
