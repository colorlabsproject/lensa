<?php
		
	$user=get_option('colabs_type_instagram');
	$limit=get_option('colabs_piccount_instagram');
	$tag=get_option('colabs_tag_instagram');
	$address=get_option('colabs_address_instagram');
	$getlatlang = getLatLng($address);
	if(empty($limit))$limit=10;
	$nextMaxId = '';
	$max_id = $nextMaxId;
	$piccounter = 1;
	$token = ColabsInstagram::getAccessToken();

	if( $token ) {
		if(!empty($getlatlang))
			$data = ColabsInstagram::getLocationBasedFeed($getlatlang);
		else{
			if(empty($tag)) $data = ColabsInstagram::getFeedByUserId($user, $max_id, $nextMaxId);
			else $data = ColabsInstagram::getFeedByTag($tag, $max_id, $nextMaxId);					
		}
		
		if(count($data) > 0){ 
			if(get_option('colabs_random_instagram')=='true') shuffle($data);	
			foreach($data as $obj){ if(intval($limit) > 0 && $piccounter > $limit) break;		
							
				$title = $obj->caption->text;
				$urlimg= $obj->images->low_resolution->url;
				$urlimgori= $obj->images->standard_resolution->url;
				$likes = $obj->likes->count;
				$time = date(get_option('date_format'), $obj->created_time);

				echo '<li class="gallery-item">
						<a href="'.$urlimgori.'" title="'.$title.'" rel="lightbox">
							'.colabs_image('width=280&link=img&return=true&src='.$urlimg).'
						</a>';
						
					echo '<div class="like">
							<p class="entry-likes">
								<i class="icon-heart"></i> 
								<span>'.$likes.'</span> 
								'.__("Loves","colabsthemes").'
							</p>
						  </div>'; 
					echo '<div class="time">
							<p class="entry-time">
								<i class="icon-time"></i> 
								<span>'.$time.'</span> 
							</p>
						  </div>'; 
						
				echo '</li>';	
					  
								
				$piccounter++;
			}
		}
	} else { ?>

		<h1><?php echo _x('Whoops!! Something Wrong', 'Instagram error message', 'colabsthemes'); ?></h1>
		<p><?php echo _x("Problem fetching data from Instagram or perhaps you haven't set your instagram account.", 'Instagram error message', 'colabsthemes'); ?></p>

	<?php }
?>    