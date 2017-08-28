<?php
use SimpleDocumentation\DocumentationItem;

/**
 * Client Documentation - _test-documentation-item.php
 * User: mathieuhays
 * Date: 18/03/2017
 * Time: 10:46
 */

class DocumentationItemTests extends WP_UnitTestCase {
	/**
	 * Create DocumentationItem \WP_Post
	 *
	 * @param array $args
	 *
	 * @return \WP_Post
	 */
	public function util_create_and_get( $args = [] ) {
		$args['post_type'] = DocumentationItem::POST_TYPE;
		return $this->factory->post->create_and_get( $args );
	}

	public function test_get_view_link() {
		$post = $this->util_create_and_get();

		$item = DocumentationItem::from_post( $post );

		// There might be better way to test this later on...
		$this->assertNotFalse( filter_var( $item->get_view_link(), FILTER_VALIDATE_URL ) );
	}

	public function test_get_edit_link() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		// should use wordpress admin
		$this->assertNotFalse( strpos( $item->get_edit_link(), 'post.php' ) );
	}

	public function test_has_attachments() {
		// @TODO implement test_has_attachments
	}

	public function test_has_video() {
		// @TODO implement test_has_video
	}

	public function test_has_content() {
		// @TODO implement test_has_content
	}

	public function test_get_attachments() {
		// @TODO implement test_get_attachments
	}

	public function test_get_video() {
		// @TODO implement test_get_video
	}

	public function test_bootstrap() {
		// @TODO implement test_bootstrap
	}
}
