<?php

	WP_CLI::add_command( 'simpledocumentation', 'SimpleDocumentation_import');
	
class SimpleDocumentation_import extends WP_CLI_Command{
	
	public function export(){
		global $wpdb;
		
		$essai = new WP_Query(array('posts_per_page', '5'));
		if($essai->have_posts()){
			while($essai->have_posts()): $essai->the_post();
				WP_CLI::line('Article:: '.get_the_title());
			endwhile;
			WP_CLI::success('Done');
		}
	}
	
}