<?php
/**
 * Client Documentation - test-post-type-item.php
 * User: mathieuhays
 * Date: 18/03/2017
 * Time: 10:46
 */

use \SimpleDocumentation\DocumentationItems\DocumentationItem;
use \SimpleDocumentation\DocumentationItems\DocumentationItems;

class PostTypeItemTests extends WP_UnitTestCase {
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

	public function test_construct_with_post() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		$this->assertSame( $post->ID, $item->ID );
	}

	public function test_construct_with_id() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post->ID );

		$this->assertSame( $post->ID, $item->ID );
	}

	public function test_construct_no_args_within_loop() {
		global $post;

		$new_post = $this->util_create_and_get();

		$post = $new_post;
		setup_postdata( $post );

		$item = new DocumentationItem;

		$this->assertSame( $new_post->ID, $item->ID );

		wp_reset_postdata(); // clean env
	}

	public function test_construct_no_args_outside_loop() {
		// @TODO implement check for exception when instantiating outside the loop
	}

	public function test_get_id() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		$this->assertSame( $post->ID, $item->get_id() );
	}

	public function test_get_title() {
		$test_title = 'Test Title';

		$post = $this->util_create_and_get([
			'post_title' => $test_title
		]);

		$item = new DocumentationItem( $post );

		$this->assertSame( $test_title, $item->get_title() );
	}

	public function test_get_permalink() {
		$post = $this->util_create_and_get();

		$item = new DocumentationItem( $post );

		$this->assertSame(
			esc_url_raw( $item->get_permalink(), [ 'http', 'https' ] ),
			$item->get_permalink()
		);
	}

	public function test_get_content() {
		// @TODO implement test for get_content()
		$test_content = '<p>Test Content</p>';

		$post = $this->util_create_and_get([
			'post_content' => $test_content
		]);

		$item = new DocumentationItem( $post );

		// use trim because the_content filter will add the extra return
		$this->assertSame( $test_content, trim( $item->get_content() ) );
	}

	public function test_is_current() {
		global $post;

		$test_post = $this->util_create_and_get();
		$post = $test_post;
		setup_postdata( $post );

		$item = new DocumentationItem;

		$this->assertTrue( $item->is_current() );

		wp_reset_postdata();
	}

	public function test_is_not_current() {
		global $post;

		$random_page = $this->factory->post->create_and_get([
			'post_type' => 'page'
		]);

		$post = $random_page;
		setup_postdata( $post );

		$test_post = $this->util_create_and_get();

		$item = new DocumentationItem( $test_post );

		$this->assertFalse( $item->is_current() );

		wp_reset_postdata();
	}

}