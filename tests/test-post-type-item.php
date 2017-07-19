<?php
/**
 * Client Documentation - test-post-type-item.php
 * User: mathieuhays
 * Date: 18/03/2017
 * Time: 10:46
 */

use \SimpleDocumentation\PostTypeItem;

class PostTypeItemTests extends WP_UnitTestCase {

	public function test_get_id() {
		$post = $this->factory->post->create_and_get();

		$item = PostTypeItem::from_post( $post );

		$this->assertSame( $post->ID, $item->get_id() );
	}

	public function test_get_title() {
		$test_title = 'Test Title';

		$post = $this->factory->post->create_and_get([
			'post_title' => $test_title,
		]);

		$item = PostTypeItem::from_post( $post );

		$this->assertSame( $test_title, $item->get_title() );
	}

	public function test_get_permalink() {
		$post = $this->factory->post->create_and_get();

		$item = PostTypeItem::from_post( $post );

		$this->assertSame(
			esc_url_raw( $item->get_permalink(), [ 'http', 'https' ] ),
			$item->get_permalink()
		);
	}

	public function test_get_content() {
		$test_content = '<p>Test Content</p>';

		$post = $this->factory->post->create_and_get([
			'post_content' => $test_content,
		]);

		$item = PostTypeItem::from_post( $post );

		// use trim because the_content filter will add an extra return
		$this->assertSame( $test_content, trim( $item->get_content() ) );
	}

	public function test_bootstrap() {
		/**
		 * @TODO implement test_bootstrap
		 * - check that register method is called
		 * - make sure bootstrap is run once
		 * - check that the post type object is returned
		 * - check it doesn't return a wp error
		 */
	}

	public function test_register() {
		/**
		 * @TODO implement test_register
		 */
	}

	public function test_from_post_called_with_post() {
		$post = $this->factory->post->create_and_get();

		$item = PostTypeItem::from_post( $post );

		$this->assertSame( $post->ID, $item->get_id() );
	}

	public function test_from_post_with_wrong_arg_type() {
		$this->assertFalse( PostTypeItem::from_post( 'foo' ) );
	}

	public function test_from_post_called_with_wrong_post_type() {
		$post = $this->factory->post->create_and_get([
			'post_type' => 'not-post',
		]);

		$item = PostTypeItem::from_post( $post );

		$this->assertWPError( $item );
	}

	public function test_is_instance() {
		$post = $this->factory->post->create_and_get();
		$item = PostTypeItem::from_post( $post );

		$this->assertTrue( PostTypeItem::is_instance( $item ) );
		$this->assertFalse( PostTypeItem::is_instance( $post ) );
		$this->assertFalse( PostTypeItem::is_instance( $post->ID ) );
		$this->assertFalse( PostTypeItem::is_instance( 'foo' ) );
		$this->assertFalse( PostTypeItem::is_instance( new stdClass() ) );
		$this->assertFalse( PostTypeItem::is_instance( [ 'ID' => $post->ID ] ) );
	}

	public function test_equals() {
		$post_1 = $this->factory->post->create_and_get();
		$post_2 = $this->factory->post->create_and_get();

		$item_1 = PostTypeItem::from_post( $post_1 );
		$item_2 = PostTypeItem::from_post( $post_2 );
		$item_3 = PostTypeItem::from_post( $post_1 );

		// Same Class Instance but not same item
		$this->assertFalse( PostTypeItem::equals( $item_1, $item_2 ) );

		// Different instances but same item
		$this->assertTrue( PostTypeItem::equals( $item_1, $item_3 ) );

		// Make sure we don't have a false position when testing with item WP_Post instance
		$this->assertFalse( PostTypeItem::equals( $item_1, $post_1 ) );

		// Test wrong argument types.
		$this->assertFalse( PostTypeItem::equals( $item_1, $post_1->ID ) );
		$this->assertFalse( PostTypeItem::equals( $item_1, 'foo' ) );
	}

	public function test_query_empty() {
		$query = PostTypeItem::query();

		$this->assertInstanceOf( WP_Query::class, $query );
		$this->assertTrue( isset( $query->posts ) );
		$this->assertEmpty( $query->posts );
	}

	public function test_query_not_empty() {
		$post = $this->factory->post->create_and_get();
		$query = PostTypeItem::query();

		$this->assertInstanceOf( WP_Query::class, $query );
		$this->assertTrue( isset( $query->posts ) );
		$this->assertNotEmpty( $query->posts );
	}

	public function test_get_page() {
		/**
		 * @TODO implement test_get_page
		 * - check it returns an array whether we have results or not
		 * - check that changing `posts_per_page`'s value is effective
		 * - check that changing `offset`'s value is effective
		 * - check that changing `paged`'s value is effective
		 */
	}

	public function test_get() {
		$post = $this->factory->post->create_and_get();

		$item = PostTypeItem::get( $post->ID );

		$this->assertSame( $post->ID, $item->get_id() );
	}
}
