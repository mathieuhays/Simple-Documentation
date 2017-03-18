<?php
/**
 *  Simple Documentation - Documentation Item
 */

namespace SimpleDocumentation\DocumentationItems;

use SimpleDocumentation\PostType_Item;
use SimpleDocumentation\DocumentationItems\DocumentationItems;
use SimpleDocumentation\DocumentationItems\DocumentationTypes;

class DocumentationItem extends PostType_Item {
	/**
	 *  Get View Link
	 *
	 *  @return string
	 */
	public function get_view_link() {
		// @TODO implement plugin item view permalink
	}


	/**
	 *  Get Edit Link
	 *
	 *  @return string
	 */
	public function get_edit_link() {
		return add_query_arg(
			[
				'post' => $this->get_id(),
				'action' => 'edit',
			],
			admin_url( 'post.php' )
		);
	}


	/**
	 *  Get Type
	 *
	 *  @return DocumentationType
	 */
	public function get_type() {
		$type = get_post_meta(
			$this->get_id(),
			DocumentationItems::META_ITEM_TYPE,
			true
		);

		if (  empty( $type ) ) {
			return DocumentationTypes::get_instance()->get_default();
		}

		return DocumentationTypes::get_instance()->get( $type );
	}


	/**
	 *  Item Has Type
	 *
	 *  @param  DocumentationType|string    $type
	 *  @return bool
	 */
	public function has_type( $type ) {
		$current_type = $this->get_type();

		if ( is_string( $type ) ) {
			return $current_type->get_slug() == $type;
		} elseif ( is_a( $type, '\SimpleDocumentation\DocumentationItems\DocumentationType' ) ) {
			return $current_type->get_slug() == $type->get_slug();
		}

		return false;
	}


	/**
	 *  Whether this item has been highlighted by the editor or not
	 *
	 *  @return bool
	 */
	public function is_highlighted() {
		// @TODO

		return get_post_meta(
			$this->get_id(),
			DocumentationItems::META_HIGHLIGHT,
			true
		);
	}
}
