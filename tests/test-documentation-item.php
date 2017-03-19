<?php
/**
 * Client Documentation - test-documentation-item.php
 * User: mathieuhays
 * Date: 18/03/2017
 * Time: 10:46
 */

use SimpleDocumentation\DocumentationItems\DocumentationItem;
use SimpleDocumentation\DocumentationItems\DocumentationItems;

class DocumentationItemTests extends WP_UnitTestCase {
	/**
	 * Create DocumentationItem \WP_Post
	 *
	 * @param array $args
	 *
	 * @return \WP_Post
	 */
	public function util_create_and_get( $args = [] ) {
		$args['post_type'] = DocumentationItems::POST_TYPE;
		return $this->factory->post->create_and_get( $args );
	}

	public function test_get_view_link() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		// There might be better way to test this later on...
		$this->assertTrue( filter_var( $item->get_view_link(), FILTER_VALIDATE_URL ) );
	}

	public function test_get_edit_link() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		// should use wordpress admin
		$this->assertNotFalse( strpos( $item->get_edit_link(), 'post.php' ) );
	}

	public function test_get_type() {
		// @TODO implement get_type() detect that DocumentationTypes()->get() is called
	}

	public function test_has_type() {
		// @TODO implement has_type() test
	}

	public function test_is_highlighted() {
		// @TODO implement is_highlighted() test
	}
}