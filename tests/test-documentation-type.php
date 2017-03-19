\<?php
/**
 * Client Documentation - test-documentation-type.php
 * User: mathieuhays
 * Date: 19/03/2017
 * Time: 12:48
 */

use \SimpleDocumentation\DocumentationItems\DocumentationType;

class DocumentationTypeTests extends WP_UnitTestCase {
	public function test_get_slug() {
		$test_slug = 'test-slug';
		$type = new DocumentationType( $test_slug, 'Label' );

		$this->assertSame( $test_slug, $type->get_slug() );
	}

	public function test_get_label() {
		$test_label = 'Test Label';
		$type = new DocumentationType( 'test-slug', $test_label );

		$this->assertSame( $test_label, $type->get_label() );
	}

	public function test_get_icon_classname() {
		$icon = 'random-classname';
		$type = new DocumentationType( 'slug', 'Label', $icon );

		$this->assertSame( $icon, $type->get_icon_classname() );
	}

	public function test_get_dashicon_classname() {
		$icon = 'dashicon-test-icon';
		$type = new DocumentationType( 'slug', 'test', $icon );

		$this->assertEquals(
			'dashicons ' . $icon,
			$type->get_icon_classname()
		);
	}

	public function test_get_icon_classname_empty() {
		$type = new DocumentationType( 'slug', 'label' );
		$this->assertSame( '', $type->get_icon_classname() );
	}

	public function test_get_icon_regular() {
		$type = new DocumentationType( 'slug', 'test', 'test-icon' );
		$this->assertNotFalse( strpos( $type->get_icon(), 'class="test-icon"' ) );
	}

	public function test_get_icon_dashicon() {
		$type = new DocumentationType( 'slug', 'test', 'dashicon-test-icon' );
		$this->assertNotFalse( strpos( $type->get_icon(), 'class="dashicons dashicon-test-icon"' ) );
	}

	public function test_get_icon_empty() {
		$type = new DocumentationType( 'slug', 'label' );
		$this->assertSame( '', $type->get_icon() );
	}
}