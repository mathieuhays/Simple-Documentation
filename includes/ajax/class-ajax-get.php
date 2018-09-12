<?php
/**
 * Ajax - schooldocumentation_get
 */

namespace Simple_Documentation\Ajax;


use Simple_Documentation\Models\Documentation;

class Ajax_Get extends Base_Ajax {

	/**
	 * @return string
	 */
	public function get_action_name() {
		return 'get';
	}

	public function render() {
		if ( empty( $_REQUEST['id'] ) ) {
			return;
		}

		$item_id = (int) $_REQUEST['id'];

		$item = Documentation::from_id( $item_id );

		if ( empty( $item ) ) {
			$this->response([
				'status' => 'error',
				'type' => 'get-data',
				'id' => $item_id,
			]);
		}

		$this->response([
			'status' => 'ok',
			'type' => 'get-data',
			'data' => $item->to_array(),
		]);
	}
}
