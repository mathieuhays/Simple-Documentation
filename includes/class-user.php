<?php
/**
 * Simple Documentation
 * User Class
 */

namespace SimpleDocumentation;

class User {
	/**
	 * @var \WP_User
	 */
	protected $user;

	const CAP_DOC_VIEW = '';

	const CAP_DOC_EDIT = '';

	/**
	 * User constructor.
	 *
	 * @param \WP_User $wp_user
	 */
	public function __construct( $wp_user ) {
		$this->user = $wp_user;
	}

	/**
	 * @return \WP_User
	 */
	public function get_object() {
		return $this->user;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->get_object()->ID;
	}

	/**
	 * @return string
	 */
	public function get_username() {
		return $this->get_object()->user_nicename;
	}

	/**
	 * Get the email address of the user
	 *
	 * @return string
	 */
	public function get_email() {
		return $this->get_object()->user_email;
	}

	/**
	 * Check whether this user has a given capability or not.
	 *
	 * @param string $capability
	 *
	 * @return bool
	 */
	public function can( $capability ) {
		return user_can( $this->get_object(), $capability );
	}


	/**
	 * Whether the user can view a given documentation item or not.
	 *
	 * @param Documentation_Item $documentation_item
	 *
	 * @return bool
	 */
	public function can_view_doc( $documentation_item ) {
		/**
		 * @TODO implement view access test
		 * should test for capability then check if there are not extra restrictions attached to the specified doc.
		 */
		return true;
	}

	/**
	 * Whether the user can edit a given documentation item or not.
	 *
	 * @param Documentation_Item $documentation_item
	 *
	 * @return bool
	 */
	public function can_edit_doc( $documentation_item ) {
		return $this->can( static::CAP_DOC_EDIT );
	}

	/**
	 * Retrieve user object for given user id or object
	 *
	 * @param int|\WP_User $user_mixed
	 * @return \WP_Error|User
	 */
	public static function from_user( $user_mixed ) {
		if ( is_a( $user_mixed, '\WP_User' ) ) {
			return new static( $user_mixed );
		}

		if ( is_numeric( $user_mixed ) ) {
			$wp_user = get_user_by( 'id', (int) $user_mixed );

			if ( is_wp_error( $wp_user ) ) {
				return $wp_user;
			} else {
				return new static( $wp_user );
			}
		}

		return new \WP_Error( 1, 'Couldn\'t generate User object based on the provided arguments' );
	}

	/**
	 * Get Current User Object
	 *
	 * @return bool|User|\WP_Error
	 */
	public static function get_current() {
		$user_id = get_current_user_id();

		if ( $user_id === 0 ) {
			return false;
		}

		return static::from_user( $user_id );
	}

	/**
	 * Whether the provided object is an instance of this class or not.
	 *
	 * @param mixed $mixed
	 *
	 * @return bool
	 */
	public static function is_instance( $mixed ) {
		return is_a( $mixed, static::class );
	}

	/**
	 * Whether the two object provided represents the same User or not.
	 *
	 * @param mixed $mixed_1
	 * @param mixed $mixed_2
	 *
	 * @return bool
	 */
	public static function equals( $mixed_1, $mixed_2 ) {
		if ( ! static::is_instance( $mixed_1 ) || ! static::is_instance( $mixed_2 ) ) {
			return false;
		}

		/**
		 * @var User $mixed_1
		 * @var User $mixed_2
		 */
		return $mixed_1->get_id() === $mixed_2->get_id();
	}
}
