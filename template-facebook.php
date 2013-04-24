<?php
			$session_key= get_option('fb-session-key');
			$session_sec= get_option('fb-session-secret');
			if(($session_key!='')&&($session_sec!='')){	
				$defaults = array('hideHead'=> true); 
				$album_content = colabs_facebook_fetch_album_content(get_post_meta($post->ID,'facebook_gallery_id',true),$defaults);
				echo $album_content['content'];
			}
?>