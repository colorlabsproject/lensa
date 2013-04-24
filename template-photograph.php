          <?php while(have_posts()): the_post(); ?>
		  <li class="gallery-item">
		  <a href="<?php colabs_image('link=url'); ?>" rel="lightbox"><?php colabs_image('width=157&height=157&link=img'); ?></a>
		  <?php echo '<div class="like">
						<p class="entry-likes" data-like="'.get_like(get_the_ID()).'_'.get_the_ID().'">
							<i class="icon-heart '.$_COOKIE['like_'.get_the_ID()].'"></i> 
							<span>'.get_like(get_the_ID()).'</span> 
							'.__("Loves","colabsthemes").'
						</p>
					  </div>'; ?>
		  <?php echo '<div class="time">
						<p class="entry-time">
							<i class="icon-time"></i> 
							<span>'.get_the_date().'</span> 
						</p>
					  </div>'; ?>
		  </li>
		  <?php endwhile; ?>