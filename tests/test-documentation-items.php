<?php
/**
 * Client Documentation - test-documentation-items.php
 * User: mathieuhays
 * Date: 17/03/2017
 * Time: 23:05
 */

use \SimpleDocumentation\DocumentationItems\DocumentationItems;

class DocumentationItemsTests extends WP_UnitTestCase {
	public function test_post_type_registration() {
		// Clean environment
		$this->reset_post_types();

		// Ensure we have a clean environment
		$this->assertEquals(
			false,
			post_type_exists( DocumentationItems::POST_TYPE )
		);

		DocumentationItems::get_instance()->register_post_type();

		// Check if the post type has been properly registered
		$this->assertEquals(
			true,
			post_type_exists( DocumentationItems::POST_TYPE )
		);
	}

	public function test_convert_post_to_documentation_item() {
		$post = $this->factory->post->create_and_get([
			'post_type' => DocumentationItems::POST_TYPE,
		]);

		$result_post = DocumentationItems::get_instance()->convert_post_to_documentation_item( $post );
		$result_id = DocumentationItems::get_instance()->convert_post_to_documentation_item( $post->ID );

		$this->assertInstanceOf( \SimpleDocumentation\DocumentationItems\DocumentationItem::class, $result_post );
		$this->assertInstanceOf( \SimpleDocumentation\DocumentationItems\DocumentationItem::class, $result_id );
	}
}
