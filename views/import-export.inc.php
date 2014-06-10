<?php

	if ( ! defined( 'ABSPATH' ) ) exit;
	
?>
<div class="wrap sd_importexport">
	<h2><?php _e('Import / Export', $this->slug ); ?></h2>

	<h3><?php _e('Export', $this->slug ); ?></h3>
	<p>
		<textarea id="sd_export" class="large-text disabled" disabled></textarea>
	</p>
	<p>
		<a href="#export" class="button button-primary sd_export_button" id="sd_export_button"><?php _e( 'Export', $this->slug ); ?></a> 
		<input type="checkbox" id="sd_export_options" value="include" checked /> 
		<label for="sd_include_options"><?php _e( 'Include options', $this->slug ); ?></label>
		<small>( <?php _e( 'if included, options will be overwritten', $this->slug ); ?> )</small>
	</p>
	
	<h3 class="top_spacer"><?php _e('Import', $this->slug ); ?></h3>
	<p>
		<textarea id="sd_import" class="large-text"></textarea>
	</p>
	<p>
		<a href="#import" class="button button-primary sd_import_button" id="sd_import_button"><?php _e( 'Import', $this->slug ); ?></a> 
	</p>
</div>