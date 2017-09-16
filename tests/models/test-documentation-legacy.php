<?php

use SimpleDocumentation\Models\Documentation_Legacy;

class Documentation_Legacy_Tests extends WP_UnitTestCase {
	/**
	 * @param array $custom_properties
	 *
	 * @return stdClass
	 */
	public function util_generate_row( $custom_properties = [] ) {
		$properties = wp_parse_args( $custom_properties, [
			'ID' => 1,
			'type' => 'note',
			'title' => 'Dapibus Pellentesque Commodo Consectetur',
			'content' => 'Ipsum Sem Vulputate',
			'etoile_b' => 0, // not used but added for accurate representation of our table row
			'etoile_t' => null, // same as above
			'restricted' => false,
			'attachment_id' => 0,
			'ordered' => '',
		] );

		$object = new stdClass();

		foreach ( $properties as $key => $value ) {
			$object->{$key} = $value;
		}

		return $object;
	}

	public function test_from_db_row() {
		$invalid_row = new stdClass();
		$invalid_row->foo = 'bar';
		$valid_row = $this->util_generate_row();

		$this->assertFalse( Documentation_Legacy::from_db_row( $invalid_row ) );
		$this->assertFalse( Documentation_Legacy::from_db_row( false ) );
		$this->assertFalse( Documentation_Legacy::from_db_row( true ) );
		$this->assertFalse( Documentation_Legacy::from_db_row( 'foo' ) );
		$this->assertInstanceOf( Documentation_Legacy::class, Documentation_Legacy::from_db_row( $valid_row ) );
	}

	public function test_get_content_from_encoded_string() {
		$row = $this->util_generate_row([
			'content' => "&lt;p&gt;it\'s a test&lt;/p&gt;",
		]);

		$documentation_legacy_1 = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation_legacy_1->get_content(), "<p>it's a test</p>" );
	}

	public function test_get_content_from_decoded_string() {
		$row = $this->util_generate_row([
			'content' => "<p>it's a test</p>",
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_content(), "<p>it's a test</p>" );
	}

	public function test_get_allowed_user_roles() {
		$row = $this->util_generate_row([
			'restricted' => '["administrator","subscriber"]',
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertEqualSets( $documentation->get_allowed_user_roles(), [
			'administrator',
			'subscriber',
		] );
	}

	public function test_get_attachment_id() {
		$row = $this->util_generate_row([
			'attachment_id' => 120,
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_attachment_id(), 120 );
	}

	public function test_get_order_index() {
		$row = $this->util_generate_row([
			'ordered' => 4,
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_order_index(), 4 );
	}
}
