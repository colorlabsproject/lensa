		<?php	
			
			$user=get_option('colabs_username_pinterest');
			$limit=get_option('colabs_piccount_pinterest');
			$board=get_option('colabs_board_pinterest');
			if(empty($limit))$limit=20;

			if(!empty($board))$feed_url = 'http://pinterest.com/'.$user.'/'.$board.'/rss'; 
			else $feed_url = 'http://pinterest.com/'.$user.'/feed.rss';	
			
			$latest_pins = colabs_pinterest_get_rss_feed( $user, $limit, $feed_url );
			if(!empty( $latest_pins ) ){$ii=0;
				foreach ( $latest_pins as $item ):
							
					$rss_pin_description = $item->get_description();
					preg_match('/href="([^"]*)"/', $rss_pin_description, $link); $href = $link[1]; unset($link);	
					preg_match('/src="([^"]*)"/', $rss_pin_description, $image); $urlimg = $image[1]; unset($image);				
					$title = strip_tags( $rss_pin_description );
					$date = $item->get_date(get_option('date_format'));
				
					echo '<li class="gallery-item">
							<a href="'.str_ireplace('_b.jpg','_c.jpg',$urlimg).'" title="'.$title.'" rel="lightbox">
								'.colabs_image('width=280&link=img&return=true&src='.$urlimg).'
							</a>
							<div class="time">
								<p class="entry-time">
									<i class="icon-time"></i> 
									<span>'.$date.'</span> 
								</p>
							</div>
						  </li>';
					
				endforeach;
			}
		?>    

