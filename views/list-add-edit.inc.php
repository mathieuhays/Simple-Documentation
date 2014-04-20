<?php
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	global $wp_roles, $wpdb;
    	
    	$entries = $wpdb->get_results("SELECT * FROM $wpdb->simpleDocumentation ORDER BY ordered ASC");
	?>
	<div class="wrap">
		
		<h2><?php _e( 'Simple Documentation', $this->slug ); ?> <a href="#add_new_content" class="add-new-h2" id="swtch_btn">Add New</a></h2>

		<div class="<?php echo $this->slug; ?> clearfix">
			
			<div class="smpldcmttn_main">
				
				<div id="sd_list">
				<h3><?php _e( 'Documentation list', $this->slug ); ?></h3>
				
				<ul class="list_doc" id="simpledoc_list">
					
					<?php
						$i=0;
						foreach($entries as $item){
							$id = $item->ID;
							$icon = $this->icon($item->type);
							$title = stripslashes($item->title);
							$content = htmlspecialchars_decode(stripslashes($item->content));
							if($item->type == 'file'){
								$url = htmlspecialchars(wp_get_attachment_url($item->attachment_id));
								$content = "<a href='$url'>$url</a>";
							}
							if($item->type == 'link') $content = "<a href='$content'>$content</a>";
							
							$usersAllowed = '';
							
							if($item->restricted){ $users = json_decode( $item->restricted ); }
							else{ 
								$usersAllowed = __( '(default) ', $this->slug );
								$users = $this->settings['user_role'];
							}
							
							$registered_usr = $wp_roles->roles;
							$ua = 0;
							
							foreach($users as $user){
								if($ua>0) $usersAllowed .= ', ';
								/*if(in_array($user, $registered_usr) > -1)*/ $usersAllowed .= __( $registered_usr[$user]['name'], $this->slug );
								$ua++;
							}
							
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
										<i class='fa fa-user smpldoc_usersallowed' title='$usersAllowed'></i>
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
				
					<h3>Add Content</h3>
					
					<ul class="add_list clearfix">
						<li>
							<a href="#video" data-type='video' id='smdoc_video_cat'><i class="fa fa-youtube-play"></i><br />
							Video</a>
						</li>
						<li>
							<a href="#note" data-type='note' id='smdoc_note_cat'><i class="fa fa-comments"></i><br />
							Note</a>
						</li>
						<li>
							<a href="#link" data-type='link' id='smdoc_link_cat'><i class="fa fa-link"></i><br />
							Link</a>
						</li>
						<li>
							<a href="#file" data-type='file' id='smdoc_file_cat'><i class="fa fa-files-o"></i><br />
							File</a>
						</li>
					</ul>
					
					<div class="smpldoc_form">
						<div id="smpldoc_overlay"></div>
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row"><label for="smpldoc_item_title">Title</label></th>
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
									<th scope="row"><label for="smpldoc_item_link"><?php _e( 'Link', $this->slug ); ?></label></th>
									<td><input name="smpldoc_item_link" type="url" id="smpldoc_item_link" value="" class="large-text" placeholder="http://"></td>
								</tr>
								<tr valign="top" class="smpldoc_file_field" style="display:none" id="smpldoc_file">
									<th scope="row"><label for="smpldoc_item_file"><?php _e('File', $this->slug ); ?></label></th>
									<td>
										<input name="smpldoc_item_file" type="hidden" id="smpldoc_item_file" value="">
										<input type="button" id="cd_button_file" tabindex="18" class="button-secondary cd_button_upload" value="<?php _e( 'Upload a file' , $this->slug ); ?>"/>
										<span id="smpldoc_filename"><?php _e( 'Select a file', $this->slug ); ?>...</span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										<label for="smpldoc_item_users">Limit to user roles</label><br />
										<small>
											Leave blank to use default<br/>
											Current Defaults:<br />
											<?php 
												$i=0;
												foreach($this->settings['user_role'] as $defrol){ 
													if($i>0) echo ', ';
													_e( $defrol, $this->slug );
													$i++;
												} 
											?>
										</small>
									</th>
									<td class="smplodc_user_items clearfix">
									<?php
						            	$roles = $wp_roles->roles;
						                foreach($roles as $srole => $vrole){
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
					
					<input type="hidden" id="item_type" value='nope' />
					<input type="hidden" id="item_id" value='nope' />
				</div>
			
			</div>
			
			<div class="smpldcmttn_side">
				<h3><?php _e( 'Information', $this->slug ); ?></h3>
				
				<p>Welcome to Simple Documentation.</p>
				
				<h4>Add new content</h4>
				<p>Maecenas sed diam eget risus varius blandit sit amet non magna. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>
				
				<h4>Re-order</h4>
				<p>Maecenas sed diam eget risus varius blandit sit amet non magna. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>
				
				<h4>Settings</h4>
				<p>Maecenas sed diam eget risus varius blandit sit amet non magna. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>
				
				<h4>Import / Export</h4>
				<p>Maecenas sed diam eget risus varius blandit sit amet non magna. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>
			</div>
		
		</div>
	
	</div>