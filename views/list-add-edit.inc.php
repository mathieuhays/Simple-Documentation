<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $wp_roles, $wpdb;

/**
 * @TODO handle pagination
 */
$items = \Simple_Documentation\Models\Documentation::get_page( 1 );

//    	$entries = $wpdb->get_results("SELECT * FROM $wpdb->simpleDocumentation ORDER BY ordered ASC");
?>
<div class="wrap">

	<h2><?php _e( 'Simple Documentation', 'client-documentation' ); ?> <a href="#add_new_content" class="add-new-h2" id="swtch_btn"><?php _e( 'Add New', 'client-documentation' ); ?></a></h2>

	<div class="<?php echo $this->slug; ?> clearfix">

		<div class="smpldcmttn_main">

			<div id="sd_list">
			<h3><?php _e( 'Documentation list', 'client-documentation' ); ?></h3>

			<ul class="list_doc" id="simpledoc_list">
				<?php

				$i = 0;

				foreach ( $items as $item ) {
					$id = $item->get_id();
					$icon = $this->icon($item->get_type());
					$title = $item->get_title();
					$content = $item->get_content();
					$url = null;
					$item_type = $item->get_type();
					$allowed_roles = $item->get_allowed_roles();

					if ( $item_type === \Simple_Documentation\Models\Documentation_Type::FILE ) {
						$attachment_id = $item->get_attachment_id();

						// File from import
						if ( empty( $attachment_id ) ) {
							$url = $content;
						} else {
							$url = wp_get_attachment_url( $attachment_id );
						}
					}

					if ( $item_type === \Simple_Documentation\Models\Documentation_Type::LINK ) {
						$url = $content;
					}

					if ( ! empty( $url ) ) {
						$content = sprintf(
							'<a href="%s">%s</a>',
							esc_url( $url ),
							esc_html( $url )
						);
					}

					$users_allowed = '';
					$role_labels = [];

					if ( empty( $allowed_roles ) ) {
						$allowed_roles = $this->settings['user_role'];
						$users_allowed = __( '(default)', 'client-documentation' ) . ' ';
					}

					$role_labels = array_map( '\\Simple_Documentation\\get_role_label', $allowed_roles );
					$translated_labels = array_map( '__', $role_labels );
					$users_allowed .= join( ', ', $translated_labels );

					echo "
					<li id='simpledoc_{$id}' class='smpldoc_li'>
						<div class='el_front' data-id='{$id}' data-order='{$i}'>
							<span class='el_front_bf'>
								<a href='#' class='smpldoc_sort'><i class='fa fa-bars'></i></a>
								<i class='fa fa-{$icon}'></i>
							</span>
							<span class='el_title'>
								{$title}
							</span>
							<span class='el_front_af'>
								<i class='fa fa-user smpldoc_usersallowed' title='$users_allowed'></i>
								<a href='#edit' class='smpldoc_edit_item'><i class='fa fa-pencil'></i></a>
								<a href='#delete' class='smpldoc_delete_item'><i class='fa fa-times'></i></a>
							</span>
						</div>
						<div class='el_expand'>
							{$content}
						</div>
					</li>";

					$i++;
				}

				?>
			</ul>
			</div>
			<div id="sd_add" style="display:none">

				<h3><?php _e( 'Add Content', 'client-documentation' ); ?></h3>

				<ul class="add_list clearfix">
					<li>
						<a href="#video" data-type='video' id='smdoc_video_cat'><i class="fa fa-youtube-play"></i><br />
						<?php _e( 'Video', 'client-documentation' ); ?></a>
					</li>
					<li>
						<a href="#note" data-type='note' id='smdoc_note_cat'><i class="fa fa-comments"></i><br />
						<?php _e( 'Note', 'client-documentation' ); ?></a>
					</li>
					<li>
						<a href="#link" data-type='link' id='smdoc_link_cat'><i class="fa fa-link"></i><br />
						<?php _e( 'Link', 'client-documentation' ); ?></a>
					</li>
					<li>
						<a href="#file" data-type='file' id='smdoc_file_cat'><i class="fa fa-files-o"></i><br />
						<?php _e( 'File', 'client-documentation' ); ?></a>
					</li>
				</ul>

				<div class="smpldoc_form">
					<div id="smpldoc_overlay"></div>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="smpldoc_item_title"><?php _e( 'Title', 'client-documentation' ); ?></label></th>
								<td><input name="smpldoc_item_title" type="text" id="smpldoc_item_title" value="" class="large-text"></td>
							</tr>
							<tr valign="top" class="smpldoc_editor_field" id="smpldoc_editor">
								<td colspan="2" class="smpldoc_editor">
									<?php wp_editor( '', 'smpldoc_item_content', $settings = array(
										'media_buttons' => false,
										'teeny' => true
									)); ?>
								</td>
							</tr>
							<tr valign="top" class="smpldoc_link_field" style="display:none" id="smpldoc_input">
								<th scope="row"><label for="smpldoc_item_link"><?php _e( 'Link', 'client-documentation' ); ?></label></th>
								<td><input name="smpldoc_item_link" type="url" id="smpldoc_item_link" value="" class="large-text" placeholder="http://"></td>
							</tr>
							<tr valign="top" class="smpldoc_file_field" style="display:none" id="smpldoc_file">
								<th scope="row"><label for="smpldoc_item_file"><?php _e('File', 'client-documentation' ); ?></label></th>
								<td>
									<input name="smpldoc_item_file" type="hidden" id="smpldoc_item_file" value="">
									<input type="button" id="cd_button_file" tabindex="18" class="button-secondary cd_button_upload" value="<?php _e( 'Upload a file' , 'client-documentation' ); ?>"/>
									<span id="smpldoc_filename"><?php _e( 'Select a file', 'client-documentation' ); ?>...</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="smpldoc_item_users"><?php _e( 'Limit to user roles', 'client-documentation' ); ?></label><br />
									<small>
										<?php _e( 'Leave blank to use default', 'client-documentation' ); ?><br/>
										<?php _e( 'Current Defaults', 'client-documentation' ); ?>:<br />
										<?php

										$default_role_labels = array_map(
											'\\Simple_Documentation\\get_role_label',
											$this->settings['user_role']
										);

										$default_role_labels_translated = array_map( '__', $default_role_labels );

										echo join( ', ', $default_role_labels_translated );

										?>
									</small>
								</th>
								<td class="smplodc_user_items clearfix">
								<?php

								$roles = $wp_roles->roles;

				                foreach ( $roles as $srole => $vrole ) {
				                    echo '
				                    <p><input type="checkbox" name="smpldoc_item_users" class="smpldoc_item_users" value="'.$srole.'">'.__( $vrole['name'] ).'</p>';
				                }

				                ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"></th>
								<td class="smpldoc_submit"><a href="#" class="button button-primary" id="smpldoc_additem" data-action='add'>Add item</a></td>
							</tr>
						</tbody>
					</table>
				</div>

				<input type="hidden" id="item_type" value="nope" />
				<input type="hidden" id="item_id" value="nope" />
			</div>

		</div>

		<div class="smpldcmttn_side">
			<h3><?php _e( 'Information', 'client-documentation' ); ?></h3>

			<p><?php _e('Welcome to Simple Documentation.', 'client-documentation' ); ?></p>

			<h2><?php _e('Update', 'client-documentation' ); ?> 1.2</h2>
			<p><?php _e( "This new update comes with new features such as item re-ordering and restricting access per user.", 'client-documentation' ); ?></p>

			<h2><?php _e('Contribution', 'client-documentation' ); ?></h2>
			<ul>
				<li><?php _e('Spanish translation by', 'client-documentation' ); ?> <a href='http://sugartoys.net/'>Sugartoys</a></li>
				<li><?php _e('German translation by', 'client-documentation' ); ?> Alexander Pfabel</li>
				<li><?php _e('Serbo-Croatian translation by', 'client-documentation' ); ?> <a href='http://www.webhostinghub.com/'>Borisa Djuraskovic</a></li>
				<li><?php _e('Dutch translation by', 'client-documentation' ); ?> <a href='http://www.funsite.eu'>Gerhard Hoogterp</a></li>
			</ul>
			<p><?php _e('If you want to contribute, feel free to ', 'client-documentation' ); ?> <a href='mailto:mathieu@mathieuhays.co.uk'><?php _e('contact me', 'client-documentation' ); ?></a> <?php _e('or', 'client-documentation' ); ?> <a href='https://github.com/mathieuhays/Simple-Documentation'><?php _e('fork the project on github', 'client-documentation' ); ?></a></p>

			<p class="sd-credit"><?php _e('Plugin created by', 'client-documentation' ); ?> <a href='https://mathieuhays.co.uk'>Mathieu HAYS</a> - <a href='https://github.com/mathieuhays/Simple-Documentation/issues'><?php _e('Report issues', 'client-documentation' ); ?></a></p>
		</div>

	</div>

</div>
