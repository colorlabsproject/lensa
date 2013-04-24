<style>
#main #panel-content .regular-text {
    margin: 5px 0 15px;
		width: 30%;
}
.form-wrap > div {
    float: left;
    width: 100%;
}
</style>
<div id="colabs_options" class="wrap <?php if (get_bloginfo('text_direction') == 'rtl') { echo 'rtl'; } ?> colabs_instagram">

	<div class="one_col wrap colabs_container">
    
            <div class="clear"></div>
            <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save">Options Updated</div></div>
            <div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset">Options Reset</div></div>
            <div style="width:100%;padding-top:15px;"></div>
            <div class="clear"></div>
        
	<div id="main">
        
	<div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
	</div><!-- #panel-header -->

    <div id="panel-content">
	
	<?php
		$isPHP5 = (version_compare(phpversion(), '5.0.0', '>='));
		
		if(!$isPHP5)
		{
			$colabsErrors = array(sprintf(__('colabs requires at least PHP 5.0 to work properly, your version is: %s', 'colabsthemes'), phpversion()));
		}
		else
		{
			// Fehlermeldungen ausgeben 
			$colabsErrors = ColabsInstagram::getInstance()->getErrors();
		}
		
		if($colabsErrors): 
	?>
	<div class="error">
		<?php foreach($colabsErrors as $colabsError): ?>
		<p>
		 	<?php echo $colabsError; ?>
		</p>
		<?php endforeach; ?>
	</div>
	<?php 
		endif; // $colabsErrors 
	?>
	
	<?php if($isPHP5): ?>
	<form method="post">
	
    <div class="section">
    
    	<h3 class="heading"><?php _e( 'Instagram Account Settings', 'colabsthemes' ); ?></h3>
    	<div class="option">
			<?php 
	
			$instagramOptions = ColabsInstagram::getInstance()->getOptions();
				
			if(ColabsInstagram::getInstance()->getAccessToken()):
				echo '<p class="success">';
				_e('Your application is authorized, have fun!', 'colabsthemes');
				echo '</p>';
			?>
				<p>
					<input type="submit" class="button" name="instagram-reset-settings" value="<?php _e('Reset settings', 'colabsthemes'); ?>" />
				<p>
			<?php
			else:
			?>
				<p><?php _e('To activate colabs just enter your Instagram username and password', 'colabsthemes') ?>:</p>
		
				<div class="form-wrap">
		
					<div>
						<label for="instagram-app-client-id"><?php _e('Username', 'colabsthemes') ?></label>
						<input class="regular-text" type="text" id="instagram-app-user-username" name="instagram-app-user-username" value="<?php echo esc_attr( $instagramOptions['app_user_username'] ) ?>" />				
					</div>
					<div>
						<label for="instagram-app-client-secret"><?php _e('Password', 'colabsthemes') ?></label>
						<input class="regular-text" type="password" id="instagram-app-user-password" name="instagram-app-user-password" value="<?php echo esc_attr( $instagramOptions['app_user_password'] ) ?>" />					
					</div>
					<div>
						<input type="submit" class="button" name="instagram-update-auth-settings" value="<?php _e('Save settings', 'colabsthemes'); ?>" />
					</div>
				
				</div><!--/.form-wrap-->
			<?php endif;?>
    	</div><!-- .option -->

    </div><!-- .section -->
	
	<div class="section">
		<h3 class="heading"><?php _e( 'Instagram General settings', 'colabsthemes' ); ?></h3>
    	<div class="option">
			
			<div class="form-wrap">
				<div>
					<?php 
					if(ColabsInstagram::getInstance()->cacheIsEnabled()): // Cache aktiv?
					?>
					<p>
						<label for="instagram-cache-time"><?php _e('Refresh cache after', 'colabsthemes') ?></label>
						<?php 
							$possibleCacheTimes = array(5, 10, 15, 30, 45, 60);
						?>
						<select id="instagram-cache-time" name="instagram-cache-time">
							<?php foreach($possibleCacheTimes as $value): ?>
								<option <?php echo esc_attr( $instagramOptions['app_cache_time'] ) == $value ? ' selected="selected"' : '' ?>><?php echo $value ?></option>
							<?php endforeach; ?>
						</select><?php _e('minutes', 'colabsthemes') ?>
					</p>
					<?php 
						else: // Cache inaktiv
					?>
					
						<p><?php _e('Cache is not active', 'colabsthemes'); ?></p>
					
					<?php 
						endif;
					?>
				</div>
				<div>
					<input type="submit" class="button" name="instagram-update-settings" value="<?php _e('Save settings', 'colabsthemes'); ?>" />
				</div>
			</div>
		</div>
	</div>
	</form>
    <?php endif; // ($isPHP5): ?>
	
    </div><!-- #panel-content -->

    <div id="panel-footer">
      <ul>
          <li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" >View Documentation</a></li>
          <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank">Submit a Support Ticket</a></li>
          <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank">Suggest a Feature</a></li>
      </ul>
  	</div><!-- #panel-footer -->
	</div><!-- #main -->

	</div><!-- .colabs_container -->
    
</div><!-- #colabs_options -->
