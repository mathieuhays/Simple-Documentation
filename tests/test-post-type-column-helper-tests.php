<?php
use SimpleDocumentation\Utilities\PostTypeColumnHelper;

/**
 * Client Documentation - test-post-type-column-helper-tests.php
 * User: mathieuhays
 * Date: 20/03/2017
 * Time: 18:53
 */
class PostTypeColumnHelperTests extends WP_UnitTestCase {
	public function test_bootstrap() {
		// @TODO check actions have been registered
	}

	public function test_handle_column_content() {
		// @TODO implement test_handle_column_content
	}

	public function test_handle_columns() {
		// @TODO implement test_handle_columns
	}

	public function test_handle_column_size() {
		// @TODO implement test_handle_column_size
	}

	public function test_add() {
		// @TODO implement test_add
	}

	public function test_remove() {
		// @TODO implement test_remove
	}

	public function test_append_empty_array() {
		$array = PostTypeColumnHelper::append( [], 'test', 'data' );

		$this->assertTrue( is_array( $array ) );
		$this->assertSame( 1, count( $array ) );
		$this->assertTrue( isset( $array['test'] ) );
		$this->assertSame( 1, count( $array['test'] ) );
	}

	public function test_append_existing_array() {
		$array = PostTypeColumnHelper::append( [
			'existing' => [ 'data' ],
		], 'test', 'data' );

		$this->assertTrue( is_array( $array ) );
		$this->assertSame( 2, count( $array ) );
		$this->assertTrue( isset( $array['test'] ) );
		$this->assertTrue( isset( $array['existing'] ) );
		$this->assertSame( 1, count( $array['test'] ) );
		$this->assertSame( 1, count( $array['existing'] ) );
	}

	public function test_append_existing_array_existing_key() {
		$array = PostTypeColumnHelper::append( [
			'existing' => [ 'data' ],
		], 'existing', 'data' );

		$this->assertTrue( is_array( $array ) );
		$this->assertSame( 1, count( $array ) );
		$this->assertTrue( isset( $array['existing'] ) );
		$this->assertSame( 2, count( $array['existing'] ) );
	}

	public function test_sanitize_size() {
		// Default to px
		$this->assertSame( '10px', PostTypeColumnHelper::sanitize_size( 10 ) );

		// Doesn't override valid value
		$this->assertSame( '10px', PostTypeColumnHelper::sanitize_size( '10px' ) );

		// Ignore weird values
		$this->assertSame( false, PostTypeColumnHelper::sanitize_size( false ) );
		$this->assertSame( null, PostTypeColumnHelper::sanitize_size( null ) );
	}
}
