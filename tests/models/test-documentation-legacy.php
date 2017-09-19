<?php

use SimpleDocumentation\Models\Documentation_Legacy;

class Documentation_Legacy_Tests extends WP_UnitTestCase {
	public static function setUpBeforeClass() {
		global $wpdb;

		/**
		 * Add DB manually for the test since the plugin doesn't add it anymore.
		 * This table was added in previous version of the plugin.
		 * We need to test it so we know we can reliably interface with this source when upgrading to
		 * the new structure.
		 */
		$wpdb->query( "
	    	CREATE TABLE wptests_simpledocumentation (
				ID bigint(20) NOT NULL auto_increment,
				type varchar(200) NOT NULL default 'note',
				title varchar(255) NOT NULL default 'New document',
				content text NOT NULL,
				etoile_b tinyint(1) NOT NULL default 0,
				etoile_t datetime,
				restricted varchar(500),
				attachment_id bigint(20),
				ordered bigint(20),
				UNIQUE KEY ID (ID)
			);"
		);

		return parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass() {
		global $wpdb;

		/**
		 * Add DB manually for the test since the plugin doesn't add it anymore.
		 * This table was added in previous version of the plugin.
		 * We need to test it so we know we can reliably interface with this source when upgrading to
		 * the new structure.
		 */
		$wpdb->query( 'DROP TABLE wptests_simpledocumentation' );

		return parent::tearDownAfterClass();
	}

	/**
	 * @param array $custom_properties
	 *
	 * @return array
	 */
	public function util_parse_properties( $custom_properties = [] ) {
		return wp_parse_args( $custom_properties, [
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
	}

	/**
	 * @param array $custom_properties
	 *
	 * @return stdClass
	 */
	public function util_generate_row( $custom_properties = [] ) {
		$properties = $this->util_parse_properties( $custom_properties );
		$object = new stdClass();

		foreach ( $properties as $key => $value ) {
			$object->{$key} = $value;
		}

		return $object;
	}

	/**
	 * @param array $custom_properties
	 *
	 * @return bool|int
	 */
	public function util_add_db_row( $custom_properties = [] ) {
		global $wpdb;

		$properties = $this->util_parse_properties( $custom_properties );
		unset( $properties['ID'] );

		$row_affected = $wpdb->insert( 'wptests_simpledocumentation', $properties, [ '%s', '%s', '%s', '%s', '%d', '%d' ] );

		if ( $row_affected === false ) {
			return false;
		}

		return $wpdb->insert_id;
	}

	public function test_get_id() {
		$row = $this->util_generate_row([
			'ID' => 12,
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_id(), 12 );
	}

	public function test_get_type() {
		$row = $this->util_generate_row([
			'type' => 'foo',
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_type(), 'foo' );
	}

	public function test_get_title() {
		$row = $this->util_generate_row([
			'title' => 'Lorem Ipsum',
		]);

		$documentation = Documentation_Legacy::from_db_row( $row );

		$this->assertSame( $documentation->get_title(), 'Lorem Ipsum' );
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

	public function test_delete() {
		global $wpdb;
		$row_id = $this->util_add_db_row();

		if ( $row_id === false ) {
			$this->assertTrue( false );
			return;
		}

		$before_count = (int) $wpdb->get_var( $wpdb->prepare(
			'SELECT COUNT(*) FROM wptests_simpledocumentation WHERE `ID` = %d',
			$row_id
		));

		// make sure we did add a row
		$this->assertTrue( $before_count === 1 );

		$documentation = Documentation_Legacy::get( $row_id );

		// make sure we managed to retrieve our documentation object based on the row id
		$this->assertTrue( $documentation !== false );

		// method we actually want to test
		$documentation->delete();

		$after_count = (int) $wpdb->get_var( $wpdb->prepare(
			'SELECT COUNT(*) FROM wptests_simpledocumentation WHERE `ID` = %d',
			$row_id
		));

		// make sure the row in question is not in the db anymore
		$this->assertTrue( $after_count === 0 );
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

	public function test_get() {
		global $wpdb;
		$row_id = $this->util_add_db_row();

		if ( $row_id === false ) {
			$this->assertTrue( false );
			return;
		}

		$count = (int) $wpdb->get_var( $wpdb->prepare(
			'SELECT COUNT(*) FROM wptests_simpledocumentation WHERE `ID` = %d',
			$row_id
		));

		$this->assertTrue( $count === 1 );

		$documentation = Documentation_Legacy::get( $row_id );

		$this->assertTrue( $documentation !== false );
		$this->assertSame( $documentation->get_id(), $row_id );
	}

	public function test_get_all() {
		Documentation_Legacy::insert([
			'title' => 'Test #1',
		]);
		Documentation_Legacy::insert([
			'title' => 'Test #2',
		]);

		$all = Documentation_Legacy::get_all();

		$this->assertSame( count( $all ), 2 );
		$this->assertInstanceOf( Documentation_Legacy::class, $all[0] );
	}

	public function test_insert() {
		$doc_1_id = Documentation_Legacy::insert([ 'foo' => 'bar' ]);
		$doc_2_id = Documentation_Legacy::insert([ 'title' => 'Lorem Ipsum' ]);

		// should return false if omitting the title argument
		$this->assertFalse( $doc_1_id );
		$this->assertTrue( is_int( $doc_2_id ) );
		$this->assertTrue( $doc_2_id > 0 );
	}
}
