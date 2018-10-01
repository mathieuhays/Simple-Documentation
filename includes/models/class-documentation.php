<?php
/**
 * Documentation
 */

namespace Simple_Documentation\Models;


use Simple_Documentation\Simple_Documentation;

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
	 * @param string $value
	 *
	 * @return bool
	 */
	public function update_type( $value ) {
		if ( ! Documentation_Type::exists( $value ) ) {
			return false;
		}

		return $this->update_prop( 'type', $value );
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
	 * @param array $value
	 *
	 * @return bool
	 */
	public function update_restricted( $value ) {
		return $this->update_prop( 'restricted', json_encode( $value ) );
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
	 * @param int $value
	 *
	 * @return bool
	 */
	public function update_ordered( $value ) {
		return $this->update_prop( 'ordered', (int) $value );
	}


	/**
	 * @param \WP_User $user
	 *
	 * @return bool
	 */
	public function user_has_access( \WP_User $user ) {
		$allowed_roles = $this->get_allowed_roles();

		if ( empty( $allowed_roles ) ) {
			$allowed_roles = Simple_Documentation::get_instance()->settings['user_role'];
		}

		foreach ( $user->roles as $user_role ) {
			if ( in_array( $user_role, $allowed_roles ) ) {
				return true;
			}
		}

		return false;
	}


	public function to_array() {
		$attachment_filename = null;
		$attachment_url = null;
		$attachment_id = $this->get_attachment_id();

		if ( ! empty( $attachment_id ) ) {
			$attachment_url = wp_get_attachment_url( $attachment_id );
			$attachment_filename = basename( $attachment_url );
		}

		return [
			'ID' => $this->get_id(),
			'title' => $this->get_title(),
			'content' => $this->get_content(),
			'type' => $this->get_type(),
			'attachment_id' => $attachment_id,
			'attachment_filename' => $attachment_filename,
			'attachment_url' => $attachment_url,
			// items below should be renamed
			'ordered' => $this->get_order_index(),
			'restricted' => $this->get_allowed_roles(),
		];
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