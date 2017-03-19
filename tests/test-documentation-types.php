<?php
/**
 * Client Documentation - test-documentation-types.php
 * User: mathieuhays
 * Date: 19/03/2017
 * Time: 12:36
 */

use \SimpleDocumentation\DocumentationItems\DocumentationTypes;
use \SimpleDocumentation\DocumentationItems\DocumentationType;

class DocumentationTypesTests extends WP_UnitTestCase {
	public function test_register_types() {
		// @TODO implement register_types() test
	}

	public function test_register() {
		$instance = DocumentationTypes::get_instance();

		$test_slug = 'test slug';

		$instance->register( $test_slug, 'Test Label', 'test-icon' );

		$this->assertTrue( isset( $instance->types[ $test_slug ] ) );
		$this->assertInstanceOf( DocumentationType::class, $instance->types[ $test_slug ] );
	}

	public function test_get() {
		$instance = DocumentationTypes::get_instance();
		$test_slug = 'test slug';
		$instance->register( $test_slug, 'Label', 'Icon' );

		$this->assertFalse( $instance->get( 'unknown-slug' ) );
		$this->assertInstanceOf( DocumentationType::class, $instance->get( $test_slug ) );
	}

	public function test_get_default() {
		$this->assertInstanceOf(
			DocumentationType::class,
			DocumentationTypes::get_instance()->get_default()
		);
	}

	public function test_get_all() {
		// @TODO implement get_all() test
	}
}