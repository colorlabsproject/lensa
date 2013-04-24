		<?php
		$content = file_get_contents("https://picasaweb.google.com/data/feed/base/user/".get_option('colabs_username_picasa')."?alt=rss&kind=photo&hl=id&imgmax=1600&max-results=".get_option('colabs_piccount_picasa')."&start-index=1");
			$x = new SimpleXmlElement($content);
			
			foreach($x->channel->item as $entry => $value){
			
				$title = $value->title;
				$image = $value->enclosure->attributes()->url;
				$urlimg= $image[0];
				$urlimgori= $image[0];
				$date = str_ireplace('+0000', '', $value->pubDate);

				echo '<li class="gallery-item">
						<a href="'.$urlimgori.'" title="'.$title.'" rel="lightbox">
							'.colabs_image('width=280&link=img&return=true&src='.$urlimg).'
						</a>
							<div class="time">
								<p class="entry-time">
									<i class="icon-time"></i> 
									<span>'.$date.'</span> 
								</p>
							</div>
					  </li>';
					
			}
		?>    