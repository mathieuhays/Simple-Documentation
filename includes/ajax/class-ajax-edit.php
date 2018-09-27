<?php
/**
 * Simple Documentation
 * Ajax Handler for simpleDocumentation_ajax
 */

namespace Simple_Documentation\Ajax;


use Simple_Documentation\Models\Documentation;
use Simple_Documentation\Models\Documentation_Type;

class Ajax_Edit extends Base_Ajax {

	/**
	 * @return string
	 */
	public function get_action_name() {
		return 'edit';
	}

	public function render() {
		$source = $_REQUEST;

		if ( empty( $source['item_id'] ) ) {
			$this->send_error( 'invalid-request' );
		}

		$item_id = $source['item_id'];
		$is_new = $item_id === 'new';

		if ( empty( $source['type'] ) ) {
			wp_send_json_error([
				'field' => null,
				'message' => __( 'You must select a type to save the item', 'client-documentation' ),
			]);
		}

		if ( ! Documentation_Type::exists( $source['type'] ) ) {
			wp_send_json_error([
				'field' => null,
				'message' => __( 'Invalid type', 'client-documentation' ),
			]);
		}

		$type = $source['type'];

		if ( empty( $source['title'] ) ) {
			wp_send_json_error([
				'field' => 'title',
				'message' => __( 'required', 'client-documentation' )
			]);
		}

		$content = ! empty( $source['content'] ) ? $source['content'] : null;

		$data = [
			'type' => $type,
			'content' => $content,
		];

		$other_fields = [
			'attachment_id',
			'ordered',
			'restricted',
		];

		foreach ( $other_fields as $field_name ) {
			if ( isset( $source[ $field_name ] ) ) {
				$data[ $field_name ] = $source[ $field_name ];
			}
		}

		if ( $is_new ) {
			$result = Documentation::insert( $data );

			if ( ! Documentation::is_instance( $result ) ) {
				if ( ! is_wp_error( $result ) ) {
					$result = new \WP_Error( 'error', 'Something went wrong when creating entity' );
				}

				wp_send_json_error([
					'message' => $result->get_error_message(),
				]);
			}

			wp_send_json_success( $result->to_array() );
			return;
		}

		$documentation = Documentation::from_id( $item_id );

		if ( empty( $documentation ) ) {
			wp_send_json_error([
				'message' => 'Couldn\'t find entity to update',
			]);
		}

		if ( ! $documentation->update_fields( $data ) ) {
			wp_send_json_error([
				'message' => 'Something went wrong while updating entity.',
			]);
		}

		$documentation->save();

		wp_send_json_success( $documentation->to_array() );
	}
}
