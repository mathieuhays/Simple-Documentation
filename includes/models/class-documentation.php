<?php
/**
 * Documentation
 */

namespace Simple_Documentation\Models;


class Documentation extends Table_Model {

	/**
	 * @return string
	 */
	protected static function get_table() {
		global $wpdb;
		return $wpdb->prefix . 'simpledocumentation';
	}


	/**
	 * @return array
	 */
	protected static function get_fields() {
		return [
			'type' => '%s',
			'title' => '%s',
			'content' => '%s',
			'restricted' => '%s',
			'attachment_id' => '%d',
			'ordered' => '%d',
		];
	}


	/**
	 * @return string
	 */
	public function get_type() {
		return $this->row->type;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		$title = $this->row->title;
		$title = stripslashes( $title );

		return $title;
	}


	/**
	 * @return string
	 */
	public function get_content() {
		$content = $this->row->content;

		if ( $this->get_type() === Documentation_Type::FILE ) {
			if ( ! empty( $file_id = $this->get_attachment_id() ) ) {
				return htmlspecialchars( wp_get_attachment_url( $file_id ) );
			}

			$file = json_decode( htmlspecialchars_decode( $content ) );

			if ( ! empty( $file->url ) ) {
				return $file->url;
			}

			return '';
		}

		return htmlspecialchars_decode( stripslashes( $content ) );
	}

	/**
	 * Get explicitly allowed roles for this item
	 *
	 * @return array
	 */
	public function get_allowed_roles() {
		return json_decode( $this->row->restricted );
	}

	/**
	 * @return int
	 */
	public function get_attachment_id() {
		return (int) $this->row->attachment_id;
	}

	/**
	 * @return int
	 */
	public function get_order_index() {
		return (int) $this->row->ordered;
	}


	/**
	 * @param array $args
	 *
	 * @return bool|\WP_Error
	 */
	public static function validate_args( $args = [] ) {
		$validate = parent::validate_args( $args );

		if ( $validate === false || is_wp_error( $validate ) ) {
			return $validate;
		}

		if ( in_array( $args['type'], Documentation_Type::get_all(), true ) ) {
			return new \WP_Error( 'invalid-type', 'Invalid documentation type.' );
		}

		return $validate;
	}

}
