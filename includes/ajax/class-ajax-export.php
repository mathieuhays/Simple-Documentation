<?php
/**
 * Ajax - simpledocumentation_export
 */

namespace Simple_Documentation\Ajax;


use Simple_Documentation\Export;

class Ajax_Export extends Base_Ajax {


	/**
	 * @return string
	 */
	public function get_action_name() {
		return 'export';
	}


	public function render() {
		$args = [
			'options' => false,
			'data' => true,
		];

		if ( isset( $_REQUEST['options'] ) &&
			 $_REQUEST['options'] === 'include' ) {
			$args['options'] = true;
		}

		$export = new Export( $args );

		$this->response($export->to_array());
	}
}
