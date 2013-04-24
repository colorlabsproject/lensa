		<?php
		
			$f = new phpFlickr(get_option('colabs_api_flickr'),get_option('colabs_secret_flickr'));
			$recent = $f->people_getPublicPhotos(get_option('colabs_username_flickr'), NULL, NULL, get_option('colabs_piccount_flickr'), $paged);
			
			foreach ($recent['photos']['photo'] as $photo) { 

				$title 	= $photo['title'];
				$urlimg	= $f->buildPhotoURL($photo,"small");
				$urlimgori = $f->buildPhotoURL($photo,"large");
				
				$info = $f->photos_getInfo($photo['id']);
				$date = date(get_option('date_format'),$info['photo']['dateuploaded']);
				$view = $info['photo']['views'];
				 
					echo '<li class="gallery-item">
							<a href="'.$urlimgori.'" title="'.$title.'" rel="lightbox">
								'.colabs_image('width=280&link=img&return=true&src='.$urlimg).'
							</a>
							<div class="like">
								<p class="entry-likes">
									<span>'.$view.' </span> 
									<i class="icon-heart"></i> 
								</p>
							</div>
							<div class="time">
								<p class="entry-time">
									<i class="icon-time"></i> 
									<span>'.$date.'</span> 
								</p>
							</div>
						  </li>';	
			}
		?>    